<?php
/**
 * Employee contract form Livewire Component
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Livewire;

use App\Models\ContractSetting;
use App\Models\EmployeeContract;
use App\Models\Role;
use App\Models\TeamSetting;
use App\Notifications\NewContractNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Livewire\Component;
/**
 *  Create Employee contract form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeeContractForm extends Component
{
    public $company;
    public $employee;
    public $roles;
    public $benefits_list;
    //contract
    public $contract_role_id;
    public $contract_start_at;
    public $contract_end_at;
    public $contract_availability;
    public $contract_min_week_time;
    public $contract_max_week_time;
    public $contract_salary_term;
    public $contract_salary_amount;
    public $contract_benefits;

    protected $listeners = [
        'resetEmployeeContractInput' => 'resetInputFields',
        'saveContractForm' => 'submitForm',
        'contractCheck' => 'contractVerification'
    ];

    protected $messages = [
        'contract_min_week_time.regex' => 'The minimum week time format is not correct.',
        'contract_max_week_time.regex' => 'The maximum week time format is not correct.',
    ];

    /**
     * Mount
     *
     * @param mixed $company  Company
     * @param mixed $employee Employee
     *
     * @return void
     */
    public function mount($company, $employee)
    {
        $this->company = $company;
        $this->employee = $employee;
        $this->roles = Role::whereNotIn('id', [1,5])->get();
        $this->benefits_list = TeamSetting::where('team_id', $this->company->id)
            ->where('type', 'benefit')->get();
    }
    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view(
            'livewire.employee-contract-form',
            [
                'roles'=>$this->roles,
                'benefits_list'=> $this->benefits_list,
            ]
        );
    }

    /**
     * Rules
     *
     * @return array
     */
    public function rules()
    {
        return [
            'contract_role_id' => ['required', 'exists:roles,id'],
            'contract_start_at' => ['required', 'date', 'after_or_equal:today'],
            'contract_end_at' => ['nullable', 'date', 'after:contract_start_at'],
            'contract_availability' => ['required', Rule::in(['full-time', 'partial-time'])],
            'contract_min_week_time' => ['required_if:contract_availability,partial-time', 'nullable', 'regex:/(\d+\:\d+:\d+)/'],
            'contract_max_week_time' => ['nullable', 'regex:/(\d+\:\d+:\d+)/'],
            'contract_salary_term' => ['required', Rule::in(['hourly', 'monthly', 'annual'])],
            'contract_salary_amount' => ['required', 'numeric'],
            'contract_benefits' => ['nullable', 'array'],
        ];
    }

    /**
     * Updated
     *
     * @param mixed $propertyName Property Name
     *
     * @return void
     */
    public function updated($propertyName)
    {

        $this->validateOnly($propertyName);

    }

    /**
     * ResetcInput Fields
     *
     * @return void
     */
    public function resetInputFields()
    {
        $this->reset(
            [
                'contract_role_id',
                'contract_start_at',
                'contract_end_at',
                'contract_availability',
                'contract_min_week_time',
                'contract_max_week_time',
                'contract_salary_term',
                'contract_salary_amount',
                'contract_benefits',
            ]
        );
        $this->resetErrorBag();
        $this->dispatchBrowserEvent('closeNewContractModal');
    }

    /**
     * Submit Form
     *
     * @return void
     */
    public function submitForm()
    {
        $this->validate();

        DB::beginTransaction();

        try {
            $company_contract = ContractSetting::where('team_id', $this->company->id)
                ->where('type', $this->contract_availability)
                ->first();

            $agreement = null;
            $agreement_status = null;

            if ($company_contract!=null) {
                $agreement = $company_contract->agreement;
                $agreement_status= 'pending';
            }

            $contract = EmployeeContract::create(
                [
                    'role_id'=> $this->contract_role_id,
                    'start_at'=> $this->contract_start_at,
                    'end_at'=> $this->contract_end_at,
                    'availability'=> $this->contract_availability,
                    'min_week_time'=> $this->contract_min_week_time,
                    'max_week_time'=> $this->contract_max_week_time,
                    'salary_term'=> $this->contract_salary_term,
                    'salary_amount'=> $this->contract_salary_amount,
                    'agreement'=>$agreement,
                    'agreement_status'=>$agreement_status,
                ]
            );
            if ($this->contract_benefits) {
                foreach ($this->contract_benefits as $key => $benefit) {
                    $contract->teamSettings()->attach($benefit);
                }
            }
            $this->employee->employees()
                ->attach($contract->id, ['team_id'=>$this->company->id]);

            if ($this->employee->active==false) {
                $this->employee->active = true;
                $this->employee->save();
            }

            DB::commit();
            $this->employee->notify(new NewContractNotification($contract));

            $message_type = 'success';
            $message = 'Success message';
        } catch (\Throwable $th) {
            DB::rollBack();

            $message_type = "error";
            $message = $th->getMessage();
        }



        $this->dispatchBrowserEvent(
            'swalContract:form',
            [
                'icon'=>$message_type,
                'title' => $message,
                'text' => '',
            ]
        );
        //dd($this->employee->contractCompany($this->company->id));
        $this->dispatchBrowserEvent('closeContractModal');
    }

    /**
     * Contract Verification
     *
     * @return void
     */
    public function contractVerification()
    {

        $last_contract = $this->employee
            ->contractCompany($this->company->id)
            ->first();

        if ($last_contract->termination_at == null && ($last_contract->end_at == null || $last_contract->end_at >= now())) {

            $this->dispatchBrowserEvent(
                'swalContract:form',
                [
                    'icon'=>'error',
                    'title' => 'The actual contract must be terminated before creating a new one',
                    'text' => '',
                ]
            );
        } else {
            $this->dispatchBrowserEvent('openContractModal');
        }
    }
}

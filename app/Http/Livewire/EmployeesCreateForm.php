<?php
/**
 * Employee create form Livewire Component
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

use App\Models\EmployeeContract;
use App\Models\Role;
use App\Models\TeamSetting;
use App\Models\User;
use App\Notifications\EmployeeCreatedNotification;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use PragmaRX\Countries\Package\Countries;
/**
 *  Create Employee form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeesCreateForm extends Component
{
    use WithFileUploads;
    //employee
    public $employee_photo;
    public $employee_name;
    public $employee_email;
    public $employee_birthdate;
    public $employee_gender;
    public $employee_ssn;
    public $employee_password;
    public $employee_password_confirmation;
    //address
    public $address_type="primary";
    public $address_suite;
    public $address_number;
    public $address_street;
    public $address_city;
    public $address_region;
    public $address_country;
    public $address_postcode;
    //contact
    public $contact_mobile;
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
    //general porpose
    public $company;
    public $roles;
    public $my_role;
    public $countries;
    public $country_cities;
    public $benefits_list;
    public $currentStep = 1;

    protected $messages = [
        'employee_birthdate.before'=>'The employee must have 18 years old.',
        'contract_min_week_time.regex' => 'The minimum week time format is not correct.',
        'contract_max_week_time.regex' => 'The maximum week time format is not correct.',
    ];

    protected $listeners = ['sendToEmployeeList' => 'sendtohome'];

    /**
     * Mount
     *
     * @param mixed $company Company
     *
     * @return void
     */
    public function mount($company)
    {
        $this->company = $company;
        $this->roles = Role::whereNotIn("id", [1,5])->get();
        $this->countries = Countries::all()->pluck('name.common', 'cca3')
            ->toArray();
        $this->benefits_list = TeamSetting::where('team_id', $this->company->id)
            ->where('type', 'benefit')->get();
        //dd($this->benefits_list);
    }
    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view(
            'livewire.employees-create-form',
            [
                'roles' => $this->roles,
                'countries' => $this->countries,
                'benefits_list' => $this->benefits_list->pluck('display_name', 'id'),
            ]
        );
    }

    /**
     * Rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'employee_photo'=>['nullable','image','mimes:png,jpg,jpeg,gif,svg','max:2048'],
            'employee_name'=>['required', 'string', 'min:3', 'max:255'],
            'employee_birthdate'=>['required', 'date', 'before:now -18 years'],
            'employee_email'=>['required', 'email:rfc,dns', 'unique:users,email'],
            'employee_gender'=>['required', Rule::in(['male', 'female', 'other'])],
            'employee_ssn'=>['required', 'numeric', 'digits:9', 'unique:users,ssn'],
            'employee_password'=>[
                'required',
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
                'confirmed',
            ],
            'employee_password_confirmation'=>['required', 'same:employee_password'],
            'contact_mobile'=>['required', 'string', 'min:7', 'max:18'],
            'address_type' => ['required', Rule::in(
                [
                    'primary',
                    'secondary',
                    'other',
                ]
            )],
            'address_suite' => ['nullable', 'numeric'],
            'address_number' => ['required', 'numeric'],
            'address_street' => ['required', 'string', 'min:5', 'max:255'],
            'address_city' => ['required'],
            'address_region' => ['required'],
            'address_country' => ['required'],
            'address_postcode' => ['nullable', 'string', 'min:4', 'max:9'],
            'contract_role_id' => ['required', 'exists:roles,id'],
            'contract_start_at' => ['required', 'date', 'after_or_equal:today'],
            'contract_end_at' => ['nullable', 'date', 'after:contract_start_at'],
            'contract_availability' => ['required', Rule::in(['full-time', 'partial-time'])],
            'contract_min_week_time' => ['required_if:contract_availability,partial-time', 'nullable', 'regex:/(\d+\:\d+:\d+)/'],
            'contract_max_week_time' => ['nullable', 'regex:/(\d+\:\d+:\d+)/'],
            'contract_salary_term' => ['required', Rule::in(['hourly', 'monthly', 'annual'])],
            'contract_salary_amount'=> ['required', 'numeric'],
            'contract_benefits'=>['nullable', 'array'],
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
     * Updated Employee Password Confirmation
     *
     * @return void
     */
    public function updatedEmployeePasswordConfirmation()
    {
        $this->validateOnly($this->employee_password);
    }
    /**
     * Updated Country
     *
     * @return void
     */
    public function updatedAddressCountry()
    {
        $this->country_cities =  Countries::where('cca3', $this->address_country)->first()
            ->hydrate('cities')
            ->cities
            ->pluck('name', 'nameascii')
            ->toArray();

        $this->address_region = null;
        $this->address_city = null;
        $this->resetErrorBag(['address_city', 'address_region']);

    }

    /**
     * Updated City
     *
     * @return void
     */
    public function updatedAddressCity()
    {
        $myRegion =  Countries::where('cca3', $this->address_country)->first()
            ->hydrateCities()
            ->cities
            ->where('nameascii', $this->address_city)
            ->first()
            ->adm1name;
        $this->address_region = utf8_decode($myRegion);
    }

    /**
     * Rules1
     *
     * @return void
     */
    public function rules1()
    {
        return [
            'employee_photo'=>['nullable','image','mimes:png,jpg,jpeg,gif,svg','max:2048'],
            'employee_name'=>['required', 'string', 'min:3', 'max:255'],
            'employee_birthdate'=>['required', 'date', 'before:now -18 years'],
            'employee_email'=>['required', 'email:rfc,dns', 'unique:users,email'],
            'employee_gender'=>['required', Rule::in(['male', 'female', 'other'])],
            'employee_ssn'=>['required', 'numeric', 'digits:9', 'unique:users,ssn'],
            'employee_password'=>[
                Password::min(8)
                    ->letters()
                    ->mixedCase()
                    ->numbers()
                    ->symbols()
                    ->uncompromised(),
            ],
            'employee_password_confirmation'=>['required', 'same:employee_password'],
            'contact_mobile'=>['required', 'string', 'min:7', 'max:18'],
        ];
    }

    /**
     * Rules2
     *
     * @return void
     */
    public function rules2()
    {
        return [
            'address_type' => ['required', Rule::in(
                [
                    'primary',
                    'secondary',
                    'other',
                ]
            )],
            'address_suite' => ['nullable', 'numeric'],
            'address_number' => ['required', 'numeric'],
            'address_street' => ['required', 'string', 'min:5', 'max:255'],
            'address_city' => ['required'],
            'address_region' => ['required'],
            'address_country' => ['required'],
            'address_postcode' => ['nullable', 'string', 'min:4', 'max:9'],
        ];
    }

    /**
     * Rules3
     *
     * @return void
     */
    public function rules3()
    {
        return [
            'contract_role_id' => ['required', 'exists:roles,id'],
            'contract_start_at' => ['required', 'date', 'after_or_equal:today'],
            'contract_end_at' => ['nullable', 'date', 'after:contract_start_at'],
            'contract_availability' => ['required', Rule::in(['full-time', 'partial-time'])],
            'contract_min_week_time' => ['required_if:contract_availability,partial-time', 'nullable', 'regex:/(\d+\:\d+:\d+)/'],
            'contract_max_week_time' => ['nullable', 'regex:/(\d+\:\d+:\d+)/'],
            'contract_salary_term' => ['required', Rule::in(['hourly', 'monthly', 'annual'])],
            'contract_salary_amount'=> ['required', 'numeric'],
            'contract_benefits'=>['nullable', 'array'],
        ];
    }

    /**
     * Back step
     *
     * @return previous step
     */
    public function myPreviousStep()
    {
        $this->currentStep -= 1;
    }

    /**
     * Fists step validation
     *
     * @return validated fields
     */
    public function myNextStep()
    {

        if ($this->currentStep == 1) {
            $this->validate($this->rules1());
        }
        if ($this->currentStep == 2) {
            $this->validate($this->rules2());
        }
        if ($this->currentStep == 3) {
            $this->validate($this->rules3());
            $this->my_role = $this->roles->where('id', $this->contract_role_id)->first()->display_name;
        }

        $this->currentStep += 1;
    }

    /**
     * Reset Input Fields
     *
     * @return void
     */
    public function resetInputFields()
    {
        $this->reset(
            [
                'employee_photo',
                'employee_name',
                'employee_email',
                'employee_birthdate',
                'employee_gender',
                'employee_ssn',
                'employee_password',
                'employee_password_confirmation',
                'address_suite',
                'address_number',
                'address_street',
                'address_city',
                'address_region',
                'address_country',
                'address_postcode',
                'contact_mobile',
                'contract_role_id',
                'contract_start_at',
                'contract_end_at',
                'contract_availability',
                'contract_min_week_time',
                'contract_max_week_time',
                'contract_salary_term',
                'contract_salary_amount',
                'contract_benefits'
            ]
        );
        $this->resetErrorBag();
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

            if ($this->employee_photo) {
                $filename = Str::random() . time() . '.' . $this->employee_photo->extension();
            } else {
                $filename = null;
            }

            $employee = User::create(
                [
                    'photo'     => $filename,
                    'name'      =>$this->employee_name,
                    'email'     => $this->employee_email,
                    'birthdate' => $this->employee_birthdate,
                    'gender'    => $this->employee_gender,
                    'ssn'       => $this->employee_ssn,
                    'password'  => bcrypt($this->employee_password),
                ]
            );

            $employee->addresses()->create(
                [
                    'type'    => $this->address_type,
                    'suite'   => $this->address_suite,
                    'number'  => $this->address_number,
                    'street'  => $this->address_street,
                    'city'    => $this->address_city,
                    'region'  => $this->address_region,
                    'country' => $this->address_country,
                    'postcode'=> $this->address_postcode
                ]
            );

            $employee->contacts()->create(
                [
                    'priority'    => 'main',
                    'type'        => 'mobile',
                    'description' => $this->contact_mobile
                ]
            );

            $employee->attachRole($this->contract_role_id, $this->company);

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
                ]
            );

            if ($this->contract_benefits) {
                foreach ($this->contract_benefits as $key => $benefit) {
                    $contract->teamSettings()->attach($benefit);
                }
            }

            $employee->employees()
                ->attach($contract->id, ['team_id'=>$this->company->id]);

            DB::commit();

            if ($this->employee_photo) {
                $this->employee_photo->storeAs('public/images/profile/users', $filename);
                Image::make('storage/images/profile/users/'.$filename)
                    ->fit(200)
                    ->save('storage/images/profile/users/'.$filename);
            }

            $message_type = "success";
            $message = "Employee created successfully";

            $employee->notify(new EmployeeCreatedNotification($this->company));

            $this->resetInputFields();

        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();

            $message_type = "error";
            $message = $th->getMessage();

        }

        $this->dispatchBrowserEvent(
            'swalEmployee:form',
            [
                'icon'=>$message_type,
                'title' => $message,
                'text' => '',
            ]
        );
    }

    /**
     * Send to home
     *
     * @return void
     */
    public function sendtohome()
    {
        return redirect()->route('company.employees', ['company'=>$this->company]);
    }
}

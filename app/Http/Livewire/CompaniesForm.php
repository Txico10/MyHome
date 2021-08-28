<?php
/**
 * Company form Livewire Component
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

use App\Events\CompanyCreated;
use App\Models\EmployeeContract;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Livewire\Component;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Livewire\WithFileUploads;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;
use PragmaRX\Countries\Package\Countries;
use SebastianBergmann\Environment\Console;

/**
 *  Company form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class CompaniesForm extends Component
{
    use WithFileUploads;
    public $logo;
    public $display_name;
    public $slug;
    public $business_number;
    public $legal_form;
    public $description;
    public $address_type="primary";
    public $address_suite;
    public $address_number;
    public $address_street;
    public $address_city;
    public $address_region;
    public $address_country;
    public $address_postcode;
    public $contact_phone;
    public $contact_email;
    public $owner_name;
    public $owner_email;
    public $owner_birthdate;
    public $owner_ssn;
    public $owner_gender;
    public $owner_password;
    public $owner_password_confirmation;
    public $owner_mobile;

    public $countries;
    public $country_cities;
    public $currentStep = 1;

    protected $listeners = ['sendToHome' => 'sendtohome'];
    protected $messages = [
        'owner_birthdate.before'=>'The owner must have 18 years old.',
        'owner_password.regex'=>'The password must have at least one uppercase letter, one lowercase letter, one number and one special character',
    ];

    /**
     * Mount
     *
     * @return void
     */
    public function mount()
    {
        $this->countries = Countries::all()->pluck('name.common', 'cca3')
            ->toArray();
    }
    /**
     * Render
     *
     * @return View
     */
    public function render()
    {
        return view(
            'livewire.companies-form',
            [
                'countries' => $this->countries,
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
            'logo'            => ['nullable','image','mimes:png,jpg,jpeg,gif,svg','max:2048'], // 1MB Max;,
            'display_name'    => ['required', 'string', 'min:3', 'max:255'],
            'slug'            => ['required', 'unique:teams,slug'],
            'business_number' => ['required', 'digits_between:8,9', 'unique:teams,bn'],
            'legal_form'    => ['required', Rule::in(
                [
                    'Sole proprietorship',
                    'Business corporation',
                    'General partnership',
                    'Limited partnership',
                    'Cooperative'
                ]
            )],
            'description' => ['nullable', 'string', 'min:5', 'max:255'],
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
            'contact_phone' => [
                Rule::requiredIf(empty($this->contact_email)),
                'nullable',
                'string',
                'min:7',
                'max:18',
            ],
            'contact_email'=>[
                Rule::requiredIf(empty($this->contact_phone)),
                'nullable',
                'email:rfc,dns',
                'unique:contacts,description'
            ],
            'owner_name'=>['required', 'string', 'min:5', 'max:255'],
            'owner_email'=>['required', 'email:rfc,dns', 'unique:users,email'],
            'owner_birthdate'=>['required', 'date', 'before:now -18 years'],
            'owner_ssn' => ['nullable', 'numeric'],
            'owner_gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'owner_password' =>
                [
                    'required', 'min:8', 'max:15', 'confirmed',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/'
                ],
            'owner_mobile'=>['required', 'string', 'min:7', 'max:18'],
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
     * Updated Display Name
     *
     * @return void
     */
    public function updatedDisplayName()
    {
        $this->resetValidation('slug');
        $this->resetErrorBag('slug');
        $this->slug = Str::of($this->display_name)->slug('-');
        $this->validate(
            [
                'slug' => ['required', 'unique:teams,slug']
            ]
        );
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
     * Updated Contact Phone
     *
     * @return void
     */
    public function updatedContactPhone()
    {
        $this->resetErrorBag('contact_email');
    }

    /**
     * Updated Contact Email
     *
     * @return void
     */
    public function updatedContactEmail()
    {
        $this->resetErrorBag('contact_phone');
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
                'logo',
                'display_name',
                'slug',
                'business_number',
                'legal_form',
                'description',
                'address_type',
                'address_suite',
                'address_number',
                'address_street',
                'address_city',
                'address_region',
                'address_country',
                'address_postcode',
                'contact_phone',
                'contact_email',
                'owner_name',
                'owner_email',
                'owner_ssn',
                'owner_birthdate',
                'owner_gender',
                'owner_password',
                'owner_password_confirmation',
                'owner_mobile',
            ]
        );
        $this->resetErrorBag();
        $this->currentStep = 1;
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
        }

        $this->currentStep += 1;
    }

    /**
     * Rules for step 1
     *
     * @return validates rules
     */
    public function rules1()
    {
        return [
            'logo'            => ['nullable','image','mimes:png,jpg,jpeg,gif,svg','max:2048'], // 1MB Max;,
            'display_name'    => ['required', 'string', 'min:3', 'max:255'],
            'slug'            => ['required', 'unique:teams,slug'],
            'business_number' => ['required', 'digits_between:8,9', 'unique:teams,bn'],
            'legal_form'    => ['required', Rule::in(
                [
                    'Sole proprietorship',
                    'Business corporation',
                    'General partnership',
                    'Limited partnership',
                    'Cooperative'
                ]
            )],
            'description' => ['nullable', 'string', 'min:5', 'max:255']
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
            'contact_phone' => [
                Rule::requiredIf(empty($this->contact_email)),
                'nullable',
                'string',
                'min:7',
                'max:18',
            ],
            'contact_email'=>[
                Rule::requiredIf(empty($this->contact_phone)),
                'nullable',
                'email:rfc,dns',
                'unique:contacts,description'
            ],
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
            'owner_name'=>['required', 'string', 'min:5', 'max:255'],
            'owner_email'=>['required', 'email:rfc,dns', 'unique:users,email'],
            'owner_birthdate'=>['required', 'date', 'before:now -18 years'],
            'owner_gender' => ['required', Rule::in(['male', 'female', 'other'])],
            'owner_ssn' => ['nullable', 'numeric'],
            'owner_password' =>
                [
                    'required', 'min:8', 'max:15',
                    'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,15}$/',
                    'confirmed'
                ],
            'owner_mobile'=>['required', 'string', 'min:7', 'max:18'],
        ];
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
            if ($this->logo) {
                $filename = Str::random() . time() . '.' . $this->logo->extension();
            } else {
                $filename = "defaultCompany.png";
            }
            $company = Team::create(
                [
                    'slug'         => $this->slug,
                    'display_name' => $this->display_name,
                    'bn'           => $this->business_number,
                    'legalform'    => $this->legal_form,
                    'description'  => $this->description,
                    'logo'         => $filename,
                ]
            );

            $company->addresses()->create(
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

            if (!empty($this->contact_phone)) {
                $company->contacts()->create(
                    [
                        'priority'   => 'main',
                        'type'       => 'phone',
                        'description'=> $this->contact_phone
                    ]
                );
            }

            if (!empty($this->contact_email)) {
                $company->contacts()->create(
                    [
                        'priority'    => 'main',
                        'type'        => 'email',
                        'description' => $this->contact_email
                    ]
                );
            }

            $owner = User::create(
                [
                    'name'      =>$this->owner_name,
                    'email'     => $this->owner_email,
                    'birthdate' => $this->owner_birthdate,
                    'gender'    => $this->owner_gender,
                    'ssn'       => $this->owner_ssn,
                    'password'  => bcrypt($this->owner_password),
                ]
            );

            $owner->addresses()->create(
                [
                    'type' => 'primary'
                ]
            );

            $owner->contacts()->create(
                [
                    'priority'    => 'main',
                    'type'        => 'mobile',
                    'description' => $this->owner_mobile
                ]
            );

            $role = Role::where('name', 'owner')->first();

            $owner->attachRole($role, $company);

            $contract =EmployeeContract::create(
                [
                    'role_id' => $role->id,
                    'start_at' => now(),
                ]
            );

            $owner->employees()->attach($contract->id, ['team_id'=>$company->id]);

            DB::commit();

            event(new Registered($owner));
            event(new CompanyCreated($company));

            if ($this->logo) {
                $this->logo->storeAs('public/images/profile/companies', $filename);
                Image::make('storage/images/profile/companies/'.$filename)
                    ->fit(200)
                    ->save('storage/images/profile/companies/'.$filename);
            }



            $message_type = "success";
            $message = "Company created successfully";

            $this->dispatchBrowserEvent(
                'swalCompany:created',
                [
                    'icon'=>$message_type,
                    'title' => $message,
                    'text' => '',
                ]
            );

            $this->resetInputFields();

        } catch (\Throwable $th) {
            DB::rollBack();
            $message_type = "error";
            $message = $th->getMessage();

            $this->dispatchBrowserEvent(
                'swalCompany:notcreated',
                [
                    'icon'=>$message_type,
                    'title' => $message,
                    'text' => '',
                ]
            );
        }

        /*
        $this->dispatchBrowserEvent(
            'newTest',
            [
                'icon'=>'success',
                'title' => 'Hello world',
                'text' => '',
            ]
        );
        */

        //redirect to Dashboad
    }

}

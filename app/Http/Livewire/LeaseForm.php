<?php
/**
 * Lease form Livewire Component
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

use App\Events\LeaseCreated;
use App\Models\Lease;
use App\Models\Role;
use Livewire\Component;
use App\Models\User;
use PragmaRX\Countries\Package\Countries;
use Illuminate\Support\Facades\DB;

/**
 *  Create lease form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class LeaseForm extends Component
{
    public $company;
    public $currentStep=1;
    public $second_lessee = false;
    public $same_address=false;
    //lessee
    public $lessees = [];
    //lease
    public $lease_term;
    public $lease_start;
    public $lease_end;
    public $family_residence = true;
    public $family_residence_description;
    public $dwelling_co_ownership = false;
    public $subsidy_program = false;
    public $furniture_included = false;
    //address
    public $addresses = [];
    public $countries = [];
    public $country_cities = [];
    //dwelling
    public $buildings;
    public $building;
    public $apartments;
    public $apartment;
    public $apartment_heating;
    public $team_settings;
    public $dependencies = [];
    public $dependencies_company;
    public $dependencies_building = [];
    public $furnitures = [];
    public $furnitures_list = [];
    public $furnitures_company;
    public $appliances = [];
    public $appliances_list = [];
    public $appliances_company;
    //rent
    public $rent_amount=0.00;
    public $rent_recurrence;
    public $first_payment_at;
    public $payment_method;
    public $payment_methode_company;
    public $postdated_cheques=false;
    public $total_cost_services=0.00;
    //services and conditions
    public $service_company;
    public $snow_removal;
    public $repairs_before;
    public $repair_during;
    public $by_laws = false;
    public $by_laws_given_at;
    public $consumption_costs;
    public $cost_born_by = [];
    public $land_access = true;
    public $land_access_description;
    public $animals=false;
    public $animals_description;
    public $other_conditions_restrictions;

    /**
     * Mount
     *
     * @param mixed $company Company
     *
     * @return void
     */
    public function mount($company)
    {
        $this->company=$company->load('addresses', 'contacts', 'buildings.address', 'apartments.leases');

        $this->countries[] = Countries::all()->lazy(50)->pluck('name.common', 'cca3')
            ->toArray();
        $this->lessees[] = ['name'=>null, 'birthdate'=>null, 'gender'=>null, 'email'=>null, 'mobile'=>null];

        $this->addresses[] = ['suite'=>null, 'number'=>null, 'street'=>null, 'city'=>null, 'region'=>null, 'country'=>null, 'postcode'=>null];

        $this->team_settings = $this->company->settings()
            ->whereIn(
                'type',
                [
                    'dependencie','appliances', 'furniture',
                    'method_payment', 'service', 'snow_removal',
                    'consumption_cost', 'heating_of_dweeling'
                ]
            )
            ->get();

        $this->buildings = $this->company->buildings->pluck('display_name', 'id');
        $this->dependencies_company = $this->team_settings->where('type', 'dependencie')->pluck('display_name', 'id');
        $this->furnitures_company = $this->team_settings->where('type', 'furniture')->pluck('display_name', 'id');
        $this->appliances_company = $this->team_settings->where('type', 'appliances')->pluck('display_name', 'id');
        $this->payment_methode_company = $this->team_settings->where('type', 'method_payment')->pluck('display_name', 'id');
        $this->service_company = $this->team_settings->where('type', 'service')->pluck('display_name', 'id');
        $this->snow_removal = $this->team_settings->where('type', 'snow_removal')->pluck('display_name', 'id');
        $this->consumption_costs = $this->team_settings->where('type', 'consumption_cost')->pluck('display_name', 'id');
        $this->buildConsumptionArray();
        //$this->consumption_costs = $this->team_settings->where('type', 'consumption_cost')->map->only(['id', 'display_name']);
        //dd($this->cost_born_by);
    }

    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.lease-form');
    }

    /**
     * Buid_consumption_array
     *
     * @return void
     */
    public function buildConsumptionArray()
    {
        $my_size = $this->consumption_costs->count()+$this->snow_removal->count();
        for ($i=0; $i <$my_size ; $i++) {
            $this->cost_born_by [] = ['lessor'=>'', 'lessee'=>''];
        }
    }

    /**
     * Updated Addresses
     *
     * @param $value Value
     * @param $key   Key
     *
     * @return void
     */
    public function updatedAddresses($value, $key)
    {
        $parts = explode(".", $key);

        if (!empty($value) && strcmp($parts[1], 'country')==0) {
            $this->country_cities[$parts[0]] =  Countries::where('cca3', $value)->first()
                ->hydrate('cities')
                ->cities
                ->pluck('name', 'nameascii')
                ->toArray();
            $this->addresses[$parts[0]]['region'] = '';
            $this->addresses[$parts[0]]['city'] = '';
            $this->resetErrorBag(['addresses.'.$parts[0].'.city', 'addresses.'.$parts[0].'.region']);
        }

        if (!empty($value) && strcmp($parts[1], 'city')==0) {
            $myRegion =  Countries::where('cca3', $this->addresses[$parts[0]]['country'])->first()
                ->hydrateCities()
                ->cities
                ->where('nameascii', $value)
                ->first()
                ->adm1name;
            $this->addresses[$parts[0]]['region'] = utf8_decode($myRegion);
        }
        /*

        $this->address_region = null;
        $this->address_city = null;
        $this->resetErrorBag(['address_city', 'address_region']);
        */
        //dd($parts);

    }

    /**
     * Updated Second Lessee
     *
     * @return void
     */
    public function updatedSecondLessee()
    {
        if ($this->second_lessee) {
            $this->lessees[] = ['name'=>null, 'birthdate'=>null, 'gender'=>null, 'email'=>null, 'mobile'=>null];
            $this->addresses[] = ['suite'=>null, 'number'=>null, 'street'=>null, 'city'=>null, 'region'=>null, 'country'=>null, 'postcode'=>null];
            $this->countries[] = Countries::all()->lazy(50)->pluck('name.common', 'cca3');

        } else {
            unset($this->lessees[1]);
            $this->lessees = array_values($this->lessees);
            unset($this->addresses[1]);
            $this->addresses = array_values($this->addresses);
            if (!empty($this->country_cities[1])) {
                unset($this->country_cities[1]);
                $this->country_cities = array_values($this->country_cities);
            }

            unset($this->countries[1]);
            $this->countries = array_values($this->countries);
        }
    }

    /**
     * Updated Same Address
     *
     * @return void
     */
    public function updatedSameAddress()
    {

        //$this->validateOnly('addresses', $this->rules2());
        if ($this->same_address) {
            unset($this->addresses[1]);
            $this->addresses = array_values($this->addresses);

        } else {
            $this->addresses[1] = ['suite'=>null, 'number'=>null, 'street'=>null, 'city'=>null, 'region'=>null, 'country'=>null, 'postcode'=>null];
        }

    }

    /**
     * UpdatedBuilding
     *
     * @return void
     */
    public function updatedBuilding()
    {
        $this->reset('apartment');

        $this->apartments = $this->company->apartments
            ->where('building_id', $this->building)
            ->loadMissing('teamSettings')
            ->filter(
                function ($value, $key) {
                    $lease = $value->leases->last();
                    if (!empty($lease)) {
                        if (!empty($lease->end_at)) {
                            if ($lease->end_at->lessThanOrEqualTo(today())) {
                                return $value;
                            }
                        }
                    } else {
                        return $value;
                    }

                }
            );
            //->pluck('number', 'id');

        if ($this->furniture_included) {
            $this->reset(
                [
                    'appliances', 'appliances_list','dependencies',
                    'dependencies_building', 'furnitures', 'furnitures_list',
                ]
            );
            $this->furniture_included = false;
        }
        //dd($this->apartments);

    }

    /**
     * Updated Apartment
     *
     * @return void
     */
    public function updatedApartment()
    {
        if ($this->furniture_included) {
            $this->reset(
                [
                    'appliances', 'appliances_list','dependencies',
                    'dependencies_building', 'furnitures', 'furnitures_list',
                ]
            );
            $this->furniture_included = false;
        }
        $apartment = $this->company->apartments->where('id', $this->apartment)->first();
        $this->apartment_heating = $apartment->teamSettings->where('type', 'heating_of_dweeling')->first();
        //dd($this->apartment_heating);

    }

    /**
     * Updated Dependencies
     *
     * @param mixed $value Value
     * @param mixed $key   Key
     *
     * @return void
     */
    public function updatedDependencies($value, $key)
    {
        $parts = explode(".", $key);

        if (!empty($value) && strcmp($parts[1], 'type')==0) {
            if (!empty($this->building)) {
                $company_dependency = $this->team_settings->where('id', $value)->first();
                $dependencies = $company_dependency->dependencies()->where('building_id', $this->building)->get();
                $dependencies = $dependencies->loadMissing('leases');
                $dependencies = $dependencies->filter(
                    function ($value, $key) {
                        $lease = $value->leases->last();
                        if (!empty($lease)) {
                            if (!empty($lease->end_at)) {
                                if ($lease->end_at->lessThanOrEqualTo(today())) {
                                    return $value;
                                }
                            }
                        } else {
                            return $value;
                        }

                    }
                );
                $this->dependencies_building[$parts[0]] = $dependencies->pluck('number', 'id');
                $this->dependencies[$parts[0]]['number'] = null;
                $this->dependencies[$parts[0]]['price'] = 0.00;
            }
            $this->resetErrorBag('dependencies.*');
        }
    }

    /**
     * Updated Appliances
     *
     * @param mixed $value Value
     * @param mixed $key   Key
     *
     * @return void
     */
    public function updatedAppliances($value, $key)
    {
        $parts = explode(".", $key);
        if (!empty($value) && strcmp($parts[1], 'type')==0) {
            $company_appliance = $this->team_settings->where('id', $value)->first();
            $appliances = $company_appliance->accessories()->get();
            $appliances = $appliances->loadMissing('leases');
            $appliances = $appliances->filter(
                function ($value, $key) {
                    $lease = $value->leases->last();
                    if (!empty($lease)) {
                        if (!empty($lease->end_at)) {
                            if ($lease->end_at->lessThanOrEqualTo(today())) {
                                return $value;
                            }
                        }
                    } else {
                        return $value;
                    }

                }
            );
            //dd($appliances->first()->manufacturer_model);
            $this->appliances_list[$parts[0]] = $appliances->pluck('model', 'id');
            $this->appliances[$parts[0]]['id'] = null;
            $this->appliances[$parts[0]]['price'] = 0.00;
            $this->resetErrorBag('appliances.*');
        }
    }

    /**
     * Updated Furnitures
     *
     * @param mixed $value Value
     * @param mixed $key   Key
     *
     * @return void
     */
    public function updatedFurnitures($value, $key)
    {
        $parts = explode(".", $key);
        if (!empty($value) && strcmp($parts[1], 'type')==0) {
            $company_furniture = $this->team_settings->where('id', $value)->first();
            $furnitures = $company_furniture->accessories()->get();
            $furnitures = $furnitures->loadMissing('leases');
            $furnitures = $furnitures->filter(
                function ($value, $key) {
                    $lease = $value->leases->last();
                    if (!empty($lease)) {
                        if (!empty($lease->end_at)) {
                            if ($lease->end_at->lessThanOrEqualTo(today())) {
                                return $value;
                            }
                        }
                    } else {
                        return $value;
                    }

                }
            );
            //dd($appliances);
            $this->furnitures_list[$parts[0]] = $furnitures->pluck('model', 'id');
            $this->furnitures[$parts[0]]['id'] = null;
            $this->furnitures[$parts[0]]['price'] = 0.00;
            $this->resetErrorBag('furnitures.*');
        }
    }

    /**
     * Updated Furniture Included
     *
     * @return void
     */
    public function updatedFurnitureIncluded()
    {
        if ($this->furniture_included) {
            if (!empty($this->apartment)) {
                $apartment = $this->company->apartments->where('id', $this->apartment)->first();
                $lease = $apartment->leases->last();
                if (!empty($lease)) {
                    $accessories = $lease->accessories;
                    if (!empty($accessories)) {
                        $accessories = $accessories->loadMissing('teamSettings');
                        foreach ($accessories as $key => $accessory) {
                            //dd($accessory->pivot);
                            $teamSettings = $accessory->teamSettings->first();
                            $data = ['type'=> $teamSettings->id, 'id'=>$accessory->id, 'price'=>$accessory->pivot->price, 'description'=>$accessory->pivot->description];
                            if (strcmp($teamSettings->type, 'appliances')==0) {
                                $this->appliances[] = $data;
                                $this->appliances_list[] = $teamSettings->accessories->where('active_lease', false)->pluck('model', 'id');
                                //dd($this->appliances);
                            } else {
                                $this->furnitures[] = $data;
                                $this->furnitures_list[] = $teamSettings->accessories->where('active_lease', false)->pluck('model', 'id');
                            }
                        }
                    }
                    //dd($teamSettings->accessories->pluck('model', 'id'));
                }
            }
        } else {
            $this->reset(['appliances', 'furnitures', 'appliances_list', 'furnitures_list']);
        }
    }

    /**
     * Updated Lease Term
     *
     * @return void
     */
    public function updatedLeaseTerm()
    {
        if (strcmp($this->lease_term, 'indeterminate')==0) {
            $this->reset('lease_end');
        }
    }

    /**
     * Updated Cost Born By
     *
     * @param mixed $value Value
     * @param mixed $key   Key
     *
     * @return void
     */
    public function updatedCostBornBy($value, $key)
    {
        $parts = explode(".", $key);
        if (strcmp($parts[1], 'lessor')==0) {
            $this->cost_born_by[$parts[0]]['lessee'] ='';
        } else {
            $this->cost_born_by[$parts[0]]['lessor'] ='';
        }
        //dd($parts[1]);
    }

    /**
     * Updated Land Access
     *
     * @return void
     */
    public function updatedLandAccess()
    {
        if (!$this->land_access) {
            $this->land_access_description='';
        }
    }

    /**
     * Updated Animals
     *
     * @return void
     */
    public function updatedAnimals()
    {
        if (!$this->animals) {
            $this->animals_description='';
        }
    }

    /**
     * Total costs
     *
     * @return float
     */
    public function totalCosts():float
    {
        $total_costs = 0.00;
        $dependencies = collect($this->dependencies);
        $furnitures = collect($this->furnitures);
        $appliances = collect($this->appliances);

        //dd($dependencies);
        $total_costs += $dependencies->sum('price');
        $total_costs += $furnitures->sum('price');
        $total_costs += $appliances->sum('price');

        return $total_costs;
    }

    /**
     * Back step
     *
     * @return previous step
     */
    public function myPreviousStep()
    {
        if ($this->currentStep >1) {
            $this->currentStep -= 1;
        }
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
            //$this->validate($this->rules2());
            //dd($this->addresses);
        }

        if ($this->currentStep == 2) {
            $this->validate($this->rules2());
            $this->total_cost_services = $this->totalCosts();
            //dd($this->dependencies_building);
        }

        if ($this->currentStep == 3) {
            $this->validate($this->rules3());
            //dd($this->consumption_costs);
            //dd($this->company->buildings->where('id', $this->building)->first()->address);
            //$this->my_role = $this->roles->where('id', $this->contract_role_id)->first()->display_name;
            //dd($this->cost_born_by);
        }


        $this->currentStep += 1;
    }



    /**
     * Rules
     *
     * @return void
     */
    public function rules()
    {
        return [
            'lessees.*.name'      => ['required', 'string', 'min:3', 'max:255'],
            'lessees.*.birthdate' => ['required', 'date', 'before:now -18 years'],
            'lessees.*.gender'    => ['required', 'in:male,female,other'],
            'lessees.*.email'     => ['required', 'email:rfc,dns', 'unique:users,email'],
            'lessees.*.mobile'    => ['required', 'string', 'min:7', 'max:18'],
            'addresses.*.suite'   => ['nullable','alpha_num','min:1','max:191'],
            'addresses.*.number'  => ['required_if:same_address,true','required_with:addresses.*.suite', 'required_with:addresses.*.street','nullable', 'numeric'],
            'addresses.*.street'  => ['required_if:same_address,true','required_with:addresses.*.number','required_with:addresses.*.city','nullable', 'string', 'min:2', 'max:32'],
            'addresses.*.city'    => ['required_if:same_address,true','required_with:addresses.*.street','required_with:addresses.*.country', 'nullable', 'string', 'min:2', 'max:32'],
            'addresses.*.region'  => ['nullable','string','min:2','max:32'],
            'addresses.*.country' => ['required_if:same_address,true','required_with:addresses.*.street', 'required_with:addresses.*.city', 'nullable', 'string', 'min:3', 'max:32'],
            'addresses.*.postcode'=> ['nullable','alpha_num','min:2','max:191'],
            'building'            => ['required', 'exists:buildings,id'],
            'apartment'           => ['required', 'exists:apartments,id'],
            'family_residence'    => ['boolean'],
            'family_residence_description' => ['required_if:family_residence,false', 'nullable', 'string', 'min:3', 'max:32'],
            'dwelling_co_ownership'        => ['boolean'],
            'furniture_included'           => ['boolean'],
            'dependencies.*.type'           => ['sometimes', 'required_with:building'],
            'dependencies.*.number'         => ['sometimes', 'required_with:dependencies.*.type', "distinct"],
            'dependencies.*.price'          => ['sometimes', 'numeric'],
            'dependencies.*.description'    => ['sometimes', 'nullable', 'string', 'min:3', 'max:32'],
            'appliances.*.type'             => ['sometimes', 'required_if:furniture_included,true'],
            'appliances.*.id'               => ['sometimes', 'required_with:appliances.*.type', 'distinct'],
            'appliances.*.price'            => ['sometimes', 'numeric'],
            'appliances.*.description'      => ['sometimes', 'nullable', 'string', 'min:3', 'max:32'],
            'furnitures.*.type'             => ['sometimes', 'required_if:furniture_included,true'],
            'furnitures.*.id'               => ['sometimes', 'required_with:furnitures.*.type', 'distinct'],
            'furnitures.*.price'            => ['sometimes', 'numeric'],
            'furnitures.*.description'      => ['sometimes', 'nullable', 'string', 'min:3', 'max:32'],
            'lease_term'                    => ['required'],
            'lease_start'                   => ['required', 'date', 'after_or_equal:today'],
            'lease_end'                     => ['required_if:lease_term,fixed','nullable','date', 'after:lease_start'],
            'rent_amount'                   => ['required', 'numeric'],
            'rent_recurrence'               => ['required'],
            'first_payment_at'              => ['required', 'date', 'before_or_equal:lease_start'],
            'payment_method'                => ['required'],
            'subsidy_program'               => ['boolean'],
            'postdated_cheques'             => ['boolean'],
            'by_laws'                       => ['boolean'],
            'by_laws_given_at'              => ['required_if:by_laws,true','nullable','date','before_or_equal:lease_start'],
            'repairs_before'                => ['required'],
            'repair_during'                 => ['required'],
            'cost_born_by.*.lessor'         => ['required_if:cost_born_by.*.lessee,""'],
            'cost_born_by.*.lessee'         => ['required_if:cost_born_by.*.lessor,""'],
            'land_access'                   => ['boolean'],
            'land_access_description'       => ['required_if:land_access,false', 'nullable', 'string', 'min:3', 'max:32'],
            'animals'                       => ['boolean'],
            'animals_description'           => ['required_if:animals,true', 'nullable', 'string', 'min:3', 'max:32'],
            'other_conditions_restrictions' => ['nullable', 'string', 'min:3', 'max:512'],

        ];
    }

    protected $validationAttributes = [

        'lessees.*.name' => 'lessee name',
        'lessees.*.birthdate' => 'lessee birthdate',
        'lessees.*.gender' => 'lessee gender',
        'lessees.*.email' => 'lessee email',
        'lessees.*.mobile' => 'lessee mobile',
        'addresses.*.suite' => 'suite',
        'addresses.*.number'=>'number',
        'addresses.*.street'=>'steet',
        'addresses.*.city'=>'city',
        'addresses.*.region'=>'region',
        'addresses.*.country'=>'country',
        'addresses.*.postcode'=>'post code/zip',
        'dependencies.*.type'=> 'dependency type',
        'dependencies.*.number'=>'dependency number',
        'dependencies.*.description' => 'description',
        'appliances.*.type' => 'type of appliance',
        'appliances.*.id' => 'appliance model',
        'appliances.*.description' => 'description',
        'furnitures.*.type'=>' type of furniture',
        'furnitures.*.id'=>'furniture model',
        'furnitures.*.description' => 'description',
        'cost_born_by.*.lessor' => 'cost born by lessor',
        'cost_born_by.*.lessee' => 'cost born by lessee',
    ];

    protected $messages = [

        'lessees.*.birthdate.before' => 'The lessee must have at least 18 years old.',

    ];

    /**
     * Rules1 - Lessees and address valivation
     *
     * @return array
     */
    public function rules1():array
    {
        return
            [
                'lessees.*.name' => ['required', 'string', 'min:3', 'max:255'],
                'lessees.*.birthdate' => ['required', 'date', 'before:now -18 years'],
                'lessees.*.gender' => ['required', 'in:male,female,other'],
                'lessees.*.email' => ['required', 'email:rfc,dns', 'unique:users,email'],
                'lessees.*.mobile' => ['required', 'string', 'min:7', 'max:18'],
                'addresses.*.suite'   => ['nullable','alpha_num','min:1','max:191'],
                'addresses.*.number'  => ['required_if:same_address,true', 'required_with:addresses.*.suite', 'required_with:addresses.*.street','nullable', 'numeric'],
                'addresses.*.street'  => ['required_if:same_address,true', 'required_with:addresses.*.number','required_with:addresses.*.city','nullable', 'string', 'min:2', 'max:32'],
                'addresses.*.city'    => ['required_if:same_address,true', 'required_with:addresses.*.street','required_with:addresses.*.country', 'nullable', 'string', 'min:2', 'max:32'],
                'addresses.*.region'  => ['nullable', 'string', 'min:2', 'max:32'],
                'addresses.*.country' => ['required_if:same_address,true', 'required_with:addresses.*.street', 'required_with:addresses.*.city', 'nullable', 'string', 'min:3', 'max:32'],
                'addresses.*.postcode'=> ['nullable', 'alpha_num', 'min:2', 'max:191']
            ]
        ;
    }

    /**
     * Rules2 - Dweeling validation
     *
     * @return array
     */
    public function rules2():array
    {
        return [
            'building'                      => ['required', 'exists:buildings,id'],
            'apartment'                     => ['required', 'exists:apartments,id'],
            'family_residence'              => ['boolean'],
            'family_residence_description'  => ['required_if:family_residence,false', 'nullable', 'string', 'min:3', 'max:32'],
            'dwelling_co_ownership'         => ['boolean'],
            'furniture_included'            => ['boolean'],
            'dependencies.*.type'           => ['sometimes', 'required_with:building'],
            'dependencies.*.number'         => ['sometimes', 'required_with:dependencies.*.type', "distinct"],
            'dependencies.*.price'          => ['sometimes', 'numeric'],
            'dependencies.*.description'    => ['sometimes', 'nullable', 'string', 'min:3', 'max:32'],
            'appliances.*.type'             => ['sometimes', 'required_if:furniture_included,true'],
            'appliances.*.id'               => ['sometimes', 'required_with:appliances.*.type', 'distinct'],
            'appliances.*.price'            => ['sometimes', 'numeric'],
            'appliances.*.description'      => ['sometimes', 'nullable', 'string', 'min:3', 'max:32'],
            'furnitures.*.type'             => ['sometimes', 'required_if:furniture_included,true'],
            'furnitures.*.id'               => ['sometimes', 'required_with:furnitures.*.type', 'distinct'],
            'furnitures.*.price'            => ['sometimes', 'numeric'],
            'furnitures.*.description'      => ['sometimes', 'nullable', 'string', 'min:3', 'max:32'],
        ];
    }

    /**
     * Rules3
     *
     * @return array
     */
    public function rules3():array
    {
        return [
            'lease_term'                    => ['required'],
            'lease_start'                   => ['required', 'date', 'after_or_equal:today'],
            'lease_end'                     => ['required_if:lease_term,fixed','nullable','date', 'after:lease_start'],
            'rent_amount'                   => ['required', 'numeric'],
            'rent_recurrence'               => ['required'],
            'first_payment_at'              => ['required', 'date', 'before_or_equal:lease_start'],
            'payment_method'                => ['required'],
            'subsidy_program'               => ['boolean'],
            'postdated_cheques'             => ['boolean'],
            'by_laws'                       => ['boolean'],
            'by_laws_given_at'              => ['required_if:by_laws,true','nullable','date','before_or_equal:lease_start'],
            'repairs_before'                => ['required'],
            'repair_during'                 => ['required'],
            'cost_born_by.*.lessor'         => ['required_if:cost_born_by.*.lessee,""'],
            'cost_born_by.*.lessee'         => ['required_if:cost_born_by.*.lessor,""'],
            'land_access'                   => ['boolean'],
            'land_access_description'       => ['required_if:land_access,false', 'nullable', 'string', 'min:3', 'max:32'],
            'animals'                       => ['boolean'],
            'animals_description'           => ['required_if:animals,true', 'nullable', 'string', 'min:3', 'max:32'],
            'other_conditions_restrictions' => ['nullable', 'string', 'min:3', 'max:512'],
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
     * Add Dependency
     *
     * @return void
     */
    public function addDependency()
    {
        $this->dependencies[] = ['type'=>null, 'number'=>null, 'price'=>0.00, 'description'=>''];
        $this->dependencies_building[] = [];
    }

    /**
     * Remove dependency
     *
     * @param mixed $index Index
     *
     * @return void
     */
    public function removeDependency($index)
    {
        unset($this->dependencies[$index]);
        unset($this->dependencies_building[$index]);
        $this->dependencies = array_values($this->dependencies);
        $this->dependencies_building = array_values($this->dependencies_building);
        $this->resetErrorBag('dependencies.*.number');
        //reset error bag

    }

    /**
     * Add Furniture
     *
     * @return void
     */
    public function addFurniture()
    {
        $this->furnitures[]=['type'=>null, 'id'=>null, 'price'=>0.00, 'description'=>''];
        $this->furnitures_list[] = [];
    }

    /**
     * Remove Furniture
     *
     * @param mixed $index Index
     *
     * @return void
     */
    public function removeFurniture($index)
    {
        unset($this->furnitures[$index]);
        unset($this->furnitures_list[$index]);
        $this->furnitures = array_values($this->furnitures);
        $this->furnitures_list = array_values($this->furnitures_list);
        $this->resetErrorBag('furnitures.*.id');
    }

    /**
     * Add Appliance
     *
     * @return void
     */
    public function addAppliance()
    {
        $this->appliances[]=['type'=>null, 'id'=>null, 'price'=>0.00, 'description'=>null];
        $this->appliances_list[] = [];
    }

    /**
     * Remove Appliance
     *
     * @param mixed $index Index
     *
     * @return void
     */
    public function removeAppliance($index)
    {
        unset($this->appliances[$index]);
        unset($this->appliances_list[$index]);
        $this->appliances = array_values($this->appliances);
        $this->appliances_list = array_values($this->appliances_list);
        $this->resetErrorBag('appliances.*.id');
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
                'lessees',
                'lease_term',
                'lease_start',
                'lease_end',
                'family_residence_description',
                'addresses',
                'countries',
                'country_cities',
                'building',
                'apartments',
                'apartment',
                'apartment_heating',
                'dependencies',
                'dependencies_building',
                'furnitures',
                'furnitures_list',
                'appliances',
                'appliances_list',
                'rent_recurrence',
                'first_payment_at',
                'payment_method',
                'repairs_before',
                'repair_during',
                'by_laws_given_at',
                'cost_born_by',
                'land_access_description',
                'animals_description',
                'other_conditions_restrictions',
            ]
        );

        $this->currentStep=1;
        $this->second_lessee = false;
        $this->same_address=false;
        $this->family_residence = true;
        $this->dwelling_co_ownership = false;
        $this->subsidy_program = false;
        $this->furniture_included = false;
        $this->rent_amount=0.00;
        $this->postdated_cheques=false;
        $this->total_cost_services=0.00;
        $this->land_access = true;
        $this->animals=false;
        $this->buildConsumptionArray();
    }

    /**
     * Submit Form
     *
     * @return void
     */
    public function submitForm()
    {
        //dd($this->cost_born_by);
        $this->validate();

        DB::beginTransaction();
        try {

            $users = [];
            $passwd = [];
            $role = Role::where('name', 'tenant')->first();

            //Create User with address and contact.
            //Assign role tenant to the created user
            foreach ($this->lessees as $key => $lessee) {
                $passwd[] = $this->generatePassword();
                $users[] = User::create(
                    [
                        'name'     => $lessee['name'],
                        'email'    => $lessee['email'],
                        'birthdate'=> $lessee['birthdate'],
                        'gender'   => $lessee['gender'],
                        'password' => bcrypt($passwd[$key]),
                    ]
                );

                if ($this->same_address) {
                    $address = $this->addresses[0];
                } else {
                    $address = $this->addresses[$key];
                }
                $address['type']="primary";

                $users[$key]->addresses()->create($address);

                $contacts = [
                    [
                        'priority'=>'main',
                        'type'=>'mobile',
                        'description'=>$lessee['mobile']
                    ],
                    [
                        'priority'=>'main',
                        'type'=>'email',
                        'description'=>$lessee['email']
                    ]
                ];

                $users[$key]->contacts()->createMany($contacts);

                $users[$key]->attachRole($role, $this->company);
            }

            //create a new lease
            $lease = Lease::create(
                [
                    'apartment_id'                    => $this->apartment,
                    'residential_purpose'             => $this->family_residence,
                    'residential_purpose_description' => $this->family_residence_description,
                    'co_ownership'                    => $this->dwelling_co_ownership,
                    'furniture_included'              => $this->furniture_included,
                    'term'                            => $this->lease_term,
                    'start_at'                        => $this->lease_start,
                    'end_at'                          => $this->lease_end,
                    'rent_amount'                     => $this->rent_amount,
                    'rent_recurrence'                 => $this->rent_recurrence,
                    'subsidy_program'                 => $this->subsidy_program,
                    'first_payment_at'                => $this->first_payment_at,
                    'postdated_cheques'               => $this->postdated_cheques,
                    'by_law_given_on'                 => $this->by_laws_given_at,
                    'land_access'                     => $this->land_access,
                    'land_access_description'         => $this->land_access_description,
                    'animals'                         => $this->animals,
                    'animals_description'             => $this->animals_description,
                    'others'                          => $this->other_conditions_restrictions,
                ]
            );
            //attach lessors (users) to lease
            $lease->code = 'BL'.$lease->start_at->format('mY').$lease->id.$this->company->id;
            $lease->save();
            foreach ($users as $user) {
                $check_account = $user->checkAccounts()->create(['team_id'=>$this->company->id]);
                $lease->users()->attach($user, ['team_id'=>$this->company->id, 'check_account_id'=>$check_account->id]);//Attache users to lease

            }

            //attach dependecies to the lease
            foreach ($this->dependencies as $dependencie) {
                $lease->dependencies()->attach(
                    $dependencie['number'],
                    [
                        'assigned_at'=>now(),//To be commented
                        'price'=>$dependencie['price'],
                        'description'=>$dependencie['description']
                    ]
                );
            }

            //attach furnitures (accessories) to the lease
            foreach ($this->furnitures as $furniture) {
                $lease->accessories()->attach(
                    $furniture['id'],
                    [
                        'assigned_at'=>now(),//To be commented
                        'price'=>$furniture['price'],
                        'description'=>$furniture['description']
                    ]
                );
            }

            //attach appliances (accessories) to the lease
            foreach ($this->appliances as $appliance) {
                $lease->accessories()->attach(
                    $appliance['id'],
                    [
                        'assigned_at'=>now(),//comment
                        'price'=>$appliance['price'],
                        'description'=>$appliance['description']
                    ]
                );
            }

            //Attach Payment Methode to the lease
            $lease->teamSettings()->attach($this->payment_method);

            //works and repairs
            //Before the delivery of the dwelling
            $lease->teamSettings()->attach($this->repairs_before, ['description'=>"before the delivery of the dwelling"]);
            //During the lease
            $lease->teamSettings()->attach($this->repair_during, ['description'=>"during the lease"]);

            //Services and consumption costs
            foreach ($this->cost_born_by as $key => $cost_born) {
                if (!empty($cost_born['lessor'])) {
                    $responsability = "lessor";
                    $service_id = $cost_born['lessor'];
                } else {
                    $responsability = "lessee";
                    $service_id = $cost_born['lessee'];
                }
                $lease->teamSettings()->attach($service_id, ['description'=>$responsability]);
            }

            DB::commit();

            $this->resetInputFields();

            $message_type = "success";
            $message = "Lease created successfully";

            //Send lease notifications
            LeaseCreated::dispatch($lease);

        } catch (\Throwable $th) {
            DB::rollBack();
            $message_type = "error";
            $message = $th->getMessage();
        }

        $this->dispatchBrowserEvent(
            'swalLease:form',
            [
                'icon'=>$message_type,
                'title' => $message,
                'text' => '',
            ]
        );

    }

    /**
     * Generate Password
     *
     * @return string
     */
    public function generatePassword()
    {
        $lowercase = range('a', 'z');
        $uppercase = range('A', 'Z');
        $digits = range(0, 9);
        $special = ['!', '@', '#', '$', '^', '*'];
        $chars = array_merge($lowercase, $uppercase, $digits, $special);
        $length = env('PASSWORD_LENGTH', 8);

        do {
            $password = array();

            for ($i = 0; $i<$length; $i++) {
                $int = rand(0, count($chars)-1);
                array_push($password, $chars[$int]);

            }
        } while (empty(array_intersect($special, $password)));

        $f_password = implode('', $password);

        return $f_password;
    }
}

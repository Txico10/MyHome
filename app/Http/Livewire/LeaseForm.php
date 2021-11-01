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

use Livewire\Component;
use PragmaRX\Countries\Package\Countries;
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
    public $countries;
    public $country_cities;
    public $currentStep=1;
    //lessee
    public $lessee_name;
    public $lessee_birthdate;
    public $lessee_gender;
    public $lessee_email;
    public $lessee_mobile;
    //lease
    public $lease_term;
    public $lease_start;
    public $lease_end;
    public $family_residence=false;
    //address
    public $address_type="primary";
    public $address_suite;
    public $address_number;
    public $address_street;
    public $address_city;
    public $address_region;
    public $address_country;
    public $address_postcode;

    /**
     * Mount
     *
     * @param mixed $company Company
     *
     * @return void
     */
    public function mount($company)
    {
        $this->company=$company;
        $this->countries = Countries::all()->pluck('name.common', 'cca3')
            ->toArray();
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
        /*
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
        */

        $this->currentStep += 1;
    }

    /**
     * Submit Form
     *
     * @return void
     */
    public function submitForm()
    {
        // code...
    }
}

<?php
/**
 * Address Form Livewire Component
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

use App\Models\Address;
use App\Rules\AddressTypeRule;
use Illuminate\Validation\Rule;
use PragmaRX\Countries\Package\Countries;
use Livewire\Component;

use function PHPSTORM_META\type;

/**
 *  Address Form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AddressesForm extends Component
{
    public $model;
    public $address_id;
    public $address_type;
    public $address_suite;
    public $address_number;
    public $address_street;
    public $address_city;
    public $address_region;
    public $address_country;
    public $address_postcode;
    public $all_countries;
    public $country_cities;

    protected $listeners = [
        'createAddress' => 'create',
        'editAddress'=> 'edit',
        'saveAddressForm' => 'store',
        'resetAdressInputFiels' => 'resetInputFields',
        'deleteAddressConfirm' => 'confirmDelete',
        'deleteAddress'=>'destroy',
    ];

    /**
     * Mount
     *
     * @param mixed $model New Model
     *
     * @return void
     */
    public function mount($model)
    {
        $this->model = $model;
        $this->all_countries = Countries::all()
            ->pluck('name.common', 'cca3')
            ->toArray();
        //dd($this->model->addresses->contains('type', 'other'));
    }

    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.addresses-form', ['all_countries'=> $this->all_countries]);
    }

    /**
     * Livetime validation
     *
     * @return validation rules
     */
    public function rules()
    {

        $address_type_rules = [
            'required',
            Rule::in(['primary', 'secondary', 'other']),
            //new AddressTypeRule(['primary', 'secondary'])
        ];

        if (is_null($this->address_id) || strcmp($this->address_type, $this->model->addresses->where('id', $this->address_id)->first()->type)!=0) {
            $address_type_rules[] = new AddressTypeRule($this->model->addresses->pluck('type')->toArray());
        }

        return [
            'address_type' => $address_type_rules,
            'address_suite' => 'nullable|alpha_num|min:1|max:191',
            'address_number' => [
                Rule::requiredIf(!empty($this->address_suite) || !empty($this->address_street)),
                'nullable','numeric'
            ],
            'address_street' => [
                Rule::requiredIf(!empty($this->address_number) || !empty($this->address_city)),
                'nullable','string','min:2','max:32'
            ],
            'address_city' => [
                Rule::requiredIf(!empty($this->address_street) || !empty($this->address_country)),
                'nullable','string','min:2','max:32'
            ],
            'address_region' => 'nullable|string|min:2|max:32',
            'address_postcode' => 'nullable|alpha_num|min:2|max:191',
            'address_country' => [
                Rule::requiredIf(!empty($this->address_street) || !empty($this->address_city)),
                'nullable','string','min:3','max:32'
            ],
        ];
    }

    /**
     * Real time validation
     *
     * @param $propertyName to validate specific field
     *
     * @return void
     */
    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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

        $this->validate(
            [
                'address_country' => 'required|string|min:2|max:32'
            ]
        );
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
     * Create
     *
     * @return void
     */
    public function create()
    {
        $this->resetInputFields();
        $this->dispatchBrowserEvent('openAddressModal');
    }
    /**
     * Edit
     *
     * @param mixed $address Address
     *
     * @return void
     */
    public function edit($address)
    {
        $this->resetInputFields();
        $this->address_id = $address['id'];
        $this->address_type = $address['type'];
        $this->address_suite = $address['suite'];
        $this->address_number = $address['number'];
        $this->address_street = $address['street'];
        $this->address_city = $address['city'];
        $this->address_region = $address['region'];
        $this->address_country = $address['country'];
        $this->address_postcode = $address['postcode'];
        if (!empty($this->address_country)) {
            $this->country_cities =  Countries::where('cca3', $this->address_country)->first()
                ->hydrate('cities')
                ->cities
                ->pluck('name', 'nameascii')
                ->toArray();
        }
        $this->dispatchBrowserEvent('openAddressModal');
    }

    /**
     * Store
     *
     * @return void
     */
    public function store()
    {
        $this->validate();

        $this->model->addresses()->updateOrCreate(
            ['id'=> $this->address_id],
            [
                'type'=>$this->address_type,
                'suite'=>$this->address_suite,
                'number'=>$this->address_number,
                'street'=>$this->address_street,
                'city'=>$this->address_city,
                'region'=>$this->address_region,
                'country'=>$this->address_country,
                'postcode'=>$this->address_postcode
            ]
        );

        $this->emitUp('refreshAddresses');
        $this->dispatchBrowserEvent('closeAddressModal');
        $this->dispatchBrowserEvent(
            'swal:modal',
            [
                'icon'=>'success',
                'title' => 'Address created successfully',
                'text' => '',
            ]
        );
        /*
        $this->dispatchBrowserEvent(
            'alert',
            [
                'type'=>'success',
                'message'=> 'Address stored successfully!!!',
            ]
        );
        */
    }

    /**
     * Confirm delete
     *
     * @param mixed $id ID
     *
     * @return void
     */
    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent(
            'swal:confirm',
            [
                'icon'  => 'warning',
                'title' => 'Are you sure?',
                'text' => 'The address will be deleted',
                'id' => $id,
            ]
        );
    }
    /**
     * Destroy Address
     *
     * @param mixed $id Address
     *
     * @return void
     */
    public function destroy($id)
    {
        $address = $this->model->addresses->where('id', $id)->first();
        $address->delete();
        $this->emitUp('refreshAddresses');
        $this->dispatchBrowserEvent(
            'swal:modal',
            [
                'icon'=>'success',
                'title' => 'Address deleted successfully',
                'text' => '',
            ]
        );
    }

    /**
     * Reset form fields
     *
     * @return void
     */
    public function resetInputFields()
    {
        $this->reset(
            [
                'address_id',
                'address_type',
                'address_suite',
                'address_number',
                'address_street',
                'address_city',
                'address_region',
                'address_country',
                'address_postcode',
                'country_cities',
            ]
        );
        $this->resetErrorBag();
    }
}

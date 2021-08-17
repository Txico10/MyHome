<?php
/**
 * Contacts form Livewire Component
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

use Illuminate\Validation\Rule;
use Livewire\Component;
/**
 *  Contact form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ContactsForm extends Component
{
    public $model;
    public $contact_id;
    public $contact_priority;
    public $contact_type;
    public $contact_description;
    public $contact_name;

    protected $listeners = [
        'createContact' => 'create',
        'editContact'=> 'edit',
        'saveContactForm' => 'store',
        'resetContactInputFiels' => 'resetInputFields',
        'deleteContactConfirm' => 'confirmDelete',
        'deleteContact'=>'destroy',
    ];

    /**
     * Mount
     *
     * @param mixed $model Model
     *
     * @return void
     */
    public function mount($model)
    {
        $this->model = $model;
    }

    /**
     * Render
     *
     * @return View
     */
    public function render()
    {
        return view('livewire.contacts-form');
    }

    /**
     * Rules
     *
     * @return void
     */
    public function rules()
    {
        $contact_type_rule = ['required'];
        if (!is_null($this->contact_type)) {
            if (strcmp($this->contact_type, 'email')==0) {
                $contact_type_rule [] = 'email:rfc,dns';
            } elseif (strcmp($this->contact_type, 'phone')==0 || strcmp($this->contact_type, 'mobile')==0) {
                $contact_type_rule [] = 'min:10';
                $contact_type_rule [] = 'regex:/^([0-9\s\-\+\(\)]*)$/';
            } else {
                $contact_type_rule [] = 'min:5';
                $contact_type_rule [] = 'string';
            }
        }
        //dd($contact_type_rule);
        return [
            'contact_priority' => [
                'required',
                Rule::in(['main', 'emergency', 'other'])
            ],
            'contact_type' => [
                'required',
                Rule::in(['email', 'phone', 'mobile','other'])
            ],
            'contact_description' => $contact_type_rule,
            'contact_name' => [
                Rule::requiredIf(
                    !is_null($this->contact_priority) && strcmp($this->contact_priority, 'emergency')==0
                ),
                'nullable',
                'min:3',
                'string'
            ]
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
     * Updated Contact Type
     *
     * @return void
     */
    public function updatedContactType()
    {
        if (!is_null($this->contact_description)) {
            $this->validateOnly($this->contact_description);
        }

    }

    /**
     * Create Contact
     *
     * @return void
     */
    public function create()
    {
        $this->resetInputFields();
        $this->dispatchBrowserEvent('openContactModal');
    }

    /**
     * Edit Contact
     *
     * @param mixed $contact Contact
     *
     * @return void
     */
    public function edit($contact)
    {
        $this->resetInputFields();
        $this->contact_id = $contact['id'];
        $this->contact_priority=$contact['priority'];
        $this->contact_type=$contact['type'];
        $this->contact_description=$contact['description'];
        $this->contact_name=$contact['name'];
        $this->dispatchBrowserEvent('openContactModal');
    }

    /**
     * Store Contact
     *
     * @return void
     */
    public function store()
    {
        $this->validate();
        $this->model->contacts()->updateOrCreate(
            ['id'=> $this->contact_id],
            [
                'priority'=> $this->contact_priority,
                'type' => $this->contact_type,
                'description' => $this->contact_description,
                'name' => $this->contact_name,
            ]
        );
        $this->emitUp('refreshContacts');
        $this->dispatchBrowserEvent('closeContactModal');
        $this->dispatchBrowserEvent(
            'swalContact:modal',
            [
                'icon'=>'success',
                'title' => 'Contact saved successfully',
                'text' => '',
            ]
        );
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
                'contact_id',
                'contact_priority',
                'contact_type',
                'contact_description',
                'contact_name',
            ]
        );
        $this->resetErrorBag();
    }

    /**
     * Confirm delete
     *
     * @param mixed $id Contact id
     *
     * @return void
     */
    public function confirmDelete($id)
    {
        $this->dispatchBrowserEvent(
            'swalContact:confirm',
            [
                'icon'  => 'warning',
                'title' => 'Are you sure?',
                'text' => 'The contact will be deleted',
                'id' => $id,
            ]
        );
    }

    /**
     * Destroy contact
     *
     * @param mixed $id Contact id
     *
     * @return void
     */
    public function destroy($id)
    {
        $contact = $this->model->contacts->where('id', $id)->first();
        $contact->delete();
        $this->emitUp('refreshContacts');
        $this->dispatchBrowserEvent(
            'swalContact:modal',
            [
                'icon'=>'success',
                'title' => 'Contact deleted successfully',
                'text' => '',
            ]
        );
    }
}

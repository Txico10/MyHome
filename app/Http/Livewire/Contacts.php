<?php
/**
 * Contacts Livewire Component
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
/**
 *  Contacts component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Contacts extends Component
{
    public $contacts;

    protected $listeners = [
        'refreshContacts'=> '$refresh',
    ];

    /**
     * Mount
     *
     * @param mixed $contacts Contacts
     *
     * @return void
     */
    public function mount($contacts)
    {
        $this->contacts = $contacts;
    }
    /**
     * Render
     *
     * @return view
     */
    public function render()
    {
        return view('livewire.contacts', ['contacts'=>$this->contacts]);
    }
}

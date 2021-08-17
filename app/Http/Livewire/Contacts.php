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
    public $model;

    protected $listeners = [
        'refreshContacts'=> '$refresh',
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
     * @return view
     */
    public function render()
    {
        return view('livewire.contacts', ['contacts'=>$this->model->contacts]);
    }
}

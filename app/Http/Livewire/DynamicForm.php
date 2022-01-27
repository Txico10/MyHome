<?php
/**
 * Dynamic form Controller
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

use App\Models\User;
use Livewire\Component;
/**
 *  Dynamic form class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class DynamicForm extends Component
{
    public $users=[];
    public $saved = false;


    /**
     * Mount
     *
     * @return void
     */
    public function mount()
    {
        //array_push($this->users, arra new User->toArray());
        //$this->users[] = new User;
        $this->users[0]= ['name'=>'', 'email' => ''];
    }

    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.dynamic-form');
    }

    /**
     * AddUser
     *
     * @return void
     */
    public function addUser()
    {
        $this->users[] = ['name'=>'', 'email'=>''];
        //$index = count($this->users);
        //$this->users[$index-1]->name = "";
        //$this->users[$index-1]->email = "";
        //if (count($this->users)>2) {
        //    dd($this->users);
        //}

    }

    /**
     * RemoveUser
     *
     * @param mixed $index Index
     *
     * @return void
     */
    public function removeUser($index)
    {
        unset($this->users[$index]);
        $this->users = array_values($this->users);
    }

    /**
     * Updated
     *
     * @param mixed $key   Key
     * @param mixed $value Value
     *
     * @return void
     */
    public function updated($key, $value)
    {
        //$this->saved = false;
        //$parts = explode(".", $key);

        $this->validate(
            [
                'users.*.name'=>'required|min:5',
            ],
            [
                'users.*.name.required'=>'The name is required',
                'users.*.name.min'=>'At least 5 char',
            ]
        );
    }
}

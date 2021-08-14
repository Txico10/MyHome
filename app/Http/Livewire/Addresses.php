<?php
/**
 * Addresses Livewire Component
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
 *  Addresses component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Addresses extends Component
{
    public $model;

    protected $listeners = [
        'refreshAddresses'=> '$refresh',
    ];

    /**
     * Mount
     *
     * @param mixed $model Adresses
     *
     * @return void
     */
    public function mount($model)
    {
        $this->model = $model;
        //dd($model);
    }

    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view(
            'livewire.addresses',
            [
                'model'=>$this->model,
            ]
        );
    }
}

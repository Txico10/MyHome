<?php
/**
 * Edit Bill form Livewire Component
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

use App\Models\Bill;
use App\Models\Team;
use Livewire\Component;
/**
 *  Edit bill-invoice form component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BillForm extends Component
{
    public $company;
    public $bill;


    /**
     * Mount
     *
     * @param Team $company Company
     * @param Bill $bill    Bill
     *
     * @return void
     */
    public function mount(Team $company, Bill $bill)
    {
        $this->company = $company;
        $this->bill    = $bill;
    }

    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        return view('livewire.bill-form');
    }
}

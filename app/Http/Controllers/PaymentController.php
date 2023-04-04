<?php
/**
 * Payment Controller
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentRequest;
use App\Models\Bill;
use App\Models\Payment;
use App\Models\Team;
use Illuminate\Http\Request;
/**
 *  Payment Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class PaymentController extends Controller
{
    /**
     * Store
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Illuminate\Http\Response
     */
    public function store(StorePaymentRequest $request, Team $company)
    {
        if ($request->ajax()) {

            $payment_data = [
                    'bill_id'=>$request->bill_id,
                    'email'=>$request->payer_email,
                    'amount'=>$request->amount,
                    'method'=>$request->method,
                    'method_number'=>$request->method_num,
                    'payed_at'=>$request->at
            ];

            Payment::create($payment_data);

            $bill = Bill::find($payment_data['bill_id']);


            $bill->status = "payed";
            $bill->save();

            return response()->json(['message'=>'Lease payed successfully!']);
        }
        return null;
    }
}

<?php
/**
 * Store Payment Request validation
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *  Payment Form Request class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class StorePaymentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'bill_id'    =>['required','numeric', 'exists:bills,id'],
            'payer_email'=>['required', 'email:rfc', 'exists:users,email'],
            'amount'     =>['required', 'numeric'],
            'method'     =>['required', Rule::in(['credit', 'cheque', 'cash'])],
            'method_num' =>['required_unless:method,cash', 'nullable','numeric', 'min:1', 'max:9999999999999999'],
            'created_at' =>['required', 'date'],
            'at'         =>['required', 'date', 'after_or_equal:created_at']
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'at.after_or_equal' => 'The payment cant be donne before the bill as been created',
        ];
    }

}

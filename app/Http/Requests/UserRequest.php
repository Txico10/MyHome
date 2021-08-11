<?php
/**
 * User Request validation
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

use App\Models\User;
use App\Rules\Checkpassword;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *  User Form Request class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class UserRequest extends FormRequest
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
        $rules = User::VALIDATION_RULES;
        $rules['gender'][] = Rule::in(['male', 'female', 'other']);

        if ($this->getMethod() == 'POST') {
            $rules += ['photo' =>['nullable', 'image', 'mimes:png,jpg,jpeg,gif,svg',
            'max:2048']];
        } else {
            if (strcmp(request()->email, request()->route('user')->email)==0) {
                $rules['email'][2]='unique:users,email,'.request()->route('user')->id;
            }
            if (request()->ssn == request()->route('user')->ssn) {
                $rules['ssn'][2]='unique:users,ssn,'.request()->route('user')->id;
            }
            $rules['password'][]= new Checkpassword(request()->route('user')->password);
        }

        return $rules;
    }
}

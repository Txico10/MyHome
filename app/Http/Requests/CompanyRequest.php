<?php
/**
 * Company Request validation
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

use App\Models\Team;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 *  Company Form Request class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class CompanyRequest extends FormRequest
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
        $rules = Team::VALIDATION_RULES;

        $rules['legalform'][] = Rule::in(
            [
                'Sole proprietorship',
                'Business corporation',
                'General partnership',
                'Limited partnership',
                'Cooperative'
            ]
        );

        if ($this->getMethod() == 'POST') {
            $rules += ['logo' =>['nullable', 'image', 'mimes:png,jpg,jpeg,gif,svg',
            'max:2048']];
        } else {
            if (request()->bn == request()->route('company')->bn) {
                $rules['bn'][2] = 'unique:teams,bn,'.request()
                    ->route('company')->id;
            }

            if (strcmp(request()->slug, request()->route('company')->slug)==0) {
                $rules['slug'][2] = 'unique:teams,slug,'.request()
                    ->route('company')->id;
            }
        }

        return $rules;
    }
}

<?php
/**
 * Password check Rule
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Hash;

/**
 *  Checkpassword class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Checkpassword implements Rule
{
    /**
     * Hashed password to be validated
     *
     * @var $hashedpassword
     */
    public $hashedpassword;

    /**
     * Create a new rule instance.
     *
     * @param mixed $hashedPassword Password
     *
     * @return void
     */
    public function __construct($hashedPassword)
    {
        $this->hashedpassword = $hashedPassword;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute Attribute
     * @param mixed  $value     Value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if (Hash::check($value, $this->hashedpassword)) {
            return true;
        }
        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'The current password is not correct. Please try again..';
    }
}

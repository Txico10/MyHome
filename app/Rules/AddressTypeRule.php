<?php
/**
 * Address type check Rule
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
/**
 *  Address Type rule class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AddressTypeRule implements Rule
{
    /**
     * Existing address types
     *
     * @var array
     */
    public $address_types;

    /**
     * Create a new rule instance.
     *
     * @param array $address_types Existing Types already assigned
     *
     * @return void
     */
    public function __construct($address_types)
    {
        $this->address_types = $address_types;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute Attributs
     * @param mixed  $value     Value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($this->address_types as $address_type) {
            if (strcmp($address_type, $value)==0) {
                //dd($value);
                return false;
            }
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This address type has already been assigned.';
    }
}

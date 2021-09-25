<?php
/**
 * Address Factory
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace Database\Factories;

use App\Models\Address;
use PragmaRX\Countries\Package\Countries;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Address factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AddressFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Address::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $myCountry = "CAN";

        $countryCities = Countries::where('cca3', $myCountry)->first()
            ->hydrate('cities')
            ->cities
            ->pluck('nameascii')
            ->toArray();

        $myCity = $this->faker->randomElement($countryCities);

        $myRegion =  Countries::where('cca3', $myCountry)->first()
            ->hydrateCities()
            ->cities
            ->where('nameascii', $myCity)
            ->first()
            ->adm1name;

        return [
            'type'     => 'primary',
            'suite'    => $this->faker->secondaryAddress(),
            'number'   => $this->faker->buildingNumber(),
            'street'   => $this->faker->streetName(),
            'city'     => $myCity,
            'region'   => utf8_decode($myRegion),
            'country'  => $myCountry,
            'postcode' => $this->faker->postcode(),
        ];
    }
}

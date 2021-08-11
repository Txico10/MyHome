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
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Users class
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
        $type = $this->faker->randomElement(['primary', 'secondary']);
        return [
            'type'     => $type,
            'number'   => $this->faker->buildingNumber(),
            'street'   => $this->faker->streetName(),
            'city'     => $this->faker->city(),
            'region'   => $this->faker->state(),
            'country'  => $this->faker->country(),
            'postcode' => $this->faker->postcode(),
            'latitude' => $this->faker->latitude(),
            'longitude'=> $this->faker->longitude()
        ];
    }
}

<?php
/**
 * Accessory Factory
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

use App\Models\Accessory;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Accessory factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AccessoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Accessory::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $manufacturer = ['whirlpool', 'maytag', 'ge appliances', 'samsung', 'admiral'];

        return [
            'manufacturer'=>$this->faker->randomElement($manufacturer),
            'model'=>$this->faker->bothify('???####??#'),
            'serial'=>$this->faker->bothify('#??######?#'),
            'buy_at'=>$this->faker->dateTimeBetween('-15 years', 'now'),
        ];
    }
}

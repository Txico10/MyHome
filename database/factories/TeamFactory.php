<?php
/**
 * Team Factory
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

use App\Models\Team;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Team factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class TeamFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $legal_form = ['Sole proprietorship', 'Business corporation', 'General partnership', 'Limited partnership', 'Cooperative'];
        $name = $this->faker->company()." ".$this->faker->companySuffix();
        return [
            'slug'=>Str::of($name)->slug('-'),
            'display_name' => $name,
            'bn' => $this->faker->randomNumber(9, true),
            'legalform'=>$this->faker->randomElement($legal_form),
            'description'=> $this->faker->sentence(3),
            'logo' => 'defaultCompany.png'
        ];
    }
}

<?php
/**
 * Building Factory
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

use App\Models\Building;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Building factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BuildingFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Building::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $lot = "1";
        for ($i = 0 ; $i<2; $i++) {
            $lot1 = $this->faker->randomNumber(3, true);
            $lot = $lot." ".$lot1;
        }
        return [
            'lot'=>$lot,
            'display_name'=>$this->faker->word(),
            'ready_for_habitation'=>$this->faker->dateTimeBetween('-15 years', 'now'),
            'description'=>$this->faker->text(200),
        ];
    }
}

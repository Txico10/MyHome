<?php
/**
 * Employee contract Factory
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

use App\Models\EmployeeContract;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Employee contract factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeeContractFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = EmployeeContract::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $salary_tem = $this->faker->randomElement(['hourly', 'monthly', 'annual']);
        $min_week_time = null;
        $max_week_time = null;
        switch ($salary_tem) {
        case "hourly":
            $salary = $this->faker->randomFloat(2, 12, 15);
            $availability = $this->faker->randomElement(['full-time', 'partial-time']);
            break;
        case "monthly":
            $salary = $this->faker->randomFloat(2, 1900, 2560);
            $availability = "full-time";
            $min_week_time ="30:00:00";
            break;
        default:
            $salary = $this->faker->randomFloat(2, 30000, 40000);
            $availability = "full-time";
            $min_week_time ="30:00:00";
        }

        if (strcmp($availability, 'partial-time')==0) {
            $min_week_time = $this->faker->time();
            $max_week_time = "30:00:00";
        }

        return [
            'start_at'=> $this->faker->dateTimeBetween('-5 years', 'now'),
            'end_at'=>$this->faker->dateTimeBetween('+4 week', '+2 years'),
            'salary_term' => $salary_tem,
            'salary_amount' => $salary,
            'availability' => $availability,
            'min_week_time'=> $min_week_time,
            'max_week_time'=> $max_week_time,
        ];
    }
}

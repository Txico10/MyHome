<?php
/**
 * Lease Factory
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

use App\Models\Lease;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Lease factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class LeaseFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lease::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $term = $this->faker->randomElement(['fixed', 'indeterminate']);
        $start_date = $this->faker->dateTimeBetween('-5 years', 'now');
        $end_date = null;
        if (strcmp($term, 'fixed')==0) {
            $end_date = \Carbon\Carbon::parse($start_date)->addYear();
        }

        return [
            'residential_purpose'=>true,
            'furniture_included'=>true,
            'term'=>$term,
            'start_at' => $start_date,
            'end_at'=>$end_date,
            'rent_amount'=>$this->faker->randomFloat(2, 750, 1500),
            'rent_recurrence'=>'month',
            'first_payment_at'=>\Carbon\Carbon::parse($start_date)->startOfMonth(),
        ];
    }
}

<?php
/**
 * Contact Factory
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

use App\Models\Contact;
use Illuminate\Database\Eloquent\Factories\Factory;
/**
 *  Contact Factory class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ContactFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Contact::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $type = $this->faker->randomElement(['phone', 'mobile', 'email']);
        $priority = $this->faker->randomElement(['main', 'emergency', 'other']);
        $name = null;

        if (strcmp($type, 'email')) {
            $description = $this->faker->phoneNumber();
        } else {
            $description = $this->faker->unique()->safeEmail();
        }

        if (strcmp($priority, 'emergency')==0) {
            $name = $this->faker->name();
        }


        return [
            'priority'    => $priority,
            'type'        => $type,
            'description' => $description,
            'name'        => $name
        ];
    }
}

<?php
/**
 * User Seeder
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace Database\Seeders;

use App\Models\Address;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Database\Seeder;
/**
 *  User seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::factory()
            ->hasAddresses(2)
            ->hasContacts(3)
            ->create(
                [
                    'name'     => 'Stefan Monteiro',
                    'email'    => 'stefanmonteiro@gmail.com',
                    'gender'   => 'male',
                    'password' => bcrypt('stefan123'),
                ]
            );
        User::factory(3)
            ->hasAddresses(2)
            ->hasContacts(3)
            ->create();
    }
}

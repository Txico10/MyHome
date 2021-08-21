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
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
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
        activity()->withoutLogs(
            function () {
                $user = User::factory()
                    ->has(
                        Address::factory()
                            ->count(2)
                            ->state(
                                new Sequence(
                                    ['type'=>'primary'],
                                    ['type'=>'secondary']
                                )
                            )
                    )
                    ->hasContacts(3)
                    ->create(
                        [
                            'name'     => 'Stefan Monteiro',
                            'email'    => 'stefanmonteiro@gmail.com',
                            'gender'   => 'male',
                            'password' => bcrypt('stefan123'),
                        ]
                    );
                $user->attachRole(1);
            }
        );

    }
}

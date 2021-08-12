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

use App\Models\Role;
use App\Models\Team;
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
        $user = User::factory()
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


        $roles = Role::all();

        $user->attachRole($roles->first());

        $companies = Team::all();

        foreach ($companies as $company) {

            User::factory(5)
                ->hasAddresses(2)
                ->hasContacts(3)
                ->create()
                ->each(
                    function ($user, $key) use ($company, $roles) {

                        switch($key) {
                        case 1:
                            $role = $roles[1];
                            break;
                        case 2:
                            $role = $roles[2];
                            break;
                        default:
                            $role = $roles[3];
                        }

                        $user->attachRole($role, $company->id);
                    }
                );
        }
    }
}

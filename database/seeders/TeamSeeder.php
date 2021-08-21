<?php
/**
 * Laratrust Team Seeder
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
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
/**
 *  Team seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class TeamSeeder extends Seeder
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
                Team::factory(5)
                    ->has(
                        Address::factory(2)
                            ->state(
                                new Sequence(
                                    ['type'=>'primary'],
                                    ['type'=>'secondary']
                                )
                            )
                    )
                    ->hasContacts(2)
                    ->create();
            }
        );
    }
}

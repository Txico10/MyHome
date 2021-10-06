<?php
/**
 * Database seeder
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

use Illuminate\Database\Seeder;
/**
 *  DatabaseSeeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(
            [
                LaratrustSeeder::class,
                TeamSeeder::class,
                TeamSettingSeeder::class,
                ContractSettingSeeder::class,
                BuildingSeeder::class,
                AccessorySeeder::class,
                UserSeeder::class,
                EmployeeContractSeeder::class,
                LeaseSeeder::class,
            ]
        );
        // \App\Models\User::factory(10)->create();
    }
}

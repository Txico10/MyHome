<?php
/**
 * Team Setting Seeder
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

use App\Models\Team;
use Illuminate\Database\Seeder;
/**
 *  Team Setting seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class TeamSettingSeeder extends Seeder
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
                $benefits= [
                    'pension' => 'Pension',
                    'legislated_leaves' => 'Legislated Leaves',
                    'employment_insurance' => 'Employment Insurance',
                    'eye_exams' => 'Eye Exams',
                    'housing' => 'Housing',
                    'parking_lot' => 'Parking Lot'
                ];

                $teams = Team::all();

                foreach ($teams as $team) {
                    foreach ($benefits as $key => $value) {
                        $team->settings()->create(
                            [
                                'type'=>'benefit',
                                'name'=>$key,
                                'display_name' =>$value,
                            ]
                        );
                    }

                }
            }
        );

    }
}

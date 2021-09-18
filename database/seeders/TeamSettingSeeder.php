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
                    [
                        'type'         => 'benefit',
                        'name'         => 'pension',
                        'display_name' => 'Pension',
                    ],
                    [
                        'type'         => 'benefit',
                        'name'         => 'legislated_leaves',
                        'display_name' =>  'Legislated Leaves',
                    ],
                    [
                        'type'         => 'benefit',
                        'name'         => 'employment_insurance',
                        'display_name' => 'Employment Insurance',
                    ],
                    [
                        'type'         => 'benefit',
                        'name'         => 'eye_exams',
                        'display_name' => 'Eye Exams',
                    ],
                    [
                        'type'         => 'benefit',
                        'name'         => 'housing',
                        'display_name' => 'Housing',
                    ],
                    [
                        'type'         => 'benefit',
                        'name'         => 'parking_lot',
                        'display_name' => 'Parking Lot'
                    ],
                ];
                $contract_termination_reasons = [
                    [
                        'type'        => 'contract_termination',
                        'name'        => 'excuded',
                        'display_name'=> 'Delete Contract',
                        'description' => "Excuded due to errors or plan changing.;"
                    ],
                    [
                        'type'        => 'contract_termination',
                        'name'        => 'permanent_layoff',
                        'display_name'=> 'Permanent layoff',
                        'description' => "Economic reasons, such as financial difficulties; Organizational reasons, such as restructuring of the company or reorganization of duties; Technical reasons, such as technological innovations;"
                    ],
                    [
                        'type'         => 'contract_termination',
                        'name'         => 'layoff',
                        'display_name' => 'Layoff',
                        'description'  => 'A layoff temporarily suspends the employment contract between the employer and the worker for economic, organizational or technical reasons; The person may be recalled to work; The employment relationship is maintained during the layoff;'
                    ],
                    [
                        'type'        => 'contract_termination',
                        'name'        => 'dismissal',
                        'display_name'=> 'Dismissal',
                        'description' => 'A dismissal occurs when an employer puts a definitive end to worker\'s employment for disciplinary or performance reasons;'
                    ],
                    [
                        'type'        => 'contract_termination',
                        'name'        => 'resignation',
                        'display_name'=> 'Resignation',
                        'description' => 'A resignation occurs when a worker decides to leave their job permanently;'
                    ]
                ];

                $teams = Team::all();

                foreach ($teams as $team) {
                    $team->settings()->createMany($benefits);
                    $team->settings()->createMany($contract_termination_reasons);

                }
            }
        );

    }
}

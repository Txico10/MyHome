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

                $accessories = [
                    [
                        'type'        => 'appliances',
                        'name'        => 'stove',
                        'display_name'=> 'Stove',
                        'description' => 'Electrical stove with oven'
                    ],
                    [
                        'type'        => 'appliances',
                        'name'        => 'microwave_oven',
                        'display_name'=> 'Microwave Oven',
                        'description' => 'Electrical microwave and/or oven'
                    ],
                    [
                        'type'        => 'appliances',
                        'name'        => 'dishwasher',
                        'display_name'=> 'Dishwacher',
                        'description' => 'Electrical dishwasher'
                    ],
                    [
                        'type'        => 'appliances',
                        'name'        => 'refrigerator',
                        'display_name'=> 'Refrigerator',
                        'description' => 'Electrical refrigirator'
                    ],
                    [
                        'type'        => 'appliances',
                        'name'        => 'washer',
                        'display_name'=> 'Washer',
                        'description' => 'Electrical washer'
                    ],
                    [
                        'type'        => 'appliances',
                        'name'        => 'dryer',
                        'display_name'=> 'Dryer',
                        'description' => 'Electrical dryer'
                    ],
                    [
                        'type'        => 'furniture',
                        'name'        => 'table',
                        'display_name'=> 'Table',
                        'description' => ''
                    ],
                    [
                        'type'        => 'furniture',
                        'name'        => 'chair',
                        'display_name'=> 'Chair',
                        'description' => ''
                    ],
                    [
                        'type'        => 'furniture',
                        'name'        => 'chest_of_drawers',
                        'display_name'=> 'Chest of drawer',
                        'description' => ''
                    ],
                    [
                        'type'        => 'furniture',
                        'name'        => 'couch',
                        'display_name'=> 'Couch',
                        'description' => ''
                    ],
                    [
                        'type'        => 'furniture',
                        'name'        => 'armchair',
                        'display_name'=> 'Armchair',
                        'description' => ''
                    ],
                    [
                        'type'        => 'furniture',
                        'name'        => 'bed',
                        'display_name'=> 'Bed',
                        'description' => ''
                    ],
                ];

                $dependencies = [
                    [
                        'type'        => 'dependencie',
                        'name'        => 'outdoor_parking',
                        'display_name'=> 'Outdoor parking',
                    ],
                    [
                        'type'        => 'dependencie',
                        'name'        => 'indoor_parking',
                        'display_name'=> 'Indoor parking',
                    ],
                    [
                        'type'        => 'dependencie',
                        'name'        => 'storage',
                        'display_name'=> 'Locker or storage space',
                    ],

                ];

                $apartmentTypes =  [
                    [
                        'type'        => 'apartment',
                        'name'        => '1_1/2',
                        'display_name'=> '1 1/2',
                        'description' => 'Un et demi'
                    ],
                    [
                        'type'        => 'apartment',
                        'name'        => '2_1/2',
                        'display_name'=> '2 1/2',
                        'description' => 'Deux et demi'
                    ],
                    [
                        'type'        => 'apartment',
                        'name'        => '3_1/2',
                        'display_name'=> '3 1/2',
                        'description' => 'Trois et demi'
                    ],
                    [
                        'type'        => 'apartment',
                        'name'        => '4_1/2',
                        'display_name'=> '4 1/2',
                        'description' => 'Quatre et demi'
                    ],
                    [
                        'type'        => 'apartment',
                        'name'        => '5_1/2',
                        'display_name'=> '5 1/2',
                        'description' => 'Cinque et demi'
                    ],
                ];

                $heating_of_dweeling =  [
                    [
                        'type'        => 'heating_of_dweeling',
                        'name'        => 'electricity',
                        'display_name'=> 'Electricity',
                        'description' => 'Electrical heating system'
                    ],
                    [
                        'type'        => 'heating_of_dweeling',
                        'name'        => 'gas',
                        'display_name'=> 'Gas',
                        'description' => 'Gas heating system'
                    ],
                    [
                        'type'        => 'heating_of_dweeling',
                        'name'        => 'fuel_oil',
                        'display_name'=> 'Fuel Oil',
                        'description' => 'Fuel Oil heating system'
                    ],
                ];

                $services = [
                    [
                        'type'        => 'service',
                        'name'        => 'electrical',
                        'display_name'=> 'Electrical',
                        'description' => 'Electrical service'
                    ],
                    [
                        'type'        => 'service',
                        'name'        => 'plumbing',
                        'display_name'=> 'Plumbing',
                        'description' => 'Plumbing service'
                    ],
                    [
                        'type'        => 'service',
                        'name'        => 'floor',
                        'display_name'=> 'Floor Varnish',
                        'description' => 'Floor Varnish service'
                    ],
                    [
                        'type'        => 'service',
                        'name'        => 'painting',
                        'display_name'=> 'Painting',
                        'description' => 'Painting service'
                    ],
                    [
                        'type'        => 'snow_removal',
                        'name'        => 'snow_removal_parking',
                        'display_name'=> 'Parking area',
                        'description' => 'Snow and ice removal on parking area.'
                    ],
                    [
                        'type'        => 'snow_removal',
                        'name'        => 'snow_removal_balcony',
                        'display_name'=> 'Balcony',
                        'description' => 'Snow and ice removal from balcony.',
                    ],
                    [
                        'type'        => 'snow_removal',
                        'name'        => 'snow_removal_entrance',
                        'display_name'=> 'Entrance, walkway, driveway',
                        'description' => 'Snow and ice removal from entrance, walkway, driveway.'
                    ],
                    [
                        'type'        => 'snow_removal',
                        'name'        => 'snow_removal_stairs',
                        'display_name'=> 'Stairs',
                        'description' => 'Snow and ice removal from stairs.'
                    ],
                ];

                $consumption_costs = [
                    [
                        'type'        => 'consumption_cost',
                        'name'        => 'heating_dwelling',
                        'display_name'=> 'Heating of dwelling',
                        'description' => 'Heating of dwelling.'
                    ],
                    [
                        'type'        => 'consumption_cost',
                        'name'        => 'other_electricity',
                        'display_name'=> 'Electricity other the heating',
                        'description' => 'Electricity other the heating.'
                    ],
                    [
                        'type'        => 'consumption_cost',
                        'name'        => 'hot_wather_heater',
                        'display_name'=> 'Hot wather Heater (rental fees)',
                        'description' => 'Hot wather Heater (rental fees).'
                    ],
                    [
                        'type'        => 'consumption_cost',
                        'name'        => 'hot_wather',
                        'display_name'=> 'Hot wather(user fees)',
                        'description' => 'Hot wather (user fees).'
                    ],
                    [
                        'type'        => 'consumption_cost',
                        'name'        => 'wather_consumption_tax',
                        'display_name'=> 'Wather consumption tax',
                        'description' => 'Wather consumption tax for dwelling.'
                    ],
                ];

                $payment_methds = [
                    [
                        'type'        => 'method_payment',
                        'name'        => 'cash',
                        'display_name'=> 'Cash',
                        'description' => 'Payment made with cash.'
                    ],
                    [
                        'type'        => 'method_payment',
                        'name'        => 'cheque',
                        'display_name'=> 'Cheque',
                        'description' => 'Payment made with cheque.'
                    ],
                    [
                        'type'        => 'method_payment',
                        'name'        => 'e-transfer',
                        'display_name'=> 'Electronic bank transfer',
                        'description' => 'Electronic bank transfer.'
                    ],
                ];

                $teams = Team::all();

                foreach ($teams as $team) {
                    $team->settings()->createMany($benefits);
                    $team->settings()->createMany($contract_termination_reasons);
                    $team->settings()->createMany($accessories);
                    $team->settings()->createMany($dependencies);
                    $team->settings()->createMany($apartmentTypes);
                    $team->settings()->createMany($heating_of_dweeling);
                    $team->settings()->createMany($services);
                    $team->settings()->createMany($consumption_costs);
                    $team->settings()->createMany($payment_methds);
                }
            }
        );

    }
}

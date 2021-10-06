<?php
/**
 * Building Seeder
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

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Dependency;
use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;
/**
 *  Building seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BuildingSeeder extends Seeder
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
                $teams = Team::all();

                foreach ($teams as $key => $team) {
                    $types = TeamSetting::where('team_id', $team->id)
                        ->whereIn('type', ['apartment', 'dependencie'])->get();
                    Building::factory(5)
                        ->hasAddress()
                        ->create(
                            [
                                'team_id'=>$team->id
                            ]
                        )
                        ->each(
                            function ($building) use ($types) {
                                $apart_types = $types->where('type', 'apartment');
                                $depend_types = $types->where('type', 'dependencie');
                                Apartment::factory(5)
                                    ->state(
                                        new Sequence(
                                            ['number'=>'1'],
                                            ['number'=>'2'],
                                            ['number'=>'3'],
                                            ['number'=>'4'],
                                            ['number'=>'5']
                                        )
                                    )
                                    ->create(
                                        [
                                            'building_id'=>$building->id,
                                        ]
                                    )->each(
                                        function ($apartment) use ($apart_types) {

                                            $my_apart_type = $apart_types->random();

                                            $apartment->teamSettings()
                                                ->attach($my_apart_type->id);
                                        }
                                    );
                                Dependency::factory(20)
                                    ->create(['building_id'=>$building->id])
                                    ->each(
                                        function ($dependency) use ($depend_types) {
                                            $my_depend_types = $depend_types->random();
                                            $dependency->teamSettings()
                                                ->attach($my_depend_types->id);
                                        }
                                    );
                            }
                        );

                }

            }
        );
    }
}

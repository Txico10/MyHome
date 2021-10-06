<?php
/**
 * Accessory Seeder
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

use App\Models\Accessory;
use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Database\Seeder;
/**
 *  Accessory seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AccessorySeeder extends Seeder
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
                        ->whereIn('type', ['furniture', 'appliances'])->get();
                    Accessory::factory(50)
                        ->create(
                            [
                                'team_id'=>$team->id,
                            ]
                        )->each(
                            function ($accessory) use ($types) {
                                $my_type = $types->random();
                                $accessory->teamSettings()
                                    ->attach($my_type->id);
                            }
                        );
                }

            }
        );
    }
}

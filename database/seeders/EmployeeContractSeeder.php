<?php
/**
 * Employee contract Seeder
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
use App\Models\EmployeeContract;
use App\Models\Role;
use App\Models\Team;
use App\Models\TeamSetting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Factories\Sequence;
/**
 *  Employee contract seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeeContractSeeder extends Seeder
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
                $companies = Team::all();

                foreach ($companies as $company) {
                    $benefits = TeamSetting::where('team_id', $company->id)
                        ->where('type', 'benefit')->get();
                    EmployeeContract::factory(5)
                        ->state(
                            new Sequence(
                                [
                                    'role_id'=> 2,
                                    'end_at'=>null,
                                    'agreement_status'=>'accepted',
                                    'acceptance_at'=>now()
                                ],
                                ['role_id'=> 3],
                                ['role_id'=> 4],
                                ['role_id'=> 4],
                                ['role_id'=> 4],
                            )
                        )
                        ->hasAttached(
                            User::factory()
                                ->has(
                                    Address::factory(2)
                                        ->state(
                                            new Sequence(
                                                ['type'=>'primary'],
                                                ['type'=>'secondary']
                                            )
                                        )
                                )
                                ->hasContacts(3),
                            ['team_id'=>$company->id]
                        )
                        ->create()
                        ->each(
                            function ($contract) use ($company, $benefits) {
                                $user = $contract->users->first();
                                $user->attachRole($contract->role_id, $company);

                                $num = rand(1, 6);
                                $contract_benefits = $benefits->random($num);
                                foreach ($contract_benefits as $benefit) {
                                    $contract->teamSettings()->attach($benefit->id);
                                }

                            }
                        );
                }
            }
        );

    }
}

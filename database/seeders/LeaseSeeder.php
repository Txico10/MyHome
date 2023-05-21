<?php
/**
 * Lease Seeder
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

//use App\Models\Accessory;
use App\Models\Address;
use App\Models\CheckAccount;
use App\Models\Lease;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

/**
 *  Lease seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class LeaseSeeder extends Seeder
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
                $role = Role::where('name', 'tenant')->first();

                foreach ($companies as $key => $company) {
                    $apartments = $company->apartments->shuffle();

                    $accessories = $company->accessories->shuffle();
                    $accessories = $accessories->chunk(2);

                    $dependencies = $company->dependencies;

                    $services = $company->settings->where('type', 'service');
                    $consumptions = $company->settings->where('type', 'consumption_cost');
                    $payment_method = $company->settings->where('type', 'method_payment');

                    Lease::factory(20)
                        ->sequence(
                            fn ($sequence)=>[
                                'apartment_id'=>$apartments[$sequence->index]->id
                            ]
                        )
                        ->hasAttached(
                            User::factory()
                                ->has(
                                    Address::factory()
                                        ->state(
                                            new Sequence(
                                                ['type'=>'other'],
                                            )
                                        )
                                )
                                ->hasContacts(2)
                                ->hasCheckAccounts(1, ['team_id'=>$company->id]),
                            ['team_id'=>$company->id, 'check_account_id'=>1]
                        )
                        //->hasCheckAccount()
                        ->create()
                        ->each(
                            function ($lease, $key) use ($role, $company, $accessories, $dependencies, $services, $consumptions, $payment_method) {

                                $lease->code='BL'.$lease->start_at->format('mY').$lease->id.$company->id;
                                $lease->save();
                                $user = $lease->users->first();
                                $user->attachRole($role, $company);
                                $lease->users()->sync([$user->id => ['check_account_id'=>$user->checkAccounts->first()->id]], false);
                                $accessories_list = $accessories[$key];

                                foreach ($accessories_list as $accessory) {
                                    $lease->accessories()->attach($accessory->id, ['assigned_at'=>$lease->start_at, 'price'=>'7.50']);
                                }

                                $dependencies = $dependencies->where('building_id', $lease->apartment->building_id);
                                $dependencies = $dependencies->shuffle();

                                $position = $key%20;

                                while ($dependencies[$position]->leases->count()>0) {
                                    $position=($position+1)%20;
                                };

                                $dependecy_name =$dependencies[$position]->teamSettings->first()->name;

                                if (strcmp($dependecy_name, "outdoor_parking")==0) {
                                    $price = '75.85';
                                } elseif (strcmp($dependecy_name, "indoor_parking")==0) {
                                    $price = '150.35';
                                } else {
                                    $price = '25.00';
                                }
                                $lease->dependencies()->attach($dependencies[$position]->id, ['assigned_at'=>$lease->start_at, 'price'=>$price]);

                                $my_payment_method = $payment_method->shuffle();
                                $my_payment_method = $my_payment_method->shift(2);

                                foreach ($my_payment_method as $payment) {
                                    $lease->teamSettings()->attach($payment->id);
                                }

                                $work_before = $services->whereIn('name', ['painting', 'floor']);
                                $repair_during = $services->whereIn('name', ['electrical', 'plumbing']);
                                $snow_removals = $services->whereNotIn('name', ['painting', 'floor', 'electrical', 'plumbing']);

                                foreach ($work_before as $service) {
                                    $lease->teamSettings()->attach($service->id, ['description'=>'before the delivery of the dwelling']);
                                }

                                foreach ($repair_during as $service) {
                                    $lease->teamSettings()->attach($service->id, ['description'=>'during the lease']);
                                }

                                foreach ($snow_removals as $snow_removal) {
                                    if (strcmp($snow_removal->name, 'snow_removal_balcony')==0) {
                                        $resposability = 'lessee';
                                    } else {
                                        $resposability = 'lessor';
                                    }

                                    $lease->teamSettings()->attach($snow_removal->id, ['description'=>$resposability]);

                                }

                                $owner_consumptions = $consumptions->shuffle();
                                $client_consumptions = $owner_consumptions->shift(3);

                                foreach ($owner_consumptions as $owner_consumption) {
                                    $lease->teamSettings()->attach($owner_consumption->id, ['description'=>'lessor']);
                                }

                                foreach ($client_consumptions as $client_consumption) {
                                    $lease->teamSettings()->attach($client_consumption->id, ['description'=>'lessee']);
                                }

                            }
                        );
                }
            }
        );
    }
}

<?php
/**
 * Bill Seeder
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

use App\Models\Lease;
use App\Models\Bill;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

/**
 *  Bill seeder class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BillSeeder extends Seeder
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
                $leases = Lease::all();
                $companies = Team::all();
                $bill_number = [];
                foreach ($companies as $company) {
                    $bill_number[$company->id]=0;
                }
                foreach ($leases as $lease) {
                    $total = 0;

                    $bill_company = $lease->teams->first();

                    $bill = Bill::factory()
                        ->create(
                            [
                                'team_id'=>$bill_company->id,
                                'period_from'=>$lease->start_at,
                                'period_to'=>$lease->start_at->addMonth(),
                                'payment_due_date'=>$lease->start_at->addMonth(2),
                            ]
                        );

                    $bill->number = ++$bill_number[$bill->team_id];

                    $bill->invoiceLease()->attach(
                        $lease,
                        [
                            'amount'=>$lease->rent_amount,
                            'oparation'=>'debi'
                        ]
                    );
                    $total+=$lease->rent_amount;
                    $dependencies = $lease->dependencies;
                    foreach ($dependencies as $dependency) {
                        $bill->invoiceDependencie()->attach(
                            $dependency,
                            [
                                'amount'=>$dependency->pivot->price,
                                'oparation'=>'debi'
                            ]
                        );
                        $total+=$dependency->pivot->price;
                    }
                    $accessories = $lease->accessories;
                    foreach ($accessories as $accessory) {
                        $bill->invoiceAccessory()->attach(
                            $accessory,
                            [
                                'amount'=>$accessory->pivot->price,
                                'oparation'=>'debi'
                            ]
                        );
                        $total+=$accessory->pivot->price;
                    }

                    $tenants = $lease->users;

                    foreach ($tenants as $tenant) {
                        $checkaccount=$tenant->checkAccounts->first();
                        $bill->checkAccounts()->attach($checkaccount);
                    }

                    $bill->total_amount = $total;
                    $bill->save();
                }
            }
        );
    }
}

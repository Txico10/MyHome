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
use Illuminate\Database\Seeder;
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

                foreach ($leases as $lease) {
                    $total = 0;
                    $user = $lease->users()->first();
                    $bill = Bill::factory()->create(
                        [
                            'check_account_id'=>$user->checkAccounts()->first()->id,
                            'period_from'=>$lease->start_at,
                            'period_to'=>$lease->start_at->addMonth(),
                            'payment_due_date'=>$lease->start_at->addMonth(2),
                        ]
                    );

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
                    $bill->total_amount = $total;
                    $bill->save();
                }
            }
        );
    }
}

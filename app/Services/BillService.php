<?php
/**
 * Bill Service
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Services;

use App\Models\Bill;
use App\Models\Lease;
use App\Models\Team;
use Carbon\Carbon;


/**
 *  Bill Service class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BillService
{

    /**
     * Add New Invoice
     *
     * @param Lease  $lease      lease
     * @param Carbon $newPeriod  New period
     * @param int    $company_id Company
     *
     * @return void
     */
    protected function addInvoice(Lease $lease, Carbon $newPeriod, int $company_id)
    {

        $total_amount = $lease->rent_amount;
        $lease = $lease->loadMissing(['dependencies', 'accessories', 'users']);

        $bill_data = [
            'team_id'=>$company_id,
            'number'=>$this->nextBillNumber($company_id),
            'period_from'=>$newPeriod->format('Y-m').'-'.$lease->start_at->format('d'),
            'period_to'=>$newPeriod->addMonth()->format('Y-m').'-'.$lease->start_at->format('d'),
            'status' => 'created',
            'payment_due_date'=>$newPeriod->addMonth()->format('Y-m').'-'.$lease->start_at->format('d'),
            'description' => "Generated automatically by the system"
        ];

        $bill = Bill::create($bill_data);

        $bill->invoiceLease()->attach(
            $lease,
            [
                'amount'=>$lease->rent_amount,
                'oparation'=>'debi'
            ]
        );

        $dependencies = $lease->dependencies;

        foreach ($dependencies as $dependency) {
            $bill->invoiceDependencie()->attach(
                $dependency,
                [
                    'amount'=>$dependency->pivot->price,
                    'oparation'=>'debi'
                ]
            );
            $total_amount+=$dependency->pivot->price;
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
            $total_amount+=$accessory->pivot->price;
        }

        $tenants = $lease->users;

        foreach ($tenants as $tenant) {
            $checkaccount=$tenant->checkAccounts->first();
            $bill->checkAccounts()->attach($checkaccount);
        }

        $bill->total_amount = $total_amount;
        $bill->save();

    }

    /**
     * GenerateBill
     *
     * @param array $search_data Search
     *
     * @return bool
     */
    public function generateBills(array $search_data): bool
    {
        $periods = Carbon::parse($search_data['start_at'])->monthsUntil($search_data['end_at']);
        //dd($periods->toArray());

        if (!empty($search_data['company_id'])) {
            if (empty($search_data['lease_id'])) {

                $company = Team::findOrFail($search_data['company_id']);

                $leases = $company->leases->filter(
                    function ($item) {
                        if (is_null($item->end_at) || $item->end_at->greaterThanOrEqualTo('today')) {
                            return $item;
                        }
                    }
                );

                foreach ($leases as $new_lease) {
                    foreach ($periods as $period) {
                        if (!$this->invoicePeriod($new_lease, $period)) {
                            //create invoice
                            $this->addInvoice($new_lease, $period, $search_data['company_id']);
                        }
                    }

                }
            } else {
                $lease = Lease::findOrFail($search_data['lease_id']);

                foreach ($periods as $period) {
                    //dd($period);
                    if (!$this->invoicePeriod($lease, $period)) {
                        //create lease
                        //dd($period);
                        $this->addInvoice($lease, $period, $search_data['company_id']);
                    }
                }

            }
            return true;

        }
        return false;
    }

    /**
     * InvoicePeriod
     *
     * @param Lease  $lease    Lease
     * @param Carbon $new_date Date
     *
     * @return bool
     */
    protected function invoicePeriod( Lease $lease, Carbon $new_date): bool
    {
        //dd($new_date->format('Y-m-d'));

        if ($lease->start_at->greaterThan($new_date)) {
            return true;
        }
        //dd($new_date->format('Y-m-d'));
        $invoices = $lease->invoices;

        foreach ($invoices as $invoice) {
            if ($invoice->period_from->isSameMonth($new_date)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Next BillNumber
     *
     * @param int $company_id Company id
     *
     * @return int
     */
    protected function nextBillNumber(int $company_id): int
    {
        $company = Team::find($company_id)->load('bills');

        if ($company->bills->isEmpty()) {
            return 1;
        }

        return ((int)$company->bills->max('number'))+1;

    }




}

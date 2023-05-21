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

use App\Models\Accessory;
use App\Models\Bill;
use App\Models\Dependency;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Support\Str;

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
     * Add New Invoice: Create a new Invoice from lease
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
            if (!is_null($dependency->pivot->assigned_at) && Carbon::parse($dependency->pivot->assigned_at)->lessThanOrEqualTo($bill_data['period_to']) && (is_null($dependency->pivot->removed_at)||Carbon::parse($dependency->pivot->removed_at)->greaterThanOrEqualTo($bill_data['period_from']))) {
                $bill->invoiceDependencie()->attach(
                    $dependency,
                    [
                        'amount'=>$dependency->pivot->price,
                        'oparation'=>'debi'
                    ]
                );
                $total_amount+=$dependency->pivot->price;
            }
        }

        $accessories = $lease->accessories;

        foreach ($accessories as $accessory) {

            if (!is_null($accessory->pivot->assigned_at) && Carbon::parse($accessory->pivot->assigned_at)->lessThanOrEqualTo($bill_data['period_to']) && (is_null($accessory->pivot->removed_at)||Carbon::parse($accessory->pivot->removed_at)->greaterThanOrEqualTo($bill_data['period_from']))) {
                $bill->invoiceAccessory()->attach(
                    $accessory,
                    [
                        'amount'=>$accessory->pivot->price,
                        'oparation'=>'debi'
                    ]
                );
                $total_amount+=$accessory->pivot->price;
            }

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
     * GenerateBill : Generates a batch of bills or one bill in a period of time
     *                or in a specific date
     *
     * @param array $search_data Search
     *
     * @return bool
     */
    public function generateBills(array $search_data): bool
    {
        $periods = Carbon::parse($search_data['start_at'])->monthsUntil($search_data['end_at']);


        if (!empty($search_data['company_id'])) {
            if (empty($search_data['lease_id'])) {
                //Generate invoice for a batch of leases
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
                //generate a invoices for a lease
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
     * InvoicePeriod: Verify if the invoice already exists
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
     * Next BillNumber: return the next invoice number available per company
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

    /**
     * Bill lines: bill lines for invoice draw
     *
     * @param int $bill_id Bill ID
     *
     * @return Collection
     */
    public function billLines(int $bill_id)
    {
        $invoices = Invoice::where('bill_id', $bill_id)->get();

        $newData = collect([]);
        foreach ( $invoices as $invoice) {
            $parts = explode("\\", $invoice->billable_type);
            $content = '';
            if (strcmp($parts[2], "Lease")==0) {
                $lease  = Lease::find($invoice->billable_id);
                $apartment = $lease->apartment->load('building');
                $content = 'Rent off apartment #'.$apartment->number.' on building '.Str::ucfirst($apartment->building->display_name);
                $serial  = $lease->code;
            } elseif (strcmp($parts[2], "Accessory")==0) {
                $access  = Accessory::find($invoice->billable_id);
                $content = $access->teamSettings->first()->display_name;
                $content = $content.' : '.ucfirst($access->manufacturer).' '.$access->model;
                $serial  = strtoupper($access->serial);
            } else {
                $dep = Dependency::find($invoice->billable_id);
                $content = $dep->teamSettings->first()->display_name;
                $serial  = strtoupper($dep->number);
            }
            $newData->push(['name'=>$content, 'serial'=>$serial, 'amount'=>$invoice->amount, 'description'=>$invoice->description]);
        }

        return $newData;
    }

    /**
     * Bill Tenants: Bill tenants for invoice draw
     *
     * @param Bill $bill Bill
     *
     * @return Collection
     */
    public function billTenants(Bill $bill)
    {

        $tenant = $bill->checkAccounts->first()->user;

        $tenant_address = $bill->invoiceLease->first()->apartment->building->address;
        $tenant_address->suite = $bill->invoiceLease->first()->apartment->number;

        $contact = $tenant->contacts->filter(
            function ($item) {
                if (strcmp($item->type, 'phone')==0 || strcmp($item->type, 'mobile')==0 || strcmp($item->type, 'email')==0) {
                    return $item;
                }
            }
        );
        //dd($contact);

        $tenant_data = collect(
            [
                'name'=>$tenant->name,
                'email'=> $contact->where('type', 'email')->where('priority', 'main')->first()?$contact->where('type', 'email')->where('priority', 'main')->first()->description:$tenant->email,
                'phone'=> $contact->where('type', 'phone')->first()?$contact->where('type', 'phone')->first()->description:'',
                'address' => $tenant_address,
            ]
        );

        return $tenant_data;
    }




}

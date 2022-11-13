<?php
/**
 * Lease Controller
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Controllers;

use App\Models\Accessory;
use App\Models\Bill;
use App\Models\Dependency;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Team;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;

/**
 *  Bills Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BillController extends Controller
{
    /**
     * Index
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response;
     */
    public function index(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $bills = $company->bills;
            $number = 1;
            return datatables()->of($bills)
                ->addIndexColumn()
                ->addColumn(
                    'lease',
                    function ($bill) use ($company) {
                        $leases = $bill->invoiceLease;
                        $mycode = '';
                        foreach ($leases as $lease) {
                            $mycode= $mycode.'BL'.$lease->start_at->format('mY').$lease->id.$company->id;
                        }
                        return $mycode;
                    }
                )
                ->addColumn(
                    'tenant',
                    function ($bill) {
                        $leases = $bill->invoiceLease;
                        foreach ($leases as $lease) {
                            $tenants = $lease->users;
                            $name = '';
                            foreach ($tenants as $tenant) {
                                if (empty($name)) {
                                    $name = $name.$tenant->name;
                                } else {
                                    $name = $name.$tenant->name.' - ';
                                }

                            }
                        }
                        return $name;
                    }
                )
                ->addColumn(
                    'period',
                    function ($bill) {
                        $period = '';
                        $period.= $bill->period_from->format('d M').' - ';
                        $period.=$bill->period_to->format('d M');
                        return $period;
                    }
                )
                ->addColumn(
                    'month',
                    function ($bill) {
                        return $bill->period_from->format('F');
                    }
                )
                ->addColumn(
                    'year',
                    function ($bill) {
                        return $bill->period_from->format('Y');
                    }
                )
                ->editColumn(
                    'period_from',
                    function ($bill) {
                        return $bill->period_from->toFormattedDateString();
                    }
                )
                ->editColumn(
                    'period_to',
                    function ($bill) {
                        return $bill->period_to->toFormattedDateString();
                    }
                )
                ->editColumn(
                    'payment_due_date',
                    function ($bill) {
                        return $bill->payment_due_date->toFormattedDateString();
                    }
                )
                ->editColumn(
                    'total_amount',
                    function ($bill) {
                        return '$'.$bill->total_amount;
                    }
                )
                ->addColumn(
                    'action',
                    function ($bill) use ($company) {
                        $btn_validation = true;
                        $btn = '<nobr>';
                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.invoice.show', ['company'=>$company, 'bill'=>$bill]).'"><i class="fas fa-search fa-fw"></i></a>';

                        if ($btn_validation && LaratrustFacade::isAbleTo('bill-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editLeaseButton" type="button" title="Edit bill" value="'.$bill->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';

                        }
                        if ($btn_validation && LaratrustFacade::isAbleTo('bill-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteLeaseButton" title="Delete bill" type="button" value="'.$bill->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('team_id')
                ->removeColumn('periode_from')
                ->removeColumn('periode_to')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }
        return view('companies.bills', ['company'=>$company]);
    }

    /**
     * Create bill
     *
     * @param Team $company Company
     *
     * @return void
     */
    public function create(Team $company)
    {
        // code...
    }

    /**
     * Show
     *
     * @param Request $request Request
     * @param Team    $company Company
     * @param Bill    $bill    Bill
     *
     * @return void
     */
    public function show(Request $request, Team $company, Bill $bill)
    {
        //$bill = $bill->loadMissing('invoiceLease', 'invoiceDependencie', 'invoiceAccessory');
        //$lease = $bill->invoiceLease->first();
        //$dependencies = $bill->invoiceDependencie;
        //$accessories = $bill->invoiceAccessory;
        //dd($dependencies);
        if ($request->ajax()) {
            $invoices = Invoice::where('bill_id', $bill->id)->get();
            $total = 0.00;
            return datatables()->of($invoices)
                ->addIndexColumn()
                ->addColumn(
                    'type',
                    function ($invoice) {
                        $parts = explode("\\", $invoice->billable_type);
                        $content = '';
                        if (strcmp($parts[2], "Lease")==0) {
                            $record = Lease::find($invoice->billable_id);
                            $content = $parts[2];
                            $content = $content.' : '.$record->code;
                        } elseif (strcmp($parts[2], "Accessory")==0) {
                            $access = Accessory::find($invoice->billable_id);
                            $content = $access->teamSettings->first()->display_name;
                            $content = $content.' : '.ucfirst($access->manufacturer).' '.$access->model;
                        } else {
                            $dep = Dependency::find($invoice->billable_id);
                            $content = $dep->teamSettings->first()->display_name;
                            $content = $content.' : '.$dep->number;
                        }
                        return $content;
                        //return $invoice->billable_type;
                    }
                )
                ->editColumn(
                    'amount',
                    function ($invoice) {
                        return '$'.$invoice->amount;
                    }
                )
                ->with('total', $invoices->sum('amount'))
                ->removeColumn('id')
                ->removeColumn('billable_type')
                ->removeColumn('billable_id')
                ->removeColumn('bill_id')
                ->removeColumn('operation')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }
        return view('companies.bill-show', ['company'=>$company, 'bill'=>$bill]);
    }
}

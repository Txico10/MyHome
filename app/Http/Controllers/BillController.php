<?php
/**
 * Bill Controller
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

use App\Jobs\GenerateLeaseBills;
use App\Models\Accessory;
use App\Models\Bill;
use App\Models\Dependency;
use App\Models\Invoice;
use App\Models\Lease;
use App\Models\Team;
use App\Services\BillService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
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

    protected $billService;

    /**
     * __construct
     *
     * @param BillService $billService Bill Service
     *
     * @return void
     */
    public function __construct(BillService $billService)
    {
        $this->billService = $billService;
    }

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
            return datatables()->of($bills)
                ->addIndexColumn()
                ->addColumn(
                    'lease',
                    function ($bill) {
                        $lease = $bill->invoiceLease->first();
                        return $lease->code;
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
                    'created_at',
                    function ($bill) {
                        return $bill->created_at->toFormattedDateString();
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
                        $payments = $bill->payments;
                        $btn_validation = $payments->isEmpty();
                        $btn = '<nobr>';
                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.invoice.show', ['company'=>$company, 'bill'=>$bill]).'"><i class="fas fa-search fa-fw"></i></a>';

                        if ($btn_validation && LaratrustFacade::isAbleTo('payment-create')) {
                            $btn = $btn.'<button class="btn btn-outline-success mx-1 shadow btn-sm makePaymentButton" type="button" title="Make Payment" value="'.$bill->id.'"><i class="fas fa-money-bill-alt fa-fw"></i></button>';
                        }

                        if ($btn_validation && LaratrustFacade::isAbleTo('bill-update')) {
                            $btn = $btn.'<a href="'.route('company.invoice.edit', ['company'=>$company, 'bill'=>$bill]).'" class="btn btn-outline-secondary mx-1 shadow btn-sm"><i class="fas fa-pencil-alt fa-fw"></i></a>';

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
                //->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }
        return view('companies.bills', ['company'=>$company]);
    }

    /**
     * Create bill
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return void
     */
    public function create(Request $request, Team $company)
    {
        if ($request->ajax()) {

            $request->validate(
                [
                    'lease_code'=>['nullable', 'numeric', 'exists:leases,id'],
                    'start_date' => ['required', 'date', 'before_or_equal:today'],
                    'end_date' => ['required', 'date', 'after_or_equal:start_date']
                ]
            );

            $search = [
                'company_id' => $company->id,
                'lease_id'   => $request->lease_code,
                'start_at'   => $request->start_date,
                'end_at'     => $request->end_date
            ];

            GenerateLeaseBills::dispatch($search);
            /*
            if ($this->billService->generateBills($search)) {
                $data = "W Company ID";
            } else {
                $data = "Wout Company ID";
            }
            */
            return response()->json(['message'=>"Bill created successfully"]);
        }
        return null;
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
        $company = $company->loadMissing(['addresses', 'contacts']);

        $bill = $bill->loadMissing('checkAccounts.user', 'payments');

        $bill_lines = $this->billService->billLines($bill->id);

        $tenant_data = $this->billService->billTenants($bill);


        return view('companies.bill-show', ['company'=>$company, 'bill'=>$bill, 'bill_lines'=>$bill_lines, 'tenant'=>$tenant_data]);
    }

    /**
     * Edit Bill
     *
     * @param Team $company Company
     * @param Bill $bill    Bill
     *
     * @return void
     */
    public function edit(Team $company, Bill $bill)
    {
        return view('companies.bill-edit', ['company'=>$company, 'bill'=>$bill]);
    }

    /**
     * DownloadPDF
     *
     * @param Team $company Company
     * @param Bill $bill    Bill
     *
     * @return void
     */
    public function downloadPDF(Team $company, Bill $bill)
    {
        $file_path = "public/reports/pdf/";
        $filename = "IN".$bill->period_from->format('mY').$bill->number.$company->id.".pdf";

        $file_exists= Storage::exists($file_path.$filename);

        if ($file_exists) {

            return Storage::download($file_path.$filename);

        } else {
            $bill_lines = $this->billService->billLines($bill->id);

            $tenant = $this->billService->billTenants($bill);

            $data = [
                'company'=>$company,
                'bill'=>$bill,
                'bill_lines'=>$bill_lines,
                'tenants'=>$tenant
            ];

            $path = storage_path('app/public/reports/pdf');

            Pdf::loadView('companies.dompdf.invoice-report', $data)->setPaper('letter')->save($path.'/'.$filename);

            return Storage::download($file_path.$filename);
            //return $pdf->download('lease.pdf');
        }
        //dd($file_exists);
    }

    /**
     * Get Tenants
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return JsonResponse
     */
    public function getTenants(Request $request, Team $company)
    {
        if ($request->ajax()) {

            $request->validate(
                [
                    'bill_id'=>['required', 'numeric', 'exists:bills,id']
                ]
            );

            $bill = Bill::find($request->bill_id);

            $check_accs = $bill->checkAccounts;

            $data = array();

            foreach ($check_accs as $key => $check_acc) {

                $data[$check_acc->user->id] = [
                    'name'        =>$check_acc->user->name,
                    'email'       =>$check_acc->user->email,
                    'bill_id'     =>$bill->id,
                    'bill_num'    =>$bill->number,
                    'total_amount'=>$bill->total_amount,
                    'payment_at' =>$bill->payment_due_date->format('Y-m-d'),
                    'created_at' =>$bill->created_at->format('Y-m-d')
                ];
            }

            return response()->json(['tenants'=>$data]);
        }

    }
}

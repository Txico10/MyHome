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

use App\Models\Lease;
use App\Models\Role;
use App\Models\Team;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

/**
 *  Lease Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class LeaseController extends Controller
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
        //$apartments = $company->apartments->load('building');
        //dd($apartments);
        if ($request->ajax()) {
            $leases = $company->leases;
            $users = $company->users;
            $apartments = $company->apartments->load('building');

            return datatables()->of($leases)
                ->addColumn(
                    'code',
                    function ($lease) use ($company) {
                        return 'BL'.$lease->start_at->format('mY').$lease->id.$company->id;
                    }
                )
                ->addColumn(
                    'tenant',
                    function ($lease) use ($users) {
                        $user = $users->where('id', $lease->pivot->user_id)->first();
                        return $user->name;
                    }
                )
                ->addColumn(
                    'apart',
                    function ($lease) use ($apartments) {
                        $apartment = $apartments->where('id', $lease->apartment_id)->first();
                        return $apartment->building->display_name.'-'.$apartment->number;
                    }
                )
                ->editColumn(
                    'term',
                    function ($lease) {
                        return ucfirst($lease->term);
                    }
                )
                ->editColumn(
                    'start_at',
                    function ($lease) {
                        return $lease->start_at->toDateString();
                    }
                )
                ->editColumn(
                    'end_at',
                    function ($lease) {
                        return $lease->end_at==null?'N/A':$lease->end_at->toDateString();
                    }
                )
                ->addColumn(
                    'status',
                    function ($lease) {
                        if (strcmp($lease->term, "fixed")==0 && $lease->end_at->lessThan(today())) {
                            return "Inactive";
                        } else {
                            if (strcmp($lease->term, "indeterminate")==0 && $lease->end_at!=null) {
                                return "Inactive";
                            } else {
                                return "Active";
                            }

                        }

                    }
                )
                ->addColumn(
                    'action',
                    function ($lease) use ($company) {
                        $btn_validation = false;
                        $btn = '<nobr>';
                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.lease.show', ['company'=>$company, 'lease'=>$lease]).'"><i class="fas fa-search fa-fw"></i></a>';
                        $btn = $btn.'<a class="btn btn-outline-danger btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.lease.download', ['company'=>$company, 'lease'=>$lease]).'"><i class="fas fa-file-pdf fa-fw"></i></a>';

                        if (strcmp($lease->term, "fixed")==0) {
                            if ($lease->end_at!=null && $lease->end_at->greaterThanOrEqualTo(today())) {
                                $btn_validation = true;
                            }
                        } else {
                            if ($lease->end_at==null) {
                                $btn_validation = true;
                            }

                        }

                        if ($btn_validation && LaratrustFacade::isAbleTo('lease-update')) {
                            if ($lease->start_at->greaterThanOrEqualTo(now())) {
                                $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editLeaseButton" type="button" title="Edit lease" value="'.$lease->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                            }

                        }
                        if ($btn_validation && LaratrustFacade::isAbleTo('lease-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteLeaseButton" title="Delete Lease" type="button" value="'.$lease->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('residential_purpose')
                ->removeColumn('residential_purpose_description')
                ->removeColumn('co-ownership')
                ->removeColumn('furniture_included')
                ->removeColumn('rent_amount')
                ->removeColumn('rent_recurrence')
                ->removeColumn('subsidy_program')
                ->removeColumn('first_payment_at')
                ->removeColumn('postdated_cheques')
                ->removeColumn('by_law_given_on')
                ->removeColumn('land_access')
                ->removeColumn('land_access_description')
                ->removeColumn('animals')
                ->removeColumn('others')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->removeColumn('pivot')
                ->make();

        }

        //dd($leases);

        return view('companies.leases', ['company'=>$company]);
    }

    /**
     * Create
     *
     * @param Team $company Company
     *
     * @return void
     */
    public function create(Team $company)
    {
        return view('companies.lease-create', ['company'=>$company]);
    }

    /**
     * Show
     *
     * @param Team  $company Company
     * @param Lease $lease   Lease
     *
     * @return view
     */
    public function show(Team $company, Lease $lease)
    {
        $company = $company->load('addresses', 'contacts');
        $lease = $lease->loadMissing('apartment.building', 'accessories', 'dependencies', 'teamSettings', 'users.addresses', 'users.contacts');
        $total_sum = 0;
        foreach ($lease->accessories as $key => $accessory) {
            $total_sum = $total_sum+$accessory->pivot->price;
        }

        foreach ($lease->dependencies as $key=>$dependency) {
            $total_sum = $total_sum+$dependency->pivot->price;
        }
        //dd($lease->accessories);
        //$tenant = User::find($lease->pivot->user_id);
        //$tenant = $tenant->loadMissing('contacts');

        return view('companies.lease-show', ['company'=>$company, 'lease'=>$lease, 'total_sum'=>$total_sum]);
    }

    /**
     * DownloadPDF
     *
     * @param Team  $company Company
     * @param Lease $lease   lease
     *
     * @return void
     */
    public function downloadPDF(Team $company, Lease $lease)
    {
        $file_path = "public/reports/pdf/";
        $filename = "BL".$lease->start_at->format('mY').$lease->id.$company->id.".pdf";

        $file_exists= Storage::exists($file_path.$filename);

        if ($file_exists) {

            return Storage::download($file_path.$filename);

        } else {
            $company = $company->load('addresses', 'contacts');
            $lease = $lease->loadMissing('apartment.building.address', 'apartment.teamSettings', 'accessories', 'dependencies', 'teamSettings', 'users.addresses', 'users.contacts');
            $janitor_role = Role::where('name', 'janitor')->first()->id;
            $janitor = $company->usersProfile($janitor_role)->first();
            $janitor = $janitor->load('contacts');

            if (strcmp($lease->term, "fixed")==0 && $lease->end_at->lessThan(today())) {
                $lease_status = "Inactive";
            } else {
                if (strcmp($lease->term, "indeterminate")==0 && $lease->end_at!=null) {
                    $lease_status = "Inactive";
                } else {
                    $lease_status = "Active";
                }

            }

            $data = [
                'company' => $company,
                'lease'=>$lease,
                'janitor'=>$janitor,
                'lease_status' => $lease_status,
            ];

            $path = storage_path('app/public/reports/pdf');

            Pdf::loadView('companies.dompdf.lease-report', $data)->setPaper('letter')->save($path.'/'.$filename);

            return Storage::download($file_path.$filename);
            //return $pdf->download('lease.pdf');
        }
        //dd($file_exists);
    }

    /**
     * Get Lease Code
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return void
     */
    public function getLeasesCode(Request $request, Team $company)
    {
        if ($request->ajax()) {

            $all_leases = $company->leases->filter(
                function ($item) {
                    if (is_null($item->end_at) ||$item->end_at->greaterThanOrEqualTo('today')) {
                        return $item;
                    }
                }
            )
            ->values();
            //$leases = $all_leases->pluck('code', 'id');

            return response()->json(['leases'=>$all_leases->pluck('code', 'id')]);
        }
        return null;
    }
}

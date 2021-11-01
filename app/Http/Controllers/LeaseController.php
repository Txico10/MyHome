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
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
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
                        return "Active";
                    }
                )
                ->addColumn(
                    'action',
                    function ($lease) use ($company) {
                        $btn = '<nobr>';
                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.lease.show', ['company'=>$company, 'lease'=>$lease]).'"><i class="fas fa-search fa-fw"></i></a>';
                        if (LaratrustFacade::isAbleTo('lease-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editLeaseButton" type="button" title="Edit lease" value="'.$lease->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        if (LaratrustFacade::isAbleTo('lease-delete')) {
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
                ->removeColumn(' first_payment_at')
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
}

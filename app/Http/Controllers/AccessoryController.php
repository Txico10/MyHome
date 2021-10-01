<?php
/**
 * Accessory Controller
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
use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
/**
 *  Accessory Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AccessoryController extends Controller
{
    /**
     * Index
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return void
     */
    public function index(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $accessories = $company->accessories;
            return datatables()->of($accessories)
                ->addIndexColumn()
                ->addColumn(
                    'type',
                    function ($accessory) {
                        return $accessory->teamSettings->first()->display_name??'N/A';
                    }
                )
                ->editColumn(
                    'manufacturer',
                    function ($accessory) {
                        return ucfirst($accessory->manufacturer);
                    }
                )
                ->editColumn(
                    'model',
                    function ($accessory) {
                        return strtoupper($accessory->model);
                    }
                )
                ->editColumn(
                    'serial',
                    function ($accessory) {
                        return strtoupper($accessory->serial);
                    }
                )
                ->editColumn(
                    'buy_at',
                    function ($accessory) {
                        return $accessory->buy_at->format('d F Y');
                    }
                )
                ->editColumn(
                    'discontinued_at',
                    function ($accessory) {
                        return $accessory->discontinued_at==null? '<span class="badge bg-success">Yes</span>' :'<span class="badge bg-danger">No</span>';
                    }
                )
                ->addColumn(
                    'action',
                    function ($accessory) use ($company) {
                        $btn = '<nobr>';
                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.accessory.show', ['company'=>$company, 'accessory'=>$accessory]).'"><i class="fas fa-search fa-fw"></i></a>';
                        if (LaratrustFacade::isAbleTo('accessory-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editAccessoryButton" type="button" title="Edit accessory" value="'.$accessory->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }

                        if (LaratrustFacade::isAbleTo('accessory-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteAccessorytButton" title="Delete accessory" type="button" value="'.$accessory->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->rawColumns(['discontinued_at', 'action'])
                ->removeColumn('id')
                ->removeColumn('team_id')
                ->removeColumn('qrcode')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }

        $accessory_types = TeamSetting::where('team_id', $company->id)->whereIn('type', ['furniture', 'appliances'])->pluck('display_name', 'id');
        $accessory = $company->accessories->first();

        return view('companies.accessories', ['company'=>$company, 'accessory_types'=>$accessory_types, 'accessory'=>$accessory]);
    }

    /**
     * Store
     *
     * @param Request $request Request
     * @param Team    $company $company
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'id'=>['nullable', 'numeric', 'exists:accessories,id'],
                    'type'=>['required'],
                    'manufacturer'=>['required', 'string', 'min:5', 'max:255'],
                    'model'=>['required', 'string', 'min:5', 'max:255'],
                    'serial'=>['required', 'string', 'min:5', 'max:255'],
                    'buy_at'=>['required', 'date']
                ]
            );

            $accessory = Accessory::updateOrCreate(
                ['id' => $request->id],
                [
                    'team_id'      => $company->id,
                    'manufacturer' => strtolower($request->manufacturer),
                    'model'        => strtolower($request->model),
                    'serial'       => strtolower($request->serial),
                    'buy_at'       => $request->buy_at
                ]
            );

            if ($accessory->teamSettings->isEmpty()) {
                $accessory->teamSettings()->attach($request->type);
            } else {
                $accessory->teamSettings()->sync($request->type);
            }

            return response()->json(['message'=>'Accessory saved successfully']);
        }
        return null;
    }

    /**
     * Show
     *
     * @param Team      $company   Company
     * @param Accessory $accessory Furniture and apliances
     *
     * @return void
     */
    public function show(Team $company, Accessory $accessory)
    {
        return view('companies.accessory-show', ['company'=>$company, 'acessory'=>$accessory]);
    }

    /**
     * Edit
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'accessory_id'=>['required', 'exists:accessories,id']
                ]
            );

            $accessory = Accessory::where('id', $request->accessory_id)->first();
            $accessory->manufacturer = ucfirst($accessory->manufacturer);
            $accessory->model = strtoupper($accessory->model);
            $accessory->serial = strtoupper($accessory->serial);
            $accessory = $accessory->load('teamSettings');
            return response()->json(['accessory'=>$accessory]);
        }
        return null;
    }
}

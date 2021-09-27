<?php
/**
 * Building Controller
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

use App\Models\Building;
use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
use PragmaRX\Countries\Package\Countries;

/**
 *  Building Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BuildingController extends Controller
{
    /**
     * Index
     *
     * @param Request $request name
     * @param Team    $company name
     *
     * @return void
     */
    public function index(Request $request, Team $company)
    {


        if ($request->ajax()) {
            $company = $company->load('buildings.address', 'buildings.apartments', 'buildings.dependencies');
            $buildings = $company->buildings;

            return datatables()->of($buildings)
                ->addIndexColumn()
                ->addColumn(
                    'address',
                    function ($building) {
                        $address = $building->address;

                        $my_address = '';
                        if ($address['suite']!=null) {
                            $my_address = $my_address.$address->suite.' - ';
                        }
                        if ($address->number!=null) {
                            $my_address = $my_address.$address->number.' '.$address->street.' '.$address->city.' '.$address->region.' '.$address->country;
                        }
                        if ($address->postcode!=null) {
                            $my_address = $my_address.' '.$address->postcode;
                        }

                        return $my_address;
                    }
                )
                ->addColumn(
                    'apart_count',
                    function ($building) {
                        return $building->apartments->count();
                    }
                )
                ->addColumn(
                    'depend_count',
                    function ($building) {
                        return $building->dependencies->count();
                    }
                )
                ->addcolumn(
                    'tenant_count',
                    function () {
                        return "0";
                    }
                )
                ->addColumn(
                    'action',
                    function ($building) use ($company) {
                        $btn = '<nobr>';

                        if (LaratrustFacade::isAbleTo('building-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editBuildingButton" type="button" title="Edit Building" value="'.$building->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        //<i class="fas fa-map-pin"></i>

                        $btn = $btn.'<button class="btn btn-outline-primary mx-1 shadow btn-sm editAddressButton" title="Update address" type="button" value="'.$building->id.'"><i class="fas fa-map-marker-alt fa-fw"></i></button>';

                        if (LaratrustFacade::isAbleTo('dependency-read')) {
                            $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="Dependencies" href="'.route('company.building.show', ['company'=>$company, 'building'=>$building]).'"><i class="fas fa-warehouse fa-fw"></i></a>';
                        }

                        if (LaratrustFacade::isAbleTo('building-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteBuildingtButton" title="Delete client" type="button" value="'.$building->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('description')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();

        }
        $building = $company->buildings->first();
        //dd($company);
        return view('companies.buildings', ['company'=>$company, 'building'=>$building]);
    }

    /**
     * Store
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Response
     */
    public function store(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'building_id'=> ['nullable', 'numeric', 'exists:buildings,id'],
                    'lot'=>['required'],
                    'display_name'=> ['required', 'string', 'min:5', 'max:255'],
                    'description' => ['nullable', 'string', 'min:5', 'max:500']
                ]
            );

            $building = Building::updateOrCreate(
                ['id'=>$request->building_id, 'team_id'=>$company->id],
                [
                    'lot'=>$request->lot,
                    'display_name'=>$request->display_name,
                    'description'=>$request->description,
                ]
            );

            if ($building->address==null) {
                $building->address()->create(
                    [
                        'type'=>'primary'
                    ]
                );
            }

            return response()->json(['message'=>'Building '.$building->display_name.' saved successfully']);
        }
        return null;
    }

    /**
     * Show
     *
     * @param Team     $company  Company
     * @param Building $building Building
     *
     * @return void
     */
    public function show(Team $company, Building $building)
    {
        $dependency_types = TeamSetting::where('team_id', $company->id)->where('type', 'dependencie')->pluck('display_name', 'id');
        return view('companies.building-show', ['company'=>$company, 'building'=>$building, 'dependency_types'=>$dependency_types]);
    }

    /**
     * Edit
     *
     * @param Request  $request  Request
     * @param Team     $company  Company
     * @param Building $building Building
     *
     * @return void
     */
    public function edit(Request $request, Team $company, Building $building)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'building_id'=>['required', 'exists:buildings,id']
                ]
            );
            $new_building = Building::find($request->building_id);
            return response()->json(['message'=>'Building edited successfylly', 'building'=>$new_building]);
        }
    }

    /**
     * Update
     *
     * @param Request  $request  Request
     * @param Team     $company  Company
     * @param Building $building Building
     *
     * @return void
     */
    public function update(Request $request, Team $company, Building $building)
    {
        // code...
    }

    /**
     * Destroy
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return void
     */
    public function destroy(Request $request, Team $company)
    {
        // code...
    }

    /**
     * Get Address
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return void
     */
    public function getAddress(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'building_id'=>['required', 'numeric', 'exists:buildings,id']
                ]
            );
            $building = Building::find($request->building_id);
            $address = $building->address;

            if ($building->address->country==null) {
                $country_cca3 = "CAN";
            } else {
                $country_cca3=$building->address->country;
            }

            $cities = Countries::where('cca3', $country_cca3)->first()
                ->hydrate('cities')
                ->cities
                ->pluck('name', 'nameascii')
                ->toArray();

            foreach ($cities as $key => $value) {
                $cities[$key]=utf8_decode($value);
            }


            $all_countries = Countries::all()
                ->pluck('name.common', 'cca3')
                ->toArray();

            return response()->json(['address'=>$address, 'cities'=>$cities, 'countries'=>$all_countries]);
        }
    }
}

<?php
/**
 * Apartment Controller
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

use App\Models\Apartment;
use App\Models\Building;
use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
/**
 *  Building Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ApartmentController extends Controller
{
    /**
     * Index
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Illuminate\Http\Response
     */
    public function index(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $apartments = $company->apartments;
            $buildings = Building::where('team_id', $company->id)->get();

            return datatables()->of($apartments)
                ->addColumn(
                    'building_name',
                    function ($apartment) use ($buildings) {
                        return $buildings->where('id', $apartment->building_id)->first()->display_name;
                    }
                )
                ->addColumn(
                    'apartment_type',
                    function ($apartment) {
                        return $apartment->teamSettings->first()->display_name;
                    }
                )
                ->addColumn(
                    'action',
                    function ($apartment) use ($company) {
                        $btn = '<nobr>';

                        $btn = $btn.'<a class="btn btn-outline-primary btn-sm mx-1 shadow" type="button" title="More details" href="'.route('company.apartment.show', ['company'=>$company, 'apartment'=>$apartment]).'"><i class="fas fa-search fa-fw"></i></a>';

                        if (LaratrustFacade::isAbleTo('apartment-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editApartmentButton" type="button" title="Edit Apartment" value="'.$apartment->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        //<i class="fas fa-map-pin"></i>

                        if (LaratrustFacade::isAbleTo('apartment-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteApartmentButton" title="Delete apartment" type="button" value="'.$apartment->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('building_id')
                ->removeColumn('created_at')
                ->removeColumn('updated_at')
                ->make();
        }

        $apartment = $company->apartments->first();
        $buildings = $company->buildings->pluck('display_name', 'id');
        $apartment_types = TeamSetting::where('team_id', $company->id)->where('type', 'apartment')->pluck('display_name', 'id');
        $heating_of_dweelings = TeamSetting::where('team_id', $company->id)->where('type', 'heating_of_dweeling')->pluck('display_name', 'id');

        return view(
            'companies.apartments',
            [
                'company'=>$company,
                'apartment'=>$apartment,
                'buildings'=>$buildings,
                'apartment_types'=>$apartment_types,
                'heating_of_dweelings'=>$heating_of_dweelings
            ]
        );
    }

    /**
     * Store
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Illuminate\Http\Response
     */
    public function store(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'id'=>['nullable', 'numeric', 'exists:apartments,id'],
                    'building'=>['required', 'numeric', 'exists:buildings,id'],
                    'number'=>['required'],
                    'size'=>['required', 'numeric'],
                    'heating_of_dweeling'=>['required', 'numeric'],
                    'description'=>['nullable', 'string', 'min:5', 'max:255']
                ]
            );

            //updateOrCreate code
            $apartment = Apartment::updateOrCreate(
                ['id'=>$request->id],
                [
                    'building_id'=>$request->building,
                    'number'=>$request->number,
                    'description'=>$request->description
                ]
            );

            if ($apartment->teamSettings->isEmpty()) {
                $apartment->teamSettings()->attach($request->size);
                $apartment->teamSettings()->attach($request->heating_of_dweeling);
            } else {
                $apartment->teamSettings()->sync([$request->size, $request->heating_of_dweeling]);
            }

            return response()->json(['message'=>'Apartment saved successfully!']);
        }
        return null;
    }

    /**
     * Show
     *
     * @param Team $company   Company
     * @param int  $apartment Apartment
     *
     * @return void
     */
    public function show(Team $company, Apartment $apartment)
    {

        return view('companies.apartment-show', ['company'=>$company, 'apartment'=>$apartment]);
    }

    /**
     * Edit
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Illuminate\Http\Response
     */
    public function edit(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'apartment_id'=>['required', 'exists:apartments,id']
                ]
            );

            $apartment = Apartment::find($request->apartment_id);
            $apartment= $apartment->load('teamSettings');
            return response()->json(['apartment'=>$apartment]);
        }
        return null;
    }
}

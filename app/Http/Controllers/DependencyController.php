<?php
/**
 * Dependency Controller
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
use App\Models\Dependency;
use App\Models\Team;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
/**
 *  Dependency Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class DependencyController extends Controller
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
        //Code
    }

    /**
     * Store
     *
     * @param Request  $request  Request
     * @param Team     $company  Company
     * @param Building $building Building
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $company, Building $building)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'id' => ['nullable', 'numeric', 'exists:dependencies,id'],
                    'type' => ['required', 'numeric', 'exists:team_settings,id'],
                    'number' => ['required'],
                    'location' => ['required', 'string', 'min:3', 'max:255'],
                    'description'=> ['nullable', 'string', 'min:5', 'max:500']
                ]
            );

            $dependency = Dependency::updateOrCreate(
                ['id' => $request->id],
                [
                    'building_id'=> $building->id,
                    'number' => $request->number,
                    'description'=>$request->description,
                    'location' => $request->location,
                ]
            );

            if ($dependency->teamSettings->isEmpty()) {
                $dependency->teamSettings()->attach($request->type);
            } else {
                $dependency->teamSettings()->sync($request->type);
            }

            return response()->json(['message'=>'Dependency saved successfully!']);
        }

        return null;
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
                    'dependency_id'=>['required', 'exists:dependencies,id']
                ]
            );
            $dependency = Dependency::find($request->dependency_id);
            $dependency = $dependency->load('teamSettings');
            return response()->json(['dependency'=>$dependency]);
        }
        return null;
    }

    /**
     * Get Building Dependencies
     *
     * @param Request  $request  Request
     * @param Team     $company  Company
     * @param Building $building Building
     *
     * @return \Illuminate\Http\Response
     */
    public function getBuildingDependencies(Request $request, Team $company, Building $building)
    {
        if ($request->ajax()) {
            $dependencies = $building->dependencies;
            return datatables()->of($dependencies)
                ->addIndexColumn()
                ->addColumn(
                    'type',
                    function ($dependency) {
                        $type = $dependency->teamSettings->first();
                        return $type->display_name??'N/A';
                    }
                )
                ->addColumn(
                    'action',
                    function ($dependency) use ($company) {
                        $btn = '<nobr>';

                        if (LaratrustFacade::isAbleTo('dependency-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editDependencyButton" type="button" title="Edit Dependency" value="'.$dependency->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        //<i class="fas fa-map-pin"></i>

                        if (LaratrustFacade::isAbleTo('dependency-delete')) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteDependencyButton" title="Delete client" type="button" value="'.$dependency->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
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
    }
}

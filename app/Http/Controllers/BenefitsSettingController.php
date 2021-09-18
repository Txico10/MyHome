<?php
/**
 * Benefits Setting Controller
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

use App\Models\Team;
use App\Models\TeamSetting;
use Illuminate\Http\Request;
use Laratrust\LaratrustFacade;
use Illuminate\Support\Facades\Validator;
/**
 *  Benefits Setting Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class BenefitsSettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $benefits = TeamSetting::where('team_id', $company->id)
                ->where('type', 'benefit')->get();

            return datatables()->of($benefits)
                ->addIndexColumn()
                ->editColumn(
                    'updated_at',
                    function ($request) {
                        return $request->updated_at->format('d F Y');
                    }
                )
                ->addColumn(
                    'action',
                    function ($benefit) {
                        $btn = '<nobr>';
                        if (LaratrustFacade::isAbleTo('benefitsSetting-update')) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editBenefitButton" type="button" title="Edit benefit" value="'.$benefit->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }
                        if (LaratrustFacade::isAbleTo('benefitsSetting-delete') && $benefit->employeeContracts->isEmpty()) {
                            $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm deleteBenefitButton" title="Delete benefit" type="button" value="'.$benefit->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                        }
                        $btn=$btn.'</nobr>';
                        return $btn;
                    }
                )
                ->removeColumn('id')
                ->removeColumn('team_id')
                ->removeColumn('type')
                ->removeColumn('location')
                ->removeColumn('created_at')
                ->make();
        }
        return view('companies.settings.benefits-setting', ['company'=>$company]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Team $company)
    {
        Validator::extend(
            'without_spaces',
            function ($attr, $value) {
                return preg_match('/^\S*$/u', $value);
            }
        );
        $request->validate(
            [
                'benefit_id'=> ['nullable', 'numeric', 'exists:team_settings,id'],
                'benefit_name' => ['required', 'string', 'min:5', 'max:255', 'without_spaces'],
                'benefit_display_name' => ['required', 'string', 'min:5', 'max:255'],
                'benefit_description' => ['nullable', 'string', 'min:5', 'max:255'],

            ],
            [
                'benefit_name.without_spaces'=> 'Whitespace not allowed.'
            ]
        );

        $benefit = TeamSetting::updateOrCreate(
            ['id'=>$request->benefit_id, 'team_id'=>$company->id],
            [
                'type' => 'benefit',
                'name' => strtolower($request->benefit_name),
                'display_name' => $request->benefit_display_name,
                'description' => $request->benefit_description,
            ]
        );

        return redirect()->route('company.benefits-setting', ['company'=>$company])
            ->with('success', 'Benefit saved successfully');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $benefit = TeamSetting::find($request->benefit_id);
            return response()->json(['benefit'=>$benefit], 200);
        }
        return null;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, Team $company)
    {
        $benefit =TeamSetting::findOrFail($request->benefit_id);
        $benefit->delete();
        return response()->json(['message'=>'Benefit deleted successfully!']);
    }
}

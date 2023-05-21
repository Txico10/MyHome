<?php
/**
 * Apartment Accessory Controller
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
use App\Models\LeaseAccessory;
use App\Models\Team;
use Carbon\Carbon;
use Illuminate\Http\Request;
/**
 *  Apartment Accessory Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ApartmentAccessoryController extends Controller
{
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
                    'id_apartaccess'=>['required', 'exists:lease_accessory,id']
                ]
            );

            $apart_accessory = LeaseAccessory::findOrFail($request->id_apartaccess);
            $accessory = Accessory::findOrFail($apart_accessory->accessory_id);

            $data = collect(
                [
                    'id'=>$apart_accessory->id,
                    'manufacturer' => $accessory->manufacturer_model,
                    'serial' => $accessory->serial,
                    'assigned_at'=>$apart_accessory->assigned_at->format('Y-m-d'),
                    'removed_at'=>is_null($apart_accessory->removed_at)? null:$apart_accessory->removed_at->format('Y-m-d'),
                    'price' => $apart_accessory->price,
                    'description' => $apart_accessory->description
                ]
            );

            return response()->json(['message'=>'Success', 'accessory'=>$data]);
        }

        return null;
    }

    /**
     * Update
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return Illuminate\Http\Response
     */
    public function update(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'id'=>['required', 'exists:lease_accessory,id'],
                    'assigned_at' => ['required', 'date'],
                    'removed_at'=> ['nullable', 'date', 'after_or_equal:assigned_at'],
                    'price'=> ['required', 'numeric', 'min:0'],
                    'description' => ['nullable', 'string', 'min:3', 'max:255']
                ]
            );

            $apartAccess = LeaseAccessory::find($request->id);

            $apartAccess->price = $request->price;

            $apartAccess->description = $request->description;

            if (!is_null($request->removed_at)) {
                $apartAccess->removed_at = $request->removed_at;
            }

            $apartAccess->save();

            return response()->json(['message'=>'Success']);
        }
        return null;
    }

    /**
     * Remove Accessory
     *
     * @param Request $request Request
     * @param Team    $company Company
     *
     * @return void
     */
    public function removeAccessory(Request $request, Team $company)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'id_apartaccess'=>['required', 'exists:lease_accessory,id']
                ]
            );

            $to_be_removed = LeaseAccessory::find($request->id_apartaccess);
            $to_be_removed->removed_at=Carbon::now();
            $to_be_removed->save();

            return response()->json(['message'=>'Accessory removed successfully!', 'success'=>true]);
        }
    }
}

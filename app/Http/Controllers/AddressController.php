<?php
/**
 * Address Controller
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

use App\Models\Address;
use Illuminate\Http\Request;
use PragmaRX\Countries\Package\Countries;
/**
 *  Address Controller class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class AddressController extends Controller
{
    /**
     * Update
     *
     * @param Request $request Request
     * @param int     $id      Address id
     *
     * @return void
     */
    public function update(Request $request, int $id)
    {
        if ($request->ajax()) {
            $request->validate(
                [
                    'number'   => ['required', 'numeric'],
                    'street'   => ['required', 'string', 'min:5', 'max:255'],
                    'city'     => ['required', 'string', 'min:5', 'max:50'],
                    'region'   => ['required', 'string', 'min:5', 'max:50'],
                    'country'  => ['required', 'string', 'size:3'],
                    'postcode' => ['required', 'string', 'min:5', 'max:10'],
                ]
            );

            $address = Address::findOrFail($id);

            $address->number = $request->number;
            $address->street = $request->street;
            $address->city = $request->city;
            $address->region = $request->region;
            $address->country = $request->country;
            $address->postcode = $request->postcode;

            $address->save();

            return response()->json(['message'=>'Address updated successfully!']);
        }

        return null;
    }
    /**
     * Get Cities
     *
     * @param Request $request Request
     *
     * @return void
     */
    public function getCities(Request $request)
    {
        if ($request->ajax()) {
            $cities = Countries::where('cca3', $request->address_country)->first()
                ->hydrate('cities')
                ->cities
                ->pluck('name', 'nameascii')
                ->toArray();

            if (!empty($cities)) {
                foreach ($cities as $key => $value) {
                    $cities[$key]=utf8_decode($value);
                }
            }

            return response()->json(['cities'=>$cities]);
        }
        return null;
    }

    /**
     * Get Region
     *
     * @param mixed $request Request
     *
     * @return void
     */
    public function getRegion(Request $request)
    {
        if ($request->ajax()) {
            $myRegion =  Countries::where('cca3', $request->address_country)->first()
                ->hydrateCities()
                ->cities
                ->where('nameascii', $request->address_city)
                ->first()
                ->adm1name;

            if ($myRegion != null) {
                $myRegion = utf8_decode($myRegion);
            }

            return response()->json(['address_region'=>$myRegion]);
        }
        return null;
    }
}

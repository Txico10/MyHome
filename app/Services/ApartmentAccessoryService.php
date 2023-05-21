<?php
/**
 * Apartment Accessory Service
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Services;

use App\Models\Apartment;
use App\Models\Team;
use Illuminate\Support\Str;
use Laratrust\LaratrustFacade;

/**
 *  Apartment Accessory Service class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ApartmentAccessoryService
{
    /**
     * Accessory Table
     *
     * @param Team      $company   Company
     * @param Apartment $apartment Apartment
     *
     * @return void
     */
    public function accessoryTable(Team $company, Apartment $apartment)
    {
        $accessories = $apartment->leases;

        if ($accessories->count()) {
            $accessories = $apartment->leases->last()->accessories;
        }
        return datatables()->of($accessories)
            ->editColumn(
                'manufacturer',
                function ($accessory) {
                    return $accessory->manufacturer_model;
                }
            )
            ->editColumn(
                'discontinued_at',
                function ($accessory) {
                    return is_null($accessory->discontinued_at)?'NO':'YES';
                }
            )
            ->addColumn(
                'assigned_at',
                function ($accessory) {
                    return !is_null($accessory->pivot->assigned_at)?$accessory->pivot->assigned_at->format('Y-m-d'):'N/A';
                }
            )
            ->addColumn(
                'removed_at',
                function ($accessory) {
                    return !is_null($accessory->pivot->removed_at)?$accessory->pivot->removed_at->format('Y-m-d'):'N/A';
                }
            )
            ->addColumn(
                'price',
                function ($accessory) {
                    return $accessory->pivot->price;
                }
            )
            ->addColumn(
                'description',
                function ($accessory) {
                    return !is_null($accessory->pivot->description)? Str::limit($accessory->pivot->description, 50, '...'):'N/A';
                }
            )
            ->addColumn(
                'type',
                function ($accessory) {
                    return $accessory->teamSettings->first()->display_name;
                }
            )
            ->addColumn(
                'action',
                function ($accessory) use ($apartment) {
                    //$payments = $bill->payments;
                    //$btn_validation = $payments->isEmpty();
                    $btn = '<nobr>';
                    if (is_null($accessory->pivot->removed_at) && LaratrustFacade::isAbleTo('lease-update')) {
                        if ($apartment->leases->last()->isActive()) {
                            $btn = $btn.'<button class="btn btn-outline-secondary mx-1 shadow btn-sm editApartAccessButton" type="button" title="Edit Accessory" value="'.$accessory->pivot->id.'"><i class="fas fa-pencil-alt fa-fw"></i></button>';
                        }

                        $btn = $btn.'<button class="btn btn-outline-danger mx-1 shadow btn-sm removeApartAccessButton" title="Remove Accessory" type="button" value="'.$accessory->pivot->id.'"><i class="fas fa-trash-alt fa-fw"></i></button>';
                    }
                    $btn=$btn.'</nobr>';
                    return $btn;
                }
            )
            ->removeColumn('id')
            ->removeColumn('team_id')
            ->removeColumn('model')
            ->removeColumn('buy_at')
            ->removeColumn('qrcode')
            ->removeColumn('updated_at')
            ->removeColumn('pivot')
            ->make();
    }
}

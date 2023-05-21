<?php
/**
 * Building dependencies
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
/**
 *  Dependency model class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Dependency extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    protected $fillable = [
        'building_id','number', 'description', 'location'
    ];

    protected static $logName = 'dependency_log';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    /**
     * Get Description For Event Log
     *
     * @param mixed $eventName Event
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Building dependency has been {$eventName}";
    }

    /**
     * Building
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function building()
    {
        return $this->belongsTo(Building::class);
    }

    /**
     * Team Settings
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function teamSettings()
    {
        return $this->morphToMany(TeamSetting::class, 'settingable')
            ->using(ConfigurationSetting::class)
            ->withPivot('description')
            ->withTimestamps();
    }

    /**
     * Leases
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function leases()
    {
        return $this->belongsToMany(Lease::class, 'lease_dependency')
            ->withPivot('assigned_at', 'removed_at', 'price', 'description')
            ->withTimestamps();
    }

    /**
     * Invoice
     *
     * @return void
     */
    public function invoices()
    {
        return $this->morphToMany(Bill::class, 'billable')
            ->using(Invoice::class)
            ->withPivot(['amount', 'description', 'oparation'])
            ->withTimestamps();
    }//
}

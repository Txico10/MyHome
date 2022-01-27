<?php
/**
 * Apartment Model
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

use Attribute;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
/**
 *  Apartment model class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Apartment extends Model
{
    use HasFactory, LogsActivity, CausesActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['building_id', 'number', 'description'];

    protected static $logName = 'apartment_log';
    protected static $logFillable = true;
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    /*
    protected $appends = [
        'active_lease'
    ];
    */
    /**
     * Get Description For Event Log
     *
     * @param mixed $eventName Event
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "Apartment has been {$eventName}";
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
        return $this->hasMany(Lease::class);
    }

    /**
     * Get Active Lease Attribute
     *
     * @return boolean

    public function getActiveLeaseAttribute()
    {
        $lease = $this->leases()->latest()->first();

        if (!empty($lease)) {
            if (!empty($lease->end_at)) {
                if ($lease->end_at->lessThanOrEqualTo(today())) {
                    return false;
                }
            }
            return true;
        }
        return false;
    }
    */
}

<?php
/**
 * Accessories Model
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
 *  Accessories model class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Accessory extends Model
{
    use HasFactory, LogsActivity, CausesActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'manufacturer',
        'model',
        'serial',
        'buy_at',
        'discontinued_at',
        'qrcode',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'buy_at' => 'datetime:Y-m-d',
        'discontinued_at' => 'datetime:Y-m-d',
    ];

    protected $appends = [
        'manufacturer_model'
    ];

    protected static $logName = 'accessories_log';
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
        return "Accessory has been {$eventName}";
    }

    /**
     * Team
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
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
        return $this->belongsToMany(Lease::class, 'lease_accessory')
            ->withPivot('assigned_at', 'removed_at', 'price', 'description')
            ->withTimestamps();
    }

    /**
     * Get Active Lease Attribute
     *
     * @return boolean
     */
    /*
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
    /**
     * GetManufacturerModelAttribute
     *
     * @return void
     */
    public function getManufacturerModelAttribute()
    {
        return $this->manufacturer."-".$this->model;
    }
}

<?php
/**
 * Building Model
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
 *  Building model class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Building extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['team_id', 'lot', 'display_name', 'ready_for_habitation', 'description'];

    protected static $logName = 'building_log';
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
        return "Building has been {$eventName}";
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
     * Address
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function address()
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Apartments
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function apartments()
    {
        return $this->hasMany(Apartment::class);
    }


    /**
     * Dependencies
     *
     * @return void
     */
    public function dependencies()
    {
        return $this->hasMany(Dependency::class);
    }

    /**
     * Leases
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function leases()
    {
        return $this->hasManyThrough(Lease::class, Apartment::class);
    }
}

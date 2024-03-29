<?php
/**
 * Lease Model
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
 *  Lease class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Lease extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'apartment_id',
        'residential_purpose',
        'residential_purpose_description',
        'co-ownership',
        'furniture_included',
        'term',
        'start_at',
        'end_at',
        'rent_amount',
        'rent_recurrence',
        'subsidy_program',
        'first_payment_at',
        'postdated_cheques',
        'by_law_given_on',
        'land_access',
        'land_access_description',
        'animals',
        'animals_description',
        'others',
        'end_sooner',
        'parent_id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'residential_purpose'=>'boolean',
        'co-ownership'=>'boolean',
        'furniture_included'=>'boolean',
        'subsidy_program'=>'boolean',
        'postdated_cheques'=>'boolean',
        'land_access'=>'boolean',
        'animals'=>'boolean',
        'start_at' => 'datetime:Y-m-d',
        'end_at' => 'datetime:Y-m-d',
        'first_payment_at' => 'datetime:Y-m-d',
        'by_law_given_on' => 'datetime:Y-m-d',
        'end_sooner' => 'datetime:Y-m-d',
    ];

    protected static $logName = 'lease_log';
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
        return "Lease has been {$eventName}";
    }

    /**
     * Users
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function users()
    {
        return $this->morphToMany(User::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('team_id', 'check_account_id')
            ->withTimestamps();
    }

    /**
     * Teams
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function teams()
    {
        return $this->morphToMany(Team::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('user_id', 'check_account_id')
            ->withTimestamps();
    }

    /**
     * CheckAccounts
     *
     * @return void
     */
    public function checkAccounts()
    {
        return $this->morphToMany(CheckAccount::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('user_id')
            ->withTimestamps();
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
     * Accessories
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function accessories()
    {
        return $this->belongsToMany(Accessory::class, LeaseAccessory::class)
            ->withPivot('id', 'assigned_at', 'removed_at', 'price', 'description')
            ->withTimestamps();
    }

    /**
     * Dependencies
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function dependencies()
    {
        return $this->belongsToMany(Dependency::class, 'lease_dependency')
            ->withPivot('price', 'description')
            ->withTimestamps();
    }

    /**
     * Apartments
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function apartment()
    {
        return $this->belongsTo(Apartment::class);
    }

    /**
     * Check account
     *
     * @return void
     */
    //public function checkaccount()
    //{
    //    return $this->hasOne(CheckAccount::class)->latestOfMany();
    //}

    /**
     * Invoices
     *
     * @return void
     */
    public function invoices()
    {
        return $this->morphToMany(Bill::class, 'billable')
            ->using(Invoice::class)
            ->withPivot(['amount', 'description', 'oparation'])
            ->withTimestamps();
    }

    /**
     * Is Active
     *
     * @return boolean
     */
    public function isActive():bool
    {
        if ($this->start_at->lessThanOrEqualTo('today') && (is_null($this->end_at)||$this->end_at->greaterThanOrEqualTo('today'))) {
            return true;
        }

        return false;
    }

}

<?php
/**
 * Team Model
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

use Laratrust\Models\LaratrustTeam;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
/**
 *  Laratrust Team class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Team extends LaratrustTeam
{
    use HasFactory, LogsActivity, CausesActivity;

    //public $guarded = [];
    const VALIDATION_RULES = [
        'slug'=>['required', 'alpha_dash', 'unique:teams,slug'],
        'display_name' => ['required', 'string', 'min:5', 'max:255'],
        'bn' => ['required', 'numeric', 'unique:teams,bn'],
        'legalform' => ['required', 'string'],
        'description' => ['nullable', 'string', 'min:5', 'max:255'],
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'slug',
        'display_name',
        'bn',
        'legalform',
        'description',
        'logo',
    ];

    protected static $logName = 'company_log';
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
        return "Company has been {$eventName}";
    }

    /**
     * Addresses
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Contacts
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }



    /**
     * Team users
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'user', 'role_user')
            ->withPivot('role_id');
    }

    /**
     * Users Check Account
     *
     * @return void
     */
    public function checkAccounts()
    {
        return $this->hasMany(CheckAccount::class);
    }

    /**
     * Users Profile
     *
     * @param int $role_id Role
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function usersProfile(int $role_id)
    {
        return $this->morphedByMany(User::class, 'user', 'role_user')
            ->wherePivot('role_id', $role_id);
    }


    /**
     * Employee contracts
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function contracts()
    {
        return $this->morphedByMany(EmployeeContract::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('user_id')
            ->withTimestamps();
    }

    /**
     * Employee contracts
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function leases()
    {
        return $this->morphedByMany(Lease::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('user_id')
            ->withTimestamps();
    }

    /**
     * Company Settings
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function settings()
    {
        return $this->hasMany(TeamSetting::class);
    }

    /**
     * Contract Setting
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function contractsSetting()
    {
        return $this->hasMany(ContractSetting::class);
    }

    /**
     * Buildings
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function buildings()
    {
        return $this->hasMany(Building::class);
    }

    /**
     * Apartments
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function apartments()
    {
        return $this->hasManyThrough(Apartment::class, Building::class);
    }

    /**
     * Dependencies
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function dependencies()
    {
        return $this->hasManyThrough(Dependency::class, Building::class);
    }

    /**
     * Accessories
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function accessories()
    {
        return $this->hasMany(Accessory::class);
    }

    /**
     * Check Account
     *
     * @return void
     */
    public function checkAccount()
    {
        return $this->hasMany(CheckAccount::class);
    }

    /**
     * Bills
     *
     * @return void
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

}

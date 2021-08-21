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
     * @return Illuminate\Database\Eloquent\Model
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Contacts
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Team users
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'user', 'role_user')
            ->withPivot(['role_id']);
    }

    /**
     * Employee contracts
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function employeeContracts()
    {
        return $this->morphedByMany(EmployeeContract::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('user_id')
            ->withTimestamps();
    }

}

<?php
/**
 * Employee contract Model
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
 *  Employee contract model class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeeContract extends Model
{
    use HasFactory, LogsActivity, CausesActivity;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code',
        'role_id',
        'start_at',
        'end_at',
        'acceptance_at',
        'salary_term',
        'salary_amount',
        'availability',
        'min_week_time',
        'max_week_time',
        'benefits',
        'agreement',
        'agreement_status',
        'termination_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_at'=>'date:Y-m-d',
        'end_at' => 'date:Y-m-d',
        'acceptance_at'=> 'date:Y-m-d',
        'termination_at'=> 'date:Y-m-d',
    ];

    protected static $logName = 'employee_contract_log';
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
        return "Employee contract has been {$eventName}";
    }

    /**
     * Users
     *
     * @return Illuminate\Database\Eloquent\Model
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
     * @return Illuminate\Database\Eloquent\Model
     */
    public function teams()
    {
        return $this->morphToMany(Team::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('user_id')
            ->withTimestamps();
    }

    /**
     * Check accounts
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
     * @return Illuminate\Database\Eloquent\Model
     */
    public function teamSettings()
    {
        return $this->morphToMany(TeamSetting::class, 'settingable')
            ->using(ConfigurationSetting::class)
            ->withPivot('description')
            ->withTimestamps();
    }

}

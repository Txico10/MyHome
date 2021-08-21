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
 *  Users class
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
        'role_id',
        'start_at',
        'end_at',
        'salary_term',
        'salary_amount',
        'availability',
        'min_week_time',
        'agreement',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_at'=>'date:Y-m-d',
        'end_at' => 'date:Y-m-d',
    ];

    protected static $logName = 'emloyee_contract_log';
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
            ->withPivot('team_id')
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
}

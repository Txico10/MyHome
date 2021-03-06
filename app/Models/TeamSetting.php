<?php
/**
 * Team Setting Model
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
 *  Team Setting model class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class TeamSetting extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    protected $fillable = [
        'team_id','type', 'name', 'display_name','description',
    ];

    protected static $logName = 'company_setting_log';
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
        return "Company setting has been {$eventName}";
    }

    /**
     * Team
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Employee Contract Settings
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function employeeContracts()
    {
        return $this->morphedByMany(EmployeeContract::class, 'settingable')
            ->using(ConfigurationSetting::class)
            ->withPivot('description')
            ->withTimestamps();
    }

    /**
     * Apartments
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function apartments()
    {
        return $this->morphedByMany(Apartment::class, 'settingable')
            ->using(ConfigurationSetting::class)
            ->withPivot('description')
            ->withTimestamps();
    }

    /**
     * Dependencies
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function dependencies()
    {
        return $this->morphedByMany(Dependency::class, 'settingable')
            ->using(ConfigurationSetting::class)
            ->withPivot('description')
            ->withTimestamps();
    }

    /**
     * Appliances
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function accessories()
    {
        return $this->morphedByMany(Accessory::class, 'settingable')
            ->using(ConfigurationSetting::class)
            ->withPivot('description')
            ->withTimestamps();
    }

}

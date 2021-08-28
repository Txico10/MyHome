<?php
/**
 * Role Model
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

use Laratrust\Models\LaratrustRole;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
/**
 *  Laratrust Role class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Role extends LaratrustRole
{
    use LogsActivity, CausesActivity;

    public $guarded = [];

    protected static $logAttributes = [
        'name','display_name', 'description',
    ];

    protected static $logName = 'role_log';
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;

    /**
     * Get Description For Log Event
     *
     * @param mixed $eventName The name of event
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This role has been {$eventName}";
    }

    /**
     * Users
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'user', 'role_user')
            ->withPivot('team_id');
    }
}

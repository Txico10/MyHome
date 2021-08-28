<?php
/**
 * Permission Model
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

use Laratrust\Models\LaratrustPermission;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;
/**
 *  Laratrust Permission class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Permission extends LaratrustPermission
{
    use LogsActivity, CausesActivity;

    public $guarded = [];

    protected static $logAttributes = [
        'name','display_name', 'description',
    ];

    protected static $logName = 'permission_log';
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
        return "This permission has been {$eventName}";
    }

    /**
     * Users
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'user', 'permission_user')
            ->withPivot('team_id');
    }
}

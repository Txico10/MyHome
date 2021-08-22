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
    public $guarded = [];

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

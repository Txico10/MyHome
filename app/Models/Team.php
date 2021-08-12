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
    use HasFactory;

    public $guarded = [];

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
        return $this->morphedByMany(User::class, 'role_user');
    }
}

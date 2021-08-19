<?php
/**
 * Users Model
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
/**
 *  Users class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Login extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'ip_address'];

    /**
     * Users
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function users()
    {
        return $this->belongsTo(User::class);
    }
}

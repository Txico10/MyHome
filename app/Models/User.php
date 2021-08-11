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

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laratrust\Traits\LaratrustUserTrait;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Validation\Rule;

/**
 *  Users class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class User extends Authenticatable implements MustVerifyEmail
{
    use LaratrustUserTrait;
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    /**
     * Basic validation rules to be used in request
     *
     * @var array
     */
    public const VALIDATION_RULES = [
        'name' => ['required', 'string', 'min:5', 'max:191'],
        'email' => ['required', 'email:rfc,dns', 'unique:users,email'],
        'birthdate' => ['required', 'date', 'before:today'],
        'gender' => ['required'],
        'ssn' => ['required', 'numeric','unique:users,ssn'],
        'password' => ['required', 'string', 'min:6']
    ];

    /**
     * Adminlte image
     *
     * @return void
     */
    public function adminlte_image()
    {
        if ($this->photo == null) {
            return 'https://picsum.photos/300/300';
        } else {
            return asset('storage/images/profile/users/'.$this->photo);
        }

    }

    /**
     * Adminlte desc
     *
     * @return void
     */
    public function adminlte_desc()
    {
        return 'That\'s a nice guy';
    }

    /**
     * Adminlte profile url
     *
     * @return void
     */
    public function adminlte_profile_url()
    {

        return route('user.profile', ['user'=>$this]);;
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

}

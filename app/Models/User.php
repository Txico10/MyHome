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
class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable, LaratrustUserTrait, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'birthdate',
        'gender',
        'ssn',
        'status',
        'photo',
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
        'birthdate' => 'date:Y-m-d',
        'email_verified_at' => 'datetime',
    ];

    /**
     * Atributes to be logged
     *
     * @var array
     */
    protected static $logAttributes = [
        'name','email','birthdate','gender','ssn',
    ];

    /**
     * Log name
     *
     * @var string
     */
    protected static $logName = 'user_log';

    /**
     * Log only changed attributes
     * Prevent empty logs
     */
    protected static $logOnlyDirty = true;
    protected static $submitEmptyLogs = false;
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
            $gravatarEmail = md5(strtolower(trim($this->email)));
            return 'https://www.gravatar.com/avatar/'.$gravatarEmail.'?s=200';

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
     * Get Description For Log Event
     *
     * @param mixed $eventName The name of event
     *
     * @return string
     */
    public function getDescriptionForEvent(string $eventName): string
    {
        return "This user has been {$eventName}";
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
     * Logins
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    public function logins()
    {
        return $this->hasMany(Login::class);
    }

    /**
     * Scope With Last Login Date
     *
     * @param mixed $query Query
     *
     * @return void
     */
    public function scopeWithLastLoginDate($query)
    {
        $query->addSelect(
            [
                'last_login_at' => Login::select('created_at')
                    ->whereColumn('user_id', 'users.id')
                    ->latest()
                    ->take(1)
            ]
        )->withCasts(['last_login_at' => 'datetime']);
    }

}

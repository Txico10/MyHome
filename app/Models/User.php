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
        'active',
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
        'active'=>'boolean',
        'birthdate' => 'date:Y-m-d',
        'email_verified_at' => 'datetime',
    ];

    protected $appends = [
        'active_company'
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
        'password' => ['required', 'min:8', 'max:15']
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
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Contacts
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function contacts()
    {
        return $this->morphMany(Contact::class, 'contactable');
    }

    /**
     * Logins
     *
     * @return \Illuminate\Database\Eloquent\Model
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

    /**
     * Employees
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function employees()
    {
        return $this->morphedByMany(EmployeeContract::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('team_id')
            ->withTimestamps();
    }

    /**
     * Contract Company
     *
     * @param mixed $company_id Company ID
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function contractCompany($company_id)
    {
        return $this->morphedByMany(EmployeeContract::class, 'engageable')
            ->using(Contract::class)
            ->wherePivot('team_id', $company_id)
            ->latest()
            ->get();
    }

    /**
     * Companies
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function companies()
    {
        return $this->morphToMany(Team::class, 'user', 'role_user')
            ->withPivot('role_id');
    }

    /**
     * Check Accounts
     *
     * @return void
     */
    public function checkAccounts()
    {
        return $this->hasMany(CheckAccount::class);
    }

    /**
     * Leases
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function leases()
    {
        return $this->morphedByMany(Lease::class, 'engageable')
            ->using(Contract::class)
            ->withPivot('team_id')
            ->withTimestamps();
    }

    /**
     * Active Companies
     *
     * @return array Companies id
     */
    public function getActiveCompanyAttribute()
    {
        if ($this->employees->isNotEmpty()) {
            $company = $this->employees
                ->filter(
                    function ($employee) {
                        if ($employee->start_at <= now() && ($employee->end_at == null || $employee->end_at >= now()) && (strcmp($employee->agreement_status, 'accepted')==0)) {
                            return true;
                        }
                    }
                )->first();
            return $company==null ? null: $company->pivot->team_id;
        } else {
            if ($this->leases->isNotEmpty()) {
                $lease = $this->leases
                    ->filter(
                        function ($lease) {
                            if (strcmp($lease->term, "fixed")==0 && $lease->end_at->greaterThan(today())) {
                                return true;
                            } else {
                                if (strcmp($lease->term, "indeterminate")==0 && $lease->end_at==null) {
                                    return true;
                                } else {
                                    return false;
                                }

                            }
                        }
                    )->first();
                return $lease==null?null:$lease->pivot->team_id;
            } else {
                return null;
            }
        }
    }

}

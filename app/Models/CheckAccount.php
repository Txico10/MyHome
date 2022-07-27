<?php
/**
 * Check Account Model
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
 *  Check Account class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class CheckAccount extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'lease_id',
        'description'
    ];

    protected static $logName = 'checkaccount_log';
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
        return "Check account has been {$eventName}";
    }

    /**
     * Lease
     *
     * @return void
     */
    public function lease()
    {
        return $this->belongsTo(Lease::class);
    }

    /**
     * Bills
     *
     * @return void
     */
    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    /**
     * Bill Lines
     *
     * @return void
     */
    public function billLines()
    {
        return $this->hasManyThrough(BillLine::class, Bill::class);
    }
}

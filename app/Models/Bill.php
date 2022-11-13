<?php
/**
 * Bill Model
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

use Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;

/**
 *  Bill class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Bill extends Model
{
    use HasFactory, LogsActivity, CausesActivity;

    protected $casts = [
        'period_from'=>'datetime:Y-m-d',
        'period_to'=>'datetime:Y-m-d',
        'payment_due_date'=>'datetime:Y-m-d',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'team_id',
        'number',
        'period_begin',
        'period_end',
        'status',
        'payment_due_date',
        'total_amount',
        'description',
    ];

    protected static $logName = 'bill_log';
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
        return "The bill has been {$eventName}";
    }

    /**
     * ChackAccount
     *
     * @return void
     */
    public function company()
    {
        return $this->belongsTo(Team::class);
    }

    /**
     * Bill Lines
     *
     * @return void
     */
    public function invoiceLease()
    {
        return $this->morphedByMany(Lease::class, 'billable')
            ->using(Invoice::class)
            ->withPivot(['amount', 'description', 'oparation'])
            ->withTimestamps();
    }

    /**
     * InvoiceDependencie
     *
     * @return void
     */
    public function invoiceDependencie()
    {
        return $this->morphedByMany(Dependency::class, 'billable')
            ->using(Invoice::class)
            ->withPivot(['amount', 'description', 'oparation'])
            ->withTimestamps();
    }

    /**
     * InvoiceAccessory
     *
     * @return void
     */
    public function invoiceAccessory()
    {
        return $this->morphedByMany(Accessory::class, 'billable')
            ->using(Invoice::class)
            ->withPivot(['amount', 'description', 'oparation'])
            ->withTimestamps();
    }


    /**
     * Payment
     *
     * @return void
     */
    public function payment()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check Accounts
     *
     * @return void
     */
    public function checkAccounts()
    {
        return $this->morphToMany(CheckAccount::class, 'checkable')
            ->withTimestamps();
    }

    /**
     * GetNumberAttribute
     *
     * @return void
     */
    public function getNumberAttribute($value)
    {
        $bill_number = (string)$value;
        $size = strlen($bill_number);
        $max_size = 6;
        $blanc ='';
        if ($max_size >=$size) {
            for ($i=0; $i<6-$size; $i++) {
                $blanc.='0';
            }
        }
        $blanc = $blanc.$bill_number;
        return $blanc;
    }
}

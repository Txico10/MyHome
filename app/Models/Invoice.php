<?php
/**
 * Invoice Pivot Model
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

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\MorphPivot;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\Traits\CausesActivity;

/**
 *  Contract class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Invoice extends MorphPivot
{
    use LogsActivity, CausesActivity;

    protected $table = 'billables';

    protected static $logName = 'invoice_log';
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
        return "Invoice has been {$eventName}";
    }

}

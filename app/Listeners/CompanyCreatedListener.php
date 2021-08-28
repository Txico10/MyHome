<?php
/**
 * Company created Listener
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Listeners;

use App\Events\CompanyCreated;
use App\Models\Team;
use App\Models\User;
use App\Notifications\CompanyCreatedNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

/**
 *  Company created listener class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class CompanyCreatedListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param CompanyCreated $event Event
     *
     * @return void
     */
    public function handle(CompanyCreated $event)
    {
        $superadmin = User::whereRoleIs('superadministrator')->get();
        $company = Team::where('id', $event->company->id)->first();
        $owner = User::whereRoleIs('owner', $company)->get();
        Notification::send($superadmin, new CompanyCreatedNotification($company));
        Notification::send($owner, new CompanyCreatedNotification($company));
    }
}

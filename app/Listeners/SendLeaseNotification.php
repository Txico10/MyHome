<?php
/**
 * Send Lease created Listener
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

use App\Events\LeaseCreated;
use App\Models\Role;
use App\Notifications\NewLease;
use App\Notifications\NewLessee;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Notification;

/**
 *  Send Lease Notification listener class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class SendLeaseNotification implements ShouldQueue
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
     * @param \App\Events\LeaseCreated $event event
     *
     * @return void
     */
    public function handle(LeaseCreated $event)
    {
        $users = $event->lease->users;
        $company = $event->lease->teams->first();
        $janitor_role = Role::where('name', 'janitor')->first()->id;
        $janitor = $company->usersProfile($janitor_role)->first();
        $janitor = $janitor->load('contacts');
        $filename = "BL".$event->lease->start_at->format('mY').$event->lease->id.$company->id;
        $data = [
            'company' => $company,
            'lease'=>$event->lease,
            'janitor'=>$janitor,
            'lease_status' => "Active",
        ];
        $path = storage_path('app/public/reports/pdf');
        $pdf = PDF::loadView('companies.dompdf.lease-report', $data)->setPaper('letter')->save($path.'/'.$filename.'.pdf');
        foreach ($users as $user) {
            Notification::send($user, new NewLease($company, $path, $filename));
            Notification::send($user, new NewLessee());
        }
    }
}

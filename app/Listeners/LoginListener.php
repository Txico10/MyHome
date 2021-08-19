<?php
/**
 * Login listener
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

use App\Models\Login;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Validation\ValidationException;

/**
 *  LoginListener class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class LoginListener
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
     * @param object $event Event
     *
     * @return void
     */
    public function handle($event)
    {
        if ($event->user->status==0) {
            Auth::logout();

            request()->session()->invalidate();
            request()->session()->regenerateToken();

            throw ValidationException::withMessages(
                [
                    'email' => "This account is blocked. Please contact the system administrator!",
                ]
            );

            return redirect()->back();
        }

        Login::create(
            [
                'user_id' => $event->user->id,
                'ip_address' => request()->getClientIp(),
            ]
        );
    }
}

<?php
/**
 * New Lessee Notification
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
/**
 *  New Lessee Notification class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class NewLessee extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable User
     *
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $notifiable User
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('stefanmonteiro@gmail.com.com', 'Real Estate System')
            ->subject('New lessee account')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your user account as been created. Click on the link below to activate your account.')
            ->action('Sign in on profil', route('user.profile', ['user'=>$notifiable]))
            ->line('Thank you for using our application!');
    }

    /**
     * Store notification into Database
     *
     * @param mixed $notifiable Notifiable
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'icon' => 'fas fa-fw fa-user',
            'subject'=> 'lessee',
            'title' => 'System administrator',
            'text' => 'Your user account as been created.',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable User
     *
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}

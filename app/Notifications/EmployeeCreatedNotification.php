<?php
/**
 * Employee Created Notification
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

use App\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
/**
 *  Employee Created Notification class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EmployeeCreatedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $company;

    /**
     * Create a new notification instance.
     *
     * @param Team $company Company
     *
     * @return void
     */
    public function __construct(Team $company)
    {
        $this->company = $company;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable Notifiable
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
     * @param mixed $notifiable Notifiable
     *
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('noreply@realestateis.com', 'Real Estate System')
            ->subject('New employee account')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Welcome to '.$this->company->display_name.'.')
            ->line('A new contract is available for you to sign.')
            ->line('To access your account use the following credentials:')
            ->line('Email: '.$notifiable->email)
            ->line('For your password contact the manager or reset your password')
            ->action('Sign in to your profil', route('user.profile', ['user'=>$notifiable]))
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
            'icon' => 'fas fa-fw fa-file-contract',
            'title' => 'New Contract',
            'text' => 'Welcome to '.$this->company->display_name.'. A new contract is available one your profile. To activate your account please sign it.',
        ];
    }

    /**
     * Get the array representation of the notification.
     *
     * @param mixed $notifiable Notifiable
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

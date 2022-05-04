<?php
/**
 * New Lease Notification
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
 *  New Lease Notification class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class NewLease extends Notification
{
    use Queueable;

    public $company;
    public $path;
    public $filename;

    /**
     * Create a new notification instance.
     *
     * @param mixed $company  Company
     * @param mixed $path     File path
     * @param mixed $filename File name
     *
     * @return void
     */
    public function __construct($company, $path, $filename)
    {
        $this->company = $company;
        $this->path = $path;
        $this->filename = $filename;
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
            ->subject('New lease')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your new lease '.$this->filename.' as been registrad with '.$this->company->display_name.'.')
            ->line('The lease is in attachment.')
            ->line('Thank you!')
            ->attach(
                $this->path.'/'.$this->filename.'.pdf',
                [
                    'as' => 'Lease.pdf',
                    'mime' => 'application/pdf',
                ]
            );
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
            'icon' => 'fas fa-fw fa-file-pdf',
            'subject'=> 'lease',
            'title' => $this->company->display_name,
            'text' => 'Welcome to '.$this->company->display_name.'. A new lease as been issued and available for download.',
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

        ];
    }
}

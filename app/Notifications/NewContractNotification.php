<?php
/**
 * New contract Notification
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

use App\Models\EmployeeContract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
/**
 *  New Contract Notification class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class NewContractNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Contract variable
     *
     * @var mixed $contract
     */
    public $contract;

    /**
     * Create a new notification instance.
     *
     * @param EmployeeContract $contract Contract
     *
     * @return void
     */
    public function __construct(EmployeeContract $contract)
    {
        $this->contract = $contract;
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
            ->from('noreply@realestateis.com', 'Real Estate System')
            ->subject('New contract available')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('A new contract is available for you.')
            ->line('To access your contract go to your account profile!')
            ->line('Please sign it before '.$this->contract->start_at.'.')
            ->action('My profile', route('user.profile', ['user'=>$notifiable]))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of database notification.
     *
     * @param mixed $notifiable User
     *
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'icon' => 'fas fa-fw fa-file-contract',
            'subject'=> 'contract',
            'title' => 'New Contract',
            'text' => 'A new contract is available for signature in your user profile. The contract should be signed before'.$this->contract->start_at.'. For more information contact your superviser. Thank you',
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

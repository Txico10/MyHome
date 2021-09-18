<?php
/**
 * Contract Termination Notification
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
 *  Contract Termination Notification class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class ContractTerminationNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Contract
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
            ->subject('Contract termination')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Your '.$this->contract->availability.' contract as been terminated on '.$this->contract->termination_at->format('d F Y').'.')
            ->line('For more information contact your superviser.')
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation for database notification.
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
            'title' => 'Contract termination',
            'text' => 'Your '.$this->contract->availability.' contract as been terminated '.$this->contract->end_at->format('d F Y').'.For more information contact your superviser. Thank you',
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

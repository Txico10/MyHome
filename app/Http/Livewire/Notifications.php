<?php
/**
 * NOtifications Livewire Component
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Http\Livewire;

use App\Models\User;
use Livewire\Component;
/**
 *  Contacts component class
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class Notifications extends Component
{
    public $user;

    protected $listeners = [
        'refresh'=>'$refresh',
        'deleteNotificationConfirm'=>'confirmDelete',
        'deleteNotification',
    ];
    /**
     * Mount
     *
     * @param User $user user
     *
     * @return void
     */
    public function mount(User $user)
    {
        $this->user = $user;
    }

    /**
     * Render
     *
     * @return void
     */
    public function render()
    {
        $notification_chunks = $this->user->notifications->sortByDesc('created_at')->chunkWhile(
            function ($value, $key, $chunk) {
                return $value->created_at === $chunk->last()->created_at;
            }
        );
        //dd($notification_chunks);
        return view(
            'livewire.notifications',
            [
                'notification_chunks' =>$notification_chunks
            ]
        );
    }

    /**
     * Read Notification
     *
     * @param mixed $notification Notification
     *
     * @return void
     */
    public function readNotification($notification)
    {
        $new_not = $this->user->notifications->where('id', $notification['id'])->first();
        $new_not->markAsRead();
        //dd($new_not);
    }

    /**
     * Confirm Delete
     *
     * @param mixed $notification Notification
     *
     * @return void
     */
    public function confirmDelete($notification)
    {
        $this->dispatchBrowserEvent(
            'swalNotification:confirm',
            [
                'icon'  => 'warning',
                'title' => 'Are you sure?',
                'text' => 'The notification will be deleted',
                'id' => $notification['id'],
            ]
        );
    }

    /**
     * Delete Notification
     *
     * @param mixed $id Notification
     *
     * @return void
     */
    public function deleteNotification($id)
    {
        $new_not = $this->user->notifications->where('id', $id)->first();
        $new_not->delete();

        $this->emitSelf('refresh');
        $this->dispatchBrowserEvent(
            'swalNotification::modal',
            [
                'icon'=>'success',
                'title' => 'notification deleted successfully',
                'text' => '',
            ]
        );
    }
}

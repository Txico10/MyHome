<?php
/**
 * Event Service Provider
 *
 * PHP version 7.4
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
namespace App\Providers;

use App\Listeners\LoginListener;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
/**
 *  EventServiceProvider
 *
 * @category MyCategory
 * @package  MyPackage
 * @author   Stefan Monteiro <stefanmonteiro@gmail.com>
 * @license  MIT treino.localhost
 * @link     link()
 * */
class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        Registered::class => [
            SendEmailVerificationNotification::class,
        ],
        Login::class => [
            LoginListener::class,
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        Event::listen(
            BuildingMenu::class, function (BuildingMenu $event) {
                // Add some items to the menu...
                $event->menu->add(
                    [
                        'header' => 'ADMINISTRATION',
                        'permission' => 'adminMenu-read'
                    ]
                );
                $event->menu->add(
                    [
                        'key' => 'authorization',
                        'text' => 'Administration',
                        'icon' => 'fas fa-fw fa-briefcase',
                        'permission' => 'adminMenu-read',
                        'submenu' => [
                            [
                                'key' => 'roles',
                                'text' => 'Roles',
                                'route'  => 'admin.roles',
                                'icon' => 'fas fa-fw fa-user-shield',
                                'permission' => 'roles-read',
                            ],
                            [
                                'key' => 'permissions',
                                'text' => 'Permissions',
                                'route'  => 'admin.permissions',
                                'icon' => 'fas fa-fw fa-user-tag',
                                'permission' => 'permissions-read',
                            ],
                            [
                                'key' => 'clients',
                                'text' => 'Clients',
                                'route'  => 'admin.clients',
                                'icon' => 'fas fa-fw fa-list',
                                'permission' => 'clients-read',
                            ]
                        ]
                    ],
                );
                $event->menu->add(
                    [
                        'key' => 'user',
                        'text' => 'Users',
                        'route'  => 'admin.users',
                        'icon' => 'fas fa-fw fa-users',
                        'permission' => 'users-read',
                        'label_color' => 'success',
                    ]
                );
            }
        );
    }
}

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

use App\Events\CompanyCreated;
use App\Listeners\CompanyCreatedListener;
use App\Listeners\LoginListener;
use App\Models\Team;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Event;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;
use Laratrust\LaratrustFacade;
use Illuminate\Support\Facades\Auth;

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
        CompanyCreated::class => [
            CompanyCreatedListener::class,
        ]
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
                                'key' => 'admin',
                                'text' => 'Admin',
                                'route'  => 'admin.index',
                                'icon' => 'fas fa-fw fa-tools',
                                'permission' => 'adminMenu-create',
                            ],
                            [
                                'key' => 'clients',
                                'text' => 'Clients',
                                'icon' => 'fas fa-fw fa-store',
                                'permission' => 'clients-read',
                                'submenu' => [
                                    [
                                        'key' => 'clients_list',
                                        'text' => 'Clients list',
                                        'route'  => 'admin.clients.index',
                                        'icon' => 'fas fa-fw fa-list',
                                        'permission' => 'clients-read',
                                    ],
                                    [
                                        'key' => 'new_clients',
                                        'text' => 'New Client',
                                        'route'  => 'admin.clients.create',
                                        'icon' => 'fas fa-fw fa-folder-plus',
                                        'permission' => 'clients-create',
                                    ],
                                ]
                            ],
                            [
                                'key' => 'permissions',
                                'text' => 'Permissions',
                                'route'  => 'admin.permissions',
                                'icon' => 'fas fa-fw fa-user-tag',
                                'permission' => 'permissions-read',
                            ],
                            [
                                'key' => 'roles',
                                'text' => 'Roles',
                                'route'  => 'admin.roles',
                                'icon' => 'fas fa-fw fa-user-shield',
                                'permission' => 'roles-read',
                            ],
                            [
                                'key' => 'user',
                                'text' => 'Users',
                                'route'  => 'admin.users',
                                'icon' => 'fas fa-fw fa-users',
                                'permission' => 'users-read',
                                'label'=>'10',
                                'label_color' => 'success',
                            ]
                        ]
                    ],
                );
                if (!LaratrustFacade::hasRole(['superadministrator'])) {
                    $company_id = Auth::user()->active_company;
                    if (!is_null($company_id)) {
                        $company = Team::findOrFail($company_id);
                        $event->menu->add(
                            [
                                'header' => 'COMPANY MANAGEMENT',
                                'permission' => 'companyMenu-read',
                            ]
                        );
                        $event->menu->add(
                            [
                                'key' => 'real_state',
                                'text' => 'My Company',
                                'route'  => ['company.show',['company' => $company]],
                                'icon' => 'fas fa-fw fa-info',
                                'permission' => 'company-read',
                            ],
                        );
                        $event->menu->add(
                            [
                                'key' => 'employees',
                                'text' => 'Employees',
                                'icon' => 'fas fa-fw fa-user-tie',
                                'permission' => 'employee-read',
                                'submenu' => [
                                    [
                                        'text' => 'All Employees',
                                        'route'  => ['company.employees', ['company' => $company]],
                                        'icon' => 'fas fa-fw fa-users',
                                        'permission' => 'employee-read',
                                    ],
                                    [
                                        'text' => 'New Employee',
                                        'route'  => ['company.employees.create', ['company' => $company]],
                                        'icon' => 'fas fa-fw fa-user-plus',
                                        'permission' => 'employee-create',
                                    ],
                                ]
                            ],
                        );
                    }

                }

            }
        );
    }
}

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
use App\Events\LeaseCreated;
use App\Listeners\CompanyCreatedListener;
use App\Listeners\LoginListener;
use App\Listeners\SendLeaseNotification;
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
        ],
        LeaseCreated::class => [
            SendLeaseNotification::class,
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
                    //$company_id = Auth::user()->active_company;
                    $company_id = session('companyID');

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
                        $event->menu->add(
                            [
                                'key' => 'leases',
                                'text' => 'Leases',
                                'icon' => 'fas fa-fw fa-handshake',
                                'permission' => 'leaseMenu-read',
                                'submenu' => [
                                    [
                                        'text' => 'All Leases',
                                        'route'  => ['company.leases', ['company' => $company]],
                                        'icon' => 'fas fa-fw fa-file-contract',
                                        'permission' => 'lease-read',
                                    ],
                                    [
                                        'text' => 'New Lease',
                                        'route'  => ['company.lease.create', ['company' => $company]],
                                        'icon' => 'fas fa-fw fa-folder-plus',
                                        'permission' => 'lease-create',
                                    ],
                                ]
                            ]
                        );
                        $event->menu->add(
                            [
                                'key' => 'buildings',
                                'text' => 'Buildings',
                                'route'  => ['company.buildings', ['company'=>$company]],
                                'icon' => 'fas fa-fw fa-building',
                                'permission' => 'building-read',
                            ]
                        );
                        $event->menu->add(
                            [
                                'key' => 'apartments',
                                'text' => 'Apartments',
                                'route'  => ['company.apartments', ['company'=>$company]],
                                'icon' => 'fas fa-fw fa-house-user',
                                'permission' => 'apartment-read',
                            ]
                        );
                        $event->menu->add(
                            [
                                'key' => 'accessories',
                                'text' => 'Accessories',
                                'route'  => ['company.accessories', ['company'=>$company]],
                                'icon' => 'fas fa-fw fa-chair',
                                'permission' => 'accessory-read',
                            ]
                        );
                        $event->menu->add(
                            [
                                'key' => 'company-settings',
                                'text' => 'Settings',
                                'icon' => 'fas fa-fw fa-cogs',
                                'permission' => 'settingsMenu-read',
                                'submenu' => [
                                    [
                                        'key' => 'accessoriesSetting',
                                        'text' => 'Accessories',
                                        'url'  => '#',
                                        'icon' => 'fas fa-fw fa-couch',
                                        'permission' => 'accessoriesSetting-read',
                                    ],
                                    [
                                        'key' => 'apartmentsSetting',
                                        'text' => 'Apartments',
                                        'url'  => '#',
                                        'icon' => 'fas fa-fw fa-home',
                                        'permission' => 'apartmentsSetting-read',
                                    ],
                                    [
                                        'key' => 'benefitsSetting',
                                        'text' => 'Benefits',
                                        'route'  => ['company.benefits-setting', ['company' => $company]],
                                        'icon' => 'fas fa-fw fa-notes-medical',
                                        'permission' => 'benefitsSetting-read',
                                    ],
                                    [
                                        'key' => 'contractsSetting',
                                        'text' => 'Contracts',
                                        'url'  => '#',
                                        'icon' => 'fas fa-fw fa-file-contract',
                                        'permission' => 'contractsSetting-read',
                                    ],
                                    [
                                        'key' => 'dependenciesSetting',
                                        'text' => 'Dependencies',
                                        'url'  => '#',
                                        'icon' => 'fas fa-fw fa-parking',
                                        'permission' => 'dependenciesSetting-read',
                                    ],
                                ],
                            ]
                        );
                    }

                }

            }
        );
    }
}

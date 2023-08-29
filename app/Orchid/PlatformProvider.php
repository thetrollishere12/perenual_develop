<?php

declare(strict_types=1);

namespace App\Orchid;

use Orchid\Platform\Dashboard;
use Orchid\Platform\ItemPermission;
use Orchid\Platform\OrchidServiceProvider;
use Orchid\Screen\Actions\Menu;
use Orchid\Support\Color;

class PlatformProvider extends OrchidServiceProvider
{
    /**
     * @param Dashboard $dashboard
     */
    public function boot(Dashboard $dashboard): void
    {
        parent::boot($dashboard);

        // ...
    }

    /**
     * @return Menu[]
     */
    public function registerMainMenu(): array
    {
        return [
            Menu::make('Email sender')
                ->icon('envelope-letter')
                ->route('platform.email')
                ->title('Tools'),

            Menu::make('Shops')
                ->icon('module')
                ->route('platform.shop.list')
                ->title('Sellers'),

            Menu::make('Products')
                ->icon('list')
                ->route('platform.product.list'),

            Menu::make(__('Plants'))
                ->icon('star')
                ->route('platform.species.plant.list')
                ->title(__('Species')),

            Menu::make(__('Article FAQ'))
                ->icon('question')
                ->route('platform.article.faq.list')
                ->title(__('Articles')),

            Menu::make('Faq')
                ->icon('question')
                ->route('platform.faq.list')
                ->title('Admin'),

            Menu::make('Example screen')
                ->icon('monitor')
                ->route('platform.example')
                ->title('Navigation')
                ->badge(fn () => 6),

            Menu::make('Dropdown menu')
                ->icon('code')
                ->list([
                    Menu::make('Sub element item 1')->icon('bag'),
                    Menu::make('Sub element item 2')->icon('heart'),
                ]),

            Menu::make('Basic Elements')
                ->title('Form controls')
                ->icon('note')
                ->route('platform.example.fields'),

            Menu::make('Advanced Elements')
                ->icon('briefcase')
                ->route('platform.example.advanced'),

            Menu::make('Text Editors')
                ->icon('list')
                ->route('platform.example.editors'),

            Menu::make('Overview layouts')
                ->title('Layouts')
                ->icon('layers')
                ->route('platform.example.layouts'),

            Menu::make('Chart tools')
                ->icon('bar-chart')
                ->route('platform.example.charts'),

            Menu::make('Cards')
                ->icon('grid')
                ->route('platform.example.cards')
                ->divider(),

            Menu::make('Documentation')
                ->title('Docs')
                ->icon('docs')
                ->url('https://orchid.software/en/docs'),

            Menu::make('Changelog')
                ->icon('shuffle')
                ->url('https://github.com/orchidsoftware/platform/blob/master/CHANGELOG.md')
                ->target('_blank')
                ->badge(fn () => Dashboard::version(), Color::DARK()),

            Menu::make(__('Users'))
                ->icon('user')
                ->route('platform.systems.users')
                ->permission('platform.systems.users')
                ->title(__('Access rights')),

            Menu::make(__('General Charts'))
                ->icon('user')
                ->route('platform.general.chart')
                ->permission('platform.systems.users'),

            Menu::make(__('Roles'))
                ->icon('lock')
                ->route('platform.systems.roles')
                ->permission('platform.systems.roles'),
        ];
    }

    /**
     * @return Menu[]
     */
    public function registerProfileMenu(): array
    {
        return [
            Menu::make(__('Profile'))
                ->route('platform.profile')
                ->icon('user'),
        ];
    }

    /**
     * @return ItemPermission[]
     */
    public function registerPermissions(): array
    {
        return [
            ItemPermission::group(__('System'))
                ->addPermission('platform.systems.roles', __('Roles'))
                ->addPermission('platform.systems.users', __('Users')),

            ItemPermission::group(__('Publisher'))
                ->addPermission('platform.article.article', __('Article'))
                ->addPermission('platform.article.faq', __('Faq')),
                
            ItemPermission::group(__('Instagram Social Media'))
                ->addPermission('platform.socialmedia.instagram.manager', __('Manager'))
                ->addPermission('platform.socialmedia.instagram.creator', __('Creator')),

            ItemPermission::group(__('Reddit Social Media'))
                ->addPermission('platform.socialmedia.reddit.manager', __('Manager')),

            ItemPermission::group(__('Sales/Spokes'))
                ->addPermission('platform.sales_spokes', __('Sales/Spokes')),

            ItemPermission::group(__('Developer'))
                ->addPermission('platform.developer', __('Developer')),

            ItemPermission::group(__('Species Editor'))
                ->addPermission('platform.species', __('Species Editor')),

        ];
    }
}

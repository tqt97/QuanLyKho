<?php

namespace App\Providers\Filament;

use Filament\Pages;
use Filament\Panel;
use Filament\Widgets;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\View\PanelsRenderHook;
use Filament\Support\Enums\MaxWidth;
use Filament\Navigation\NavigationItem;
use App\Filament\Widgets\DashboardAdmin;
use Filament\Navigation\NavigationGroup;
use Filament\Http\Middleware\Authenticate;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Cookie\Middleware\EncryptCookies;
use TomatoPHP\FilamentBrowser\FilamentBrowserPlugin;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\AuthenticateSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use App\Filament\Resources\OrderResource\Widgets\LatestOrders;
use App\Filament\Resources\OrderResource\Widgets\OrdersPerDayChart;
use App\Filament\Resources\ProductResource\Widgets\ProductsOverview;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile()
            ->registration()
            ->passwordReset()
            ->emailVerification()
            ->profile(isSimple: false)
            ->authPasswordBroker('users')
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Widgets\AccountWidget::class,
                // Widgets\FilamentInfoWidget::class,
                OrdersPerDayChart::class,
                DashboardAdmin::class,                // ProductsOverview::class,
                LatestOrders::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->renderHook(
                // PanelsRenderHook::BODY_END,
                PanelsRenderHook::FOOTER,
                fn() => view('filament.footer')
            )
            // ->collapsibleNavigationGroups(false)
            ->sidebarCollapsibleOnDesktop()
            ->sidebarFullyCollapsibleOnDesktop()
            ->maxContentWidth(MaxWidth::ScreenTwoExtraLarge)
            ->topNavigation()
            ->spa()
            ->unsavedChangesAlerts()
            ->navigationGroups([
                NavigationGroup::make()
                    ->label(fn(): string => __('shop/navigation.shops'))
                    ->icon('heroicon-o-shopping-cart'),
                NavigationGroup::make()
                    ->label(fn(): string => __('shop/navigation.accounts'))
                    ->icon('heroicon-o-user-circle'),
            ])
            ->navigationItems([
                NavigationItem::make('Website')
                    ->url('/', shouldOpenInNewTab: true)

                    ->icon('heroicon-o-globe-alt')->sort(10)
            ])
            ->plugins([
                FilamentBrowserPlugin::make()
                    ->hiddenFolders([
                        base_path('app'),
                        base_path('bootstrap'),
                        base_path('config'),
                        base_path('database'),
                        base_path('node_modules'),
                        base_path('routes'),
                        base_path('resources'),
                        base_path('lang'),
                        base_path('tests'),
                        base_path('vendor'),
                    ])
                    ->hiddenFiles([
                        base_path('.env'),
                        base_path('.env.example'),
                        base_path('.gitignore'),
                        base_path('composer.json'),
                        base_path('composer.lock'),
                        base_path('package.json'),
                        base_path('webpack.mix.js'),
                        base_path('yarn.lock'),
                        base_path('phpunit.xml'),
                        base_path('README.md'),
                        base_path('LICENSE'),
                        base_path('artisan'),
                        base_path('server.php'),
                        base_path('.gitattributes'),
                        base_path('package-lock.json'),
                        base_path('*'),

                    ])
                    ->hiddenExtantions([
                        'php',
                    ])
                    ->allowCreateFolder()
                    ->allowEditFile()
                    ->allowCreateNewFile()
                    ->allowCreateFolder()
                    ->allowRenameFile()
                    ->allowDeleteFile()
                    ->allowMarkdown()
                    ->allowCode()
                    ->allowPreview()
                    ->basePath(base_path() . '/public/storage/products'),
            ]);
    }
}

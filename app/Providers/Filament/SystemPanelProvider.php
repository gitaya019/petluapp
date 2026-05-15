<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\App;
use YusufGenc34\FilamentApiForge\FilamentApiForgePlugin;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use AchyutN\FilamentLogViewer\FilamentLogViewer;
use ShuvroRoy\FilamentSpatieLaravelHealth\FilamentSpatieLaravelHealthPlugin;


class SystemPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('system')
            ->path('system')
            ->brandName('PetluApp Center')
            ->login()
            ->favicon(asset('images/logo_ruby.svg'))
            ->colors([
                'primary' => Color::Red,
            ])

            ->plugins([
                FilamentLogViewer::make()
                    ->navigationGroup('System')
                    ->navigationLabel('Logs')
                    ->navigationIcon('heroicon-o-document-text')
                    ->navigationSort(10)
                    ->authorize(fn() => Auth::check())
                    ->registerNavigation(true)
                    ->navigationGroup('System')
                    ->navigationIcon('heroicon-o-document-text')
                    ->navigationLabel('Log Viewer')
                    ->navigationSort(10)
                    ->navigationUrl('/logs')
                    ->pollingTime(null), // Set to null to disable polling,

                FilamentApiForgePlugin::make()
                    ->apiKeys()     // API key management
                    ->docs()        // API Docs + Access Control + Settings pages
                    ->dashboard(),   // Developer Center dashboard

                FilamentSpatieLaravelHealthPlugin::make(),
            ])

            ->discoverResources(in: app_path('Filament/System/Resources'), for: 'App\Filament\System\Resources')
            ->discoverPages(in: app_path('Filament/System/Pages'), for: 'App\Filament\System\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/System/Widgets'), for: 'App\Filament\System\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
                \App\Http\Middleware\EnsureSystemAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

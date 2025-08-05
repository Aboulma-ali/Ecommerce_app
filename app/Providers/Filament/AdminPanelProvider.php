<?php

namespace App\Providers\Filament;

use App\Http\Middleware\CheckIsAdmin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Filament\Navigation\UserMenuItem;      // 👈
use Filament\Navigation\NavigationItem;    // 👈
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
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
              //  Widgets\FilamentInfoWidget::class,
            ])
            // 1) Un lien "Retour à la boutique" dans le menu utilisateur
            ->userMenuItems([
                'storefront' => UserMenuItem::make()
                    ->label('Retour à la boutique')
                    ->icon('heroicon-o-home')
                    ->url(fn () => url('/'))      // route front-office
                    ->openUrlInNewTab(false),   // true si tu préfères un nouvel onglet
            ])

            // 2) (Facultatif) Un item dans la sidebar Filament
            ->navigationItems([
                NavigationItem::make('Retour boutique')
                    ->url(fn () => url('/'))
                    ->icon('heroicon-o-arrow-left')
                    ->group('Boutique')        // ou supprime pour aucun groupage
                    ->sort(-1)                 // -1 se place tout en haut
                    ->openUrlInNewTab(false),
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
                CheckIsAdmin::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}

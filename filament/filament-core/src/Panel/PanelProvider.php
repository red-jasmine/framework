<?php

namespace RedJasmine\FilamentCore\Panel;

use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\Widgets;
use Filament\Pages;
use Filament\PanelProvider as FilamentPanelProvider;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

abstract class PanelProvider extends FilamentPanelProvider
{
    public static function configure(Panel $panel) : Panel
    {

        $panel->login(Login::class)
              ->maxContentWidth(Width::Full)
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
                  VerifyCsrfToken::class,
                  SubstituteBindings::class,
                  DisableBladeIconComponents::class,
                  DispatchServingFilamentEvent::class,
              ])
              ->authMiddleware([
                  Authenticate::class,
              ])
              ->sidebarWidth('10rem')
              ->passwordReset()
              ->emailVerification()
              ->profile();

        return $panel;

    }

}
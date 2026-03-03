<?php

namespace App\Providers\Filament;

use App\Filament\Auth\LoginAdmin;
use App\Filament\User\Pages\Dashboard as PagesDashboard;
use App\Filament\User\Pages\Profile;
use App\Filament\User\Widgets\AnalyticKehadiranBulanan;
use App\Filament\User\Widgets\AnalyticKehadiranMasuk;
use App\Filament\User\Widgets\KaryawanWidget;
use App\Livewire\AnalyticKehadiranKeluar;
use App\Livewire\AnalyticKetidakHadiran;
use Asmit\ResizedColumn\ResizedColumnPlugin;
use Filament\Actions\Action;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages\Dashboard;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Width;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Moataz01\FilamentNotificationSound\FilamentNotificationSoundPlugin;
use WatheqAlshowaiter\FilamentStickyTableHeader\StickyTableHeaderPlugin;

class UserPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('user')
            ->path('user')
            ->viteTheme('resources/css/filament/admin/theme.css')
            ->login(LoginAdmin::class)
            ->colors([
                'primary' => Color::Amber,
            ])
            ->userMenuItems([
                'profile' => fn(Action $action) => $action
                    ->label(fn() => auth()->user()->name)
                    ->url(fn(): string => Profile::getUrl())
                    ->icon('heroicon-m-user-circle')
            ])
           // ->brandName(fn() => view('component.brand_user'))
            ->favIcon(fn() => auth()?->user()?->perusahaan?->logo == null ? asset('storage/perusahaan/default_logo.png') :  Storage::url(auth()->user()->perusahaan->logo))
            ->spa()
            ->maxContentWidth(Width::Full)
            ->sidebarCollapsibleOnDesktop()
            ->simplePageMaxContentWidth(Width::Small)
            ->databaseNotifications()
            ->databaseNotificationsPolling(null)
            ->sidebarFullyCollapsibleOnDesktop()
            ->databaseTransactions()
            ->discoverResources(in: app_path('Filament/User/Resources'), for: 'App\Filament\User\Resources')
            ->discoverPages(in: app_path('Filament/User/Pages'), for: 'App\Filament\User\Pages')
            ->pages([])
            ->discoverWidgets(in: app_path('Filament/User/Widgets'), for: 'App\Filament\User\Widgets')
            ->widgets([
                // KaryawanWidget::class,
                // AnalyticKehadiranMasuk::class,
                // AnalyticKehadiranKeluar::class,
                // AnalyticKetidakHadiran::class,
                // AnalyticKehadiranBulanan::class
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
            ->plugins([
                StickyTableHeaderPlugin::make(), //php artisan filament:assets
                FilamentNotificationSoundPlugin::make()
                    ->volume(0.8) // Volume (0.0 to 1.0)
                    ->showAnimation(true) // Show animation on notification badge
                    ->enabled(true),

            ])
            ->font('Poppins')
            ->colors([
                'danger' => Color::Rose,
                'gray' => Color::Gray,
                'info' => Color::Blue,
                'primary' => Color::Sky,
                'success' => Color::Green,
                'warning' => Color::Amber,
            ])
            ->viteTheme('resources/css/filament/user/theme.css');
    }
}

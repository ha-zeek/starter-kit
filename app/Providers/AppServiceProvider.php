<?php

namespace App\Providers;

use App\Modifiers\ShippingModifier;
use Illuminate\Support\ServiceProvider;
use Lunar\Admin\Support\Facades\LunarPanel;
use Lunar\Base\ShippingModifiers;
use Lunar\Shipping\ShippingPlugin;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        LunarPanel::panel(
            fn ($panel) => $panel
                ->path('admin')
                ->plugins([new ShippingPlugin])
                ->colors([])
//                ->brandName('Xentral Methods')
                ->brandLogo(asset('images/xm.png'))
                ->darkModeBrandLogo(asset('images/xm.png'))
                ->brandLogoHeight('3rem')
                ->resources(['App\Filament\Resources\ReportResource','App\Filament\Resources\UpdateLogResource','App\Filament\Resources\BackupLogResource',
                    ...$panel->getResources()])

        )
            ->register();
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(ShippingModifiers $shippingModifiers): void
    {
        $shippingModifiers->add(
            ShippingModifier::class
        );
    }
}

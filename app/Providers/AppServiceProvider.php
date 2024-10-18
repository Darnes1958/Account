<?php

namespace App\Providers;

use App\Models\OurCompany;
use BezhanSalleh\FilamentLanguageSwitch\LanguageSwitch;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentColor;
use Filament\Support\Facades\FilamentView;
use Filament\Tables\Table;
use Filament\View\PanelsRenderHook;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Table::$defaultNumberLocale = 'nl';
        Gate::before(function ($user, $ability) {
            return $user->hasRole('admin') ? true : null;
        });
        FilamentColor::register([
            'Fuchsia' =>  Color::Fuchsia,
            'green' =>  Color::Green,
            'blue' =>  Color::Blue,
            'gray' =>  Color::Gray,
        ]);
        LanguageSwitch::configureUsing(function (LanguageSwitch $switch) {
            $switch
                ->locales(['ar','en',]); // also accepts a closure
        });
        FilamentView::registerRenderHook(
            PanelsRenderHook::USER_MENU_AFTER,
            fn (): View => view('avatar',['compImage'=>OurCompany::where('company',Auth::user()->company)->first()->CompanyImg]),
        );
        FilamentView::registerRenderHook(
            PanelsRenderHook::GLOBAL_SEARCH_BEFORE,
            fn (): string => Blade::render('@livewire(\'top-bar\')'),
        );
        Model::unguard();
    }
}

<?php

namespace App\Livewire\widgets;

use App\Models\OurCompany;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Auth;

class State1 extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('مرحبا !!',Auth::user()->name)
                ->description('في منظومة المحاسبة')
                ->descriptionIcon('heroicon-s-check')
                ->color('success'),
            Stat::make('هذه النسحة مرخصة لــ ',OurCompany::where('Company',Auth::user()->company)->first()->CompanyName)
                ->description(OurCompany::where('Company',Auth::user()->company)->first()->CompanyNameSuffix)

                ->color('primary'),
        ];
    }
}

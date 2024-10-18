<?php

namespace App\Filament\Pages;

class Dashboard extends \Filament\Pages\Dashboard
{
    protected ?string $heading="منظومة المحاسبة - الصفحة الرئيسية";
    public function getColumns(): int | string | array
    {
        return 6;
    }
}

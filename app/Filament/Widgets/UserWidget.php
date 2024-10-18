<?php

namespace App\Filament\Widgets;

use App\Models\OurCompany;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;

class UserWidget extends Widget
{
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort=4;
    public $has_image2 = false;
    public $image2;
    protected static string $view = 'filament.widgets.user-widget';
    public function mount(): void {

        $this->image2=Auth::user()->image;
        if ($this->image2) $this->has_image2=true;
    }
}

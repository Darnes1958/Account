<?php

namespace App\Filament\Widgets;

use App\Models\OurCompany;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Auth;
use Svg\Tag\Image;

class CompanyWidget extends Widget

{
    protected int | string | array $columnSpan = 1;
    protected static ?int $sort=2;
    public $image;

    public $has_image = false;

    protected static string $view = 'filament.widgets.company-widget';
    public function mount(): void {
        $this->image=OurCompany::where('Company',Auth::user()->company)->first()->CompanyImg;
        if ($this->image) $this->has_image=true;

    }
}

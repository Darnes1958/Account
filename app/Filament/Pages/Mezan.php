<?php

namespace App\Filament\Pages;

use App\Enums\AccLevel;
use App\Models\Account;
use App\Models\Accountsum;
use Filament\Forms\Components\Radio;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;

class Mezan extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.mezan';

    public $acc_level=1;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Radio::make('acc_level')
                ->label('المستوي')
                ->inline()
                ->inlineLabel(false)
                ->options(AccLevel::class)
                ->live()
            ]);
    }
    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                return Accountsum::where('acc_level',1)

                    ;
            })
            ->columns([
                TextColumn::make('id')
                ->label('رقم الحساب'),
                TextColumn::make('name')
                ->label('الاسم'),
               TextColumn::make('mden')->summarize(Sum::make()),
                TextColumn::make('daen')->summarize(Sum::make()),
               // TextColumn::make('kyde_data_sum_mden')->sum('kyde_data','mden')->label('مدين'),
              //  TextColumn::make('kyde_data_sum_daen')->sum('kyde_data','daen')->label('دائن'),

            ]);
    }
}

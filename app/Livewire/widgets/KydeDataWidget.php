<?php

namespace App\Livewire\widgets;

use App\Models\KydeData;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class KydeDataWidget extends BaseWidget
{
    public function mount($kyde_id)
    {
        $this->kyde_id=$kyde_id;
    }
    public $kyde_id;
    public function table(Table $table): Table
    {
        return $table
            ->query( function() {
                return KydeData::query()->where('kyde_id',$this->kyde_id);
            }
            )
            ->columns([
                TextColumn::make('Account.id')
                    ->searchable()
                    ->sortable()
                    ->label('الحساب'),
                TextColumn::make('Account.name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم'),
                TextColumn::make('Account.full_name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم الكامل'),
                TextColumn::make('mden')
                    ->searchable()
                    ->sortable()
                    ->label('مدين'),
                TextColumn::make('daen')
                    ->searchable()
                    ->sortable()
                    ->label('دائن'),
            ]);
    }
}

<?php
namespace App\Livewire\Traits;



use App\Enums\AccLevel;
use App\Models\Rent;
use App\Models\Renttran;
use App\Models\Salary;
use App\Models\Salarytran;

use Carbon\Carbon;
use DateTime;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\Summarizers\Sum;
use Filament\Tables\Columns\Summarizers\Summarizer;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

trait PublicTrait {

    protected function getAcc_levelFromComponent(): Radio
    {
        return  Radio::make('acc_level')
            ->options(AccLevel::class)
            ->inline()
            ->live()
            ->inlineLabel(false)
            ->label('المستوي');
    }
    protected function getKydedataFormComponent($name): TextColumn
    {
        if ($name === 'kyde_id') {
            return TextColumn::make('kyde_id')
                ->label('رقم القيد')
                ->searchable()
                ->sortable();
        }
        if ($name === 'notes') {
            return TextColumn::make('Kyde.notes')
                ->label('بيان القيد')
                ->tooltip('انقر هنا لعرض القيد')
                ->searchable()
                ->sortable();
        }
        if ($name === 'account_id') {
            return TextColumn::make('account_id')
                ->label('رقم الحساب')
                ->searchable()
                ->sortable();
        }
        if ($name === 'full_name') {
            return TextColumn::make('Account.full_name')
                ->label('اسم الحساب')
                ->searchable()
                ->sortable();
        }
        if ($name === 'kyde_date') {
            return TextColumn::make('Kyde.kyde_date')
                ->label('التاريخ')
                ->searchable()
                ->sortable();
        }


    }
    protected function getMdenFormComponent(): TextColumn
    {
        return  TextColumn::make('mden')
            ->state(function (Model $recoed){
                if ($recoed->mden==0) return null;
                else return $recoed->mden;
            })
            ->color('danger')
            ->label('مدين')
            ->summarize(Sum::make()->label('')->numeric(
                decimalPlaces: 2,
                decimalSeparator: '.',
                thousandsSeparator: ',',
            ))
            ->numeric(
                decimalPlaces: 2,
                decimalSeparator: '.',
                thousandsSeparator: ',',
            );
    }
    protected function getDaenFormComponent(): TextColumn
    {
        return
            TextColumn::make('daen')
            ->state(function (Model $recoed){
                if ($recoed->daen==0) return null;
                else return $recoed->daen;
            })
            ->label('دائن')
            ->color('info')
            ->summarize(Sum::make()->label('')->numeric(
                decimalPlaces: 2,
                decimalSeparator: '.',
                thousandsSeparator: ',',
            ))
            ->numeric(
                decimalPlaces: 2,
                decimalSeparator: '.',
                thousandsSeparator: ',',
            );
    }
    protected function getMden2FormComponent(): TextColumn
    {
        return
            TextColumn::make('mden2')
            ->state(function (Model $recoed){
                if ($recoed->mden2==0) return null;
                else return $recoed->mden2;
            })
            ->summarize(Summarizer::make()
                ->numeric(
                    decimalPlaces: 2,
                    decimalSeparator: '.',
                    thousandsSeparator: ',',
                )
                ->using(function (Table $table) {
                    return $table->getRecords()->sum('mden2');
                })
            )
            ->numeric(
                decimalPlaces: 2,
                decimalSeparator: '.',
                thousandsSeparator: ',',
            )
            ->color('danger')
            ->label('مدين');
    }
    protected function getDaen2FormComponent(): TextColumn
    {
        return
            TextColumn::make('daen2')
            ->state(function (Model $recoed){
                if ($recoed->daen2==0) return null;
                else return $recoed->daen2;
            })
            ->summarize(Summarizer::make()
                ->numeric(
                    decimalPlaces: 2,
                    decimalSeparator: '.',
                    thousandsSeparator: ',',
                )
                ->using(function (Table $table) {
                    return $table->getRecords()->sum('daen2');
                })
            )
            ->numeric(
                decimalPlaces: 2,
                decimalSeparator: '.',
                thousandsSeparator: ',',
            )
            ->color('info')
            ->label('دائن');
    }


}

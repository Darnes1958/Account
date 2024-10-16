<?php

namespace App\Filament\Resources;

use App\Enums\AccLevel;
use App\Filament\Resources\AccountResource\Pages;
use App\Filament\Resources\AccountResource\RelationManagers;
use App\Models\Account;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Forms\Set;
use Filament\Forms\Get;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Forms\Components\TextInput;
use Illuminate\Support\Collection;
use Svg\Tag\Text;
use Filament\Forms\Components\Radio;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Select;
class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Radio::make('acc_level')
                ->options(AccLevel::class)
                ->live()
                ->inline()
                ->inlineLabel(false)
                ->default(1),
                Select::make('theGrand')
                 ->dehydrated(false)
                 ->options(Account::where('acc_level',1)->pluck('name', 'id'))
                 ->preload()
                 ->searchable()
                 ->required()
                 ->live()
                 ->visible(fn(Get $get): bool=> $get('acc_level')>1)
                 ->afterStateUpdated(function ($state,Set $set,Get $get): void {
                     $set('grand_id',$state);
                    if ($get('acc_level')==2) {
                        $set('num',Account::where('grand_id',$state)->max('num') + 1);
                        $set('id',strval($state).'-'.strval($get('num')));
                    }
                    if ($get('acc_level')==3) {$set('theFather',null);}

                 }),
                Select::make('theFather')
                    ->dehydrated(false)
//->options(fn (Get $get): Collection => Account::query()->where('acc_level',2)->where('grand_id', $get('grand_id'))->pluck('name', 'id'))
                     ->options(function (Get $get){
                         $theGrand=Account::query()->where('acc_level',2)
                             ->where('grand_id', $get('grand_id'))
                             ->pluck('name', 'id');
                         if (! $theGrand) return Account::query()->where('acc_level',2)
                             ->pluck('name', 'id');
                         return $theGrand;
                    })
                    ->preload()
                    ->searchable()
                    ->required()
                    ->live()
                   ->visible(fn(Get $get): bool=> $get('acc_level')>2)
                    ->disabled(fn(Get $get): bool=> !$get('theGrand'))
                    ->afterStateUpdated(function ($state,Set $set,Get $get): void {
                        $set('father_id',$state);
                        if ($get('acc_level')==3) {
                            $set('num',Account::where('father_id',$state)->max('num') + 1);
                            $set('id',strval($state).'-'.strval($get('num')));
                        }

                    }),
                TextInput::make('num')
                 ->required()
                 ->default(function (Get $get){
                     if ($get('acc_level')==1) return Account::where('acc_level',1)->max('num')+1;
                 })
                 ->live(onBlur: true)
                 ->afterStateUpdated(function ($state, Set $set,Get $get) {
                     if ($get('acc_level') == 1) {$set('id',strval($state));}
                 }),
                TextInput::make('name')
                    ->required(),
                TextInput::make('id')
                    ->default(function (Get $get){
                        if ($get('acc_level')==1) return strval(Account::where('acc_level',1)->max('num')+1);
                    })
                    ->readOnly(),
                TextInput::make('grand_id')
                    ->default(1)
                    ->readOnly(),
                TextInput::make('father_id')
                    ->readOnly(),
                TextInput::make('son_id')
                ->readOnly(),
                TextInput::make('is_active')
                    ->default(1)
                    ->readOnly(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_string')
                 ->searchable()
                 ->sortable()
                 ->label('الحساب'),
                TextColumn::make('num')
                    ->searchable()
                    ->sortable()
                    ->label('الرقم'),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم'),
                TextColumn::make('full_name')
                    ->searchable()
                    ->sortable()
                    ->label('الاسم الكامل'),
                TextColumn::make('acc_level')
                    ->searchable()
                    ->sortable()
                    ->badge()
                    ->label('المستوي'),

            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListAccounts::route('/'),
            'create' => Pages\CreateAccount::route('/create'),
            'edit' => Pages\EditAccount::route('/{record}/edit'),
        ];
    }
}

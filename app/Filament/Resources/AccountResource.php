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
use Filament\Forms\Components\Hidden;
class AccountResource extends Resource
{
    protected static ?string $model = Account::class;

    protected static ?string $navigationLabel='حسابات';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                 ->schema([
                     Radio::make('acc_level')
                         ->label('مستوي الحساب')
                         ->options(AccLevel::class)
                         ->live()
                         ->inline()
                         ->afterStateUpdated(function (Set $set,$state,Get $get){
                             $set('grand_id', null);
                             $set('father_id', null);
                             $set('son_id', null);

                             $set('id', null);
                             $set('theGrand', null);
                             $set('theFather', null);
                             $set('theSon', null);


                             if ($state==1) {
                                 $set('id',strval(Account::where('acc_level',1)->max('num')+1));
                             }
                             if ($state>1) {
                                 $set('grand_id', '1');
                             }
                             if ($state>2) {
                                 $set('father_id', '1-1');
                             }

                         })
                         ->inlineLabel(false)
                         ->default(1),
                     Select::make('theGrand')
                         ->label('الحساب الرئيسي')
                         ->dehydrated(false)
                         ->options(Account::where('acc_level',1)->pluck('name', 'id'))
                         ->preload()
                         ->searchable()
                         ->required()
                         ->live()
                         ->visible(fn(Get $get): bool=> $get('acc_level')>1)
                         ->afterStateUpdated(function ($state,Set $set,Get $get): void {
                         //    $set('grand_id',$state);
                             if ($get('acc_level')==2) {
                                 $set('num',Account::where('grand_id',$state)->max('num') + 1);
                                 $set('id',strval($state).'-'.strval($get('num')));
                             }
                             if ($get('acc_level')==3) {$set('theFather',null);}

                         }),
                     Select::make('theFather')
                         ->label('الحساب الفرعي')
                         ->dehydrated(false)
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
                          //   $set('father_id',$state);
                             if ($get('acc_level')==3) {
                                 $set('num',Account::where('father_id',$state)->max('num') + 1);
                                 $set('id',strval($state).'-'.strval($get('num')));
                             }
                             if ($get('acc_level')==4) {$set('theSon',null);}

                         }),
                     Select::make('theSon')
                         ->label('الحساب التحليلي')
                         ->dehydrated(false)
                         ->options(function (Get $get){
                             $theFather=Account::query()->where('acc_level',3)
                                 ->where('father_id', $get('father_id'))
                                 ->pluck('name', 'id');
                             if (! $theFather) return Account::query()->where('acc_level',3)
                                 ->pluck('name', 'id');
                             return $theFather;
                         })
                         ->preload()
                         ->searchable()
                         ->required()
                         ->live()
                         ->visible(fn(Get $get): bool=> $get('acc_level')>3)
                         ->disabled(fn(Get $get): bool=> !$get('theFather'))
                         ->afterStateUpdated(function ($state,Set $set,Get $get): void {
                             $set('son_id',$state);
                             if ($get('acc_level')==4) {
                                 $set('num',Account::where('son_id',$state)->max('num') + 1);
                                 $set('id',strval($state).'-'.strval($get('num')));
                             }

                         }),

                     TextInput::make('name')
                         ->label('اسم الحساب')
                         ->required(),
                     TextInput::make('id')
                         ->label('رقم الحساب')
                         ->default(function (Get $get){
                             if ($get('acc_level')==1) return strval(Account::where('acc_level',1)->max('num')+1);
                         })
                         ->readOnly(),
                     Hidden::make('num')
                         ->default(function (Get $get){
                             if ($get('acc_level')==1) return Account::where('acc_level',1)->max('num')+1;
                         }),
                     Hidden::make('grand_id')
                         ,
                     Hidden::make('father_id')
                         ,
                     Hidden::make('son_id'),
                     Hidden::make('is_active')
                         ->default(1) ,

                 ])
                ->columnSpan(1),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')
                 ->searchable()
                 ->sortable()
                 ->label('الحساب'),

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
               //
            ])
            ;
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

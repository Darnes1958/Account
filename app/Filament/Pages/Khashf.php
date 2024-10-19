<?php

namespace App\Filament\Pages;

use App\Enums\AccLevel;
use App\Livewire\Traits\PublicTrait;
use App\Models\Account;
use App\Models\Kyde;
use App\Models\KydeData;
use Filament\Actions\StaticAction;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Select;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Pages\Page;
use Filament\Support\Enums\IconSize;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\View\View;

class Khashf extends Page implements HasForms,HasTable
{
    use InteractsWithForms,InteractsWithTable;
    use PublicTrait;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'filament.pages.khashf';
    protected ?string $heading='';

    protected static ?string $navigationLabel='كشف حساب';

    public $account_id;
    public $acc_level=4;
    public function form(Form $form): Form
    {
        return $form
            ->schema([
               Select::make('account_id')
                ->label('الحساب')
                ->searchable()
                ->preload()
                ->options(function (){
                    return Account::where('acc_level','<=',$this->acc_level)->pluck('name', 'id') ;
                })
                ->live(),
               $this->getAcc_levelFromComponent(),
            ])
            ->columns(4);
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(function (){
                if (!$this->account_id) return KydeData::where('account_id', $this->account_id);
                return KydeData::where('account_id', $this->account_id)
                    ->orwhereIn('account_id', Account::where('grand_id',$this->account_id)->select('id'))
                    ->orwhereIn('account_id', Account::where('father_id',$this->account_id)->select('id'))
                    ->orwhereIn('account_id', Account::where('son_id',$this->account_id)->select('id'));

            }
            )
            ->emptyStateHeading('لا توجد بيانات')

            ->columns([
                $this->getKydedataFormComponent('kyde_id'),
                $this->getKydedataFormComponent('kyde_date'),
                $this->getKydedataFormComponent('notes')
                 ->action(
                     Action::make('kydeview')
                         ->iconButton()
                         ->iconSize(IconSize::Small)
                         ->icon('heroicon-o-list-bullet')
                         ->color('success')
                         ->modalHeading(false)
                         ->modalSubmitAction(false)
                         ->modalCancelAction(fn (StaticAction $action) => $action->label('عودة'))
                         ->modalContent(fn (KydeData $record): View => view(
                             'view-kyde-data-widget',
                             ['kyde_id' => $record->kyde_id],
                         )),
                 ),
                $this->getKydedataFormComponent('account_id'),
                $this->getKydedataFormComponent('full_name'),
                $this->getMdenFormComponent(),
                $this->getDaenFormComponent(),





            ]);
    }
}

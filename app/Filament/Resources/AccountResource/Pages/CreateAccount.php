<?php

namespace App\Filament\Resources\AccountResource\Pages;

use App\Filament\Resources\AccountResource;
use App\Models\Account;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateAccount extends CreateRecord
{
    protected static string $resource = AccountResource::class;

    protected function getRedirectUrl(): string
    {
        return static::getResource()::getUrl('index');
    }
    protected ?string $heading='';
    protected function mutateFormDataBeforeCreate(array $data): array
    {

        unset($data['theFather']);
        unset($data['theSon']);

        if ($data['acc_level']==2) Account::find($data['grand_id'])->update(['is_active'=>0]);
        if ($data['acc_level']==3) Account::find($data['father_id'])->update(['is_active'=>0]);
        if ($data['acc_level']==4) Account::find($data['son_id'])->update(['is_active'=>0]);
        return parent::mutateFormDataBeforeCreate($data); // TODO: Change the autogenerated stub
    }
}
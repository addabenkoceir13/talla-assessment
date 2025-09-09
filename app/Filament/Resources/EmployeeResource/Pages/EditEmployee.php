<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditEmployee extends EditRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $this->roleName = $data['role'];
        unset($data['role']);
        return $data;
    }

    protected function afterSave(): void
    {
        if ($this->roleName) {
            $this->record->syncRoles([$this->roleName]);

            $this->record->update(['role' => $this->roleName]);
        }
    }
}

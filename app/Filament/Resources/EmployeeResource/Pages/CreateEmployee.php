<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateEmployee extends CreateRecord
{
    protected static string $resource = EmployeeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $this->roleName = $data['role'];   // save role temporarily
        unset($data['role']);              // remove from user table
        return $data;
    }

    protected function afterCreate(): void
    {
        if ($this->roleName) {
            $this->record->syncRoles([$this->roleName]);

            $this->record->update(['role' => $this->roleName]);
        }
    }
}

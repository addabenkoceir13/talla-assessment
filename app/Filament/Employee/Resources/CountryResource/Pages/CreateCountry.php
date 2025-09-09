<?php

namespace App\Filament\Employee\Resources\CountryResource\Pages;

use App\Filament\Employee\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;
use Filament\Notifications\Notification;

class CreateCountry extends CreateRecord
{
    protected static string $resource = CountryResource::class;

    protected function getFormActions(): array
    {
        return [
            $this->getSaveFormAction()->label('Save Country'),
            $this->getCancelFormAction(),
        ];
    }

    protected function getSavedNotification(): ?Notification
    {
        return Notification::make()
            ->success()
            ->title('Country saved')
            ->body('The country has been saved successfully.');
    }

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        // Ensure source is set if not provided
        $data['source'] = $data['source'] ?? 'user_added';
        $data['added_by_user_id'] = auth()->id();

        return $data;
    }
}

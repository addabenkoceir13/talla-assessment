<?php

namespace App\Filament\Employee\Resources\TestingPageResource\Pages;

use App\Filament\Employee\Resources\TestingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditTestingPage extends EditRecord
{
    protected static string $resource = TestingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}

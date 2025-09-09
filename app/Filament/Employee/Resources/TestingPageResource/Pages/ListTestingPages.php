<?php

namespace App\Filament\Employee\Resources\TestingPageResource\Pages;

use App\Filament\Employee\Resources\TestingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTestingPages extends ListRecords
{
    protected static string $resource = TestingPageResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}

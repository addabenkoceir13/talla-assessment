<?php

namespace App\Filament\Employee\Resources\TestingPageResource\Pages;

use App\Filament\Employee\Resources\TestingPageResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateTestingPage extends CreateRecord
{
    protected static string $resource = TestingPageResource::class;
}

<?php

namespace App\Filament\Employee\Resources\CountryResource\Pages;

use App\Filament\Employee\Resources\CountryResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Contracts\View\View;

class ListCountries extends ListRecords
{
    protected static string $resource = CountryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }

    public function getHeader(): ?View
    {
        return view('filament.employee.pages.country');
    }
}

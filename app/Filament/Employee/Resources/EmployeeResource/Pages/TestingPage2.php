<?php

namespace App\Filament\Employee\Resources\EmployeeResource\Pages;

use App\Filament\Employee\Resources\EmployeeResource;
use Filament\Resources\Pages\Page;

class TestingPage2 extends Page
{
    protected static string $resource = EmployeeResource::class;

    protected static string $view = 'filament.employee.resources.employee-resource.pages.testing-page2';
}

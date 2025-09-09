<?php
// app/Filament/Employee/Pages/TestingPage1.php

namespace App\Filament\Employee\Pages;

use App\Models\TestingData;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Forms;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;

class TestingPage2 extends Page implements HasTable, HasForms
{
    use InteractsWithTable;
    use InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-list';
    protected static ?string $navigationGroup = 'Testing Pages';
    protected static ?string $navigationLabel = 'Testing Page 2';
    protected static ?int $navigationSort = 3;
    protected static string $view = 'filament.employee.pages.testing-page2';


    public static function canAccess(): bool
    {
        // return Auth::user()->can('view_testing_page_1');
        return true;
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(TestingData::query()->where('page_type', 'page2'))
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),

                Tables\Columns\TextColumn::make('title')
                    ->label('Task Title')
                    ->searchable()
                    ->sortable()
                    ->limit(30),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'inactive' => 'danger',
                        'pending' => 'warning',
                        'completed' => 'info',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('priority')
                    ->label('Priority')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'low' => 'gray',
                        'medium' => 'info',
                        'high' => 'warning',
                        'urgent' => 'danger',
                    })
                    ->sortable(),

                Tables\Columns\TextColumn::make('category')
                    ->label('Category')
                    ->searchable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                    ]),

                Tables\Filters\SelectFilter::make('priority')
                    ->options([
                        'low' => 'Low',
                        'medium' => 'Medium',
                        'high' => 'High',
                        'urgent' => 'Urgent',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()
                    ->visible(fn () => Auth::user()->can('view_testing_page_2')),

                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->can('edit_testing_page_2'))
                    ->form($this->getFormSchema()),

                Tables\Actions\DeleteAction::make()
                    ->visible(fn () => Auth::user()->can('delete_testing_page_2')),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make()
                        ->visible(fn () => Auth::user()->can('delete_testing_page_2')),
                ]),
            ])
            ->headerActions([
                
                Tables\Actions\CreateAction::make()
                    ->visible(fn () => Auth::user()->can('create_testing_page_2'))
                    ->form($this->getFormSchema())
                    ->mutateFormDataUsing(function (array $data): array {
                        $data['created_by'] = Auth::id();
                        $data['page_type'] = 'page2';
                        return $data;
                    }),
            ])
            ->emptyStateHeading('No Page 1 Tasks Found')
            ->emptyStateDescription('Create your first task to test permissions on Testing Page 2.')
            ->emptyStateIcon('heroicon-o-clipboard-document-list');
    }


    protected function getFormSchema(): array
    {
        return [
            Forms\Components\Section::make('Task Information - Page 2')
                ->description('Testing Page 1 - Task Management Demo')
                ->schema([
                    Forms\Components\TextInput::make('title')
                        ->label('Task Title')
                        ->required()
                        ->maxLength(255),

                    Forms\Components\Textarea::make('description')
                        ->label('Description')
                        ->rows(3),

                    Forms\Components\Grid::make(2)
                        ->schema([
                            Forms\Components\Select::make('status')
                                ->label('Status')
                                ->required()
                                ->options([
                                    'active' => 'Active',
                                    'inactive' => 'Inactive',
                                    'pending' => 'Pending',
                                    'completed' => 'Completed',
                                ])
                                ->default('active'),

                            Forms\Components\Select::make('priority')
                                ->label('Priority')
                                ->required()
                                ->options([
                                    'low' => 'Low',
                                    'medium' => 'Medium',
                                    'high' => 'High',
                                    'urgent' => 'Urgent',
                                ])
                                ->default('medium'),
                        ]),

                    Forms\Components\TextInput::make('category')
                        ->label('Category')
                        ->placeholder('e.g., Development, Design'),
                ]),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        if (!static::canAccess()) {
            return null;
        }
        
        return (string) TestingData::where('page_type', 'page2')->count();
    }
}
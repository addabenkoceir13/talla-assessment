<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Spatie\Permission\Models\Permission;
use Filament\Forms\Components\Select;
use Spatie\Permission\Models\Role;

class EmployeeResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = 'Employees';
    protected static ?string $pluralModelLabel = 'Employees';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),

            Forms\Components\TextInput::make('email')
                ->email()
                ->unique(User::class, 'email', ignoreRecord: true)
                ->required(),
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn($context) => $context === 'create') // required on create only
                ->dehydrateStateUsing(fn($state) =>  $state)
                ->dehydrated(fn($state) => filled($state)) // only save if not empty
                ->maxLength(255),
            Select::make('role')
                ->label('Role')
                ->options(Role::pluck('name', 'name')->toArray()) // get all roles
                ->searchable()
                ->required(),
            Forms\Components\CheckboxList::make('permissions')
                ->label('Permissions')
                ->options(fn() => Permission::all()->pluck('name', 'id'))
                ->columns(2)
                ->relationship('permissions', 'name'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('email')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('roles.name')->label('Role'),
                Tables\Columns\BadgeColumn::make('permissions.name')
                ->label('Permissions')
                ->separator(', '),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    /**
     * Get the relation managers that should be available for the resource.
     *
     * @return array
     */
    /*******  5f797c1f-1280-4294-9525-a8cc5fb3dfda  *******/
    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()->role('employee');
    }

    public static function afterCreate($record): void
    {
        // Assign role
        $record->assignRole('employee');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
        ];
    }
}

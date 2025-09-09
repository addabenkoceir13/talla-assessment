<?php

namespace App\Filament\Employee\Resources;

use App\Filament\Employee\Resources\CountryResource\Pages;
use App\Filament\Employee\Resources\CountryResource\RelationManagers;
use App\Models\Country;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class CountryResource extends Resource
{
    protected static ?string $model = Country::class;

    protected static ?string $navigationIcon = 'heroicon-o-flag';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255)
                    ->live(onBlur: true)
                    ->afterStateUpdated(function ($state, callable $set) {
                        if (! $state) return;

                        // Fetch from API when name is typed
                        $client = new \GuzzleHttp\Client();
                        try {
                            $response = $client->get("https://restcountries.com/v3.1/name/{$state}?fields=capital,area,currencies,languages,flags,region,subregion,population");
                            $data = json_decode($response->getBody(), true)[0] ?? null;

                            if ($data) {
                                $set('capital', $data['capital'][0] ?? null);
                                $set('area', $data['area'] ?? null);
                                $set('currencies', $data['currencies'] ?? null);
                                $set('languages', $data['languages'] ?? null);
                                $set('flag', $data['flags']['svg'] ?? null);
                                $set('region', $data['region'] ?? null);
                                $set('subregion', $data['subregion'] ?? null);
                                $set('population', $data['population'] ?? null);
                                $set('source', 'api');
                            }
                        } catch (\Exception $e) {
                            // Do nothing — let user fill manually
                        }
                    }),

                Forms\Components\TextInput::make('capital')->nullable(),
                Forms\Components\TextInput::make('region')->nullable(),
                Forms\Components\TextInput::make('subregion')->nullable(),
                Forms\Components\TextInput::make('population')->nullable(),
                Forms\Components\TextInput::make('area')->nullable()->numeric(),
                Forms\Components\Textarea::make('currencies')->nullable()->json(),
                Forms\Components\Textarea::make('languages')->nullable()->json(),
                Forms\Components\TextInput::make('flag')->nullable()->url(),

                Forms\Components\Hidden::make('source')->default('user_added'),
                Forms\Components\Hidden::make('added_by_user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('flag')->circular()->size(32),
                Tables\Columns\TextColumn::make('name')->searchable()->sortable(),
                Tables\Columns\TextColumn::make('capital')->searchable(),
                Tables\Columns\TextColumn::make('region')->searchable(),
                Tables\Columns\TextColumn::make('population')->sortable()->numeric(),
                Tables\Columns\TextColumn::make('source')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'api' => 'success',
                        'user_added' => 'warning',
                    }),
                Tables\Columns\TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('source')
                    ->options([
                        'api' => 'From API',
                        'user_added' => 'User Added',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCountries::route('/'),
            'create' => Pages\CreateCountry::route('/create'),
            'edit' => Pages\EditCountry::route('/{record}/edit'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Countries';
    }
}

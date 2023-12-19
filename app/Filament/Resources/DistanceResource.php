<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DistanceResource\Pages;
use App\Filament\Resources\DistanceResource\RelationManagers;
use App\Models\Distance;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DistanceResource extends Resource
{
    protected static ?string $model = Distance::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    protected static ?int $navigationSort = 2;

    protected static ?string $navigationGroup = 'Locations and Distances';

    public static function getNavigationBadge() : ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('source_location')
                    ->label('Source')
                    ->relationship('sourceLocation', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\Select::make('destination_location')
                    ->label('Destination')
                    ->relationship('destinationLocation', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('distance')
                    ->required()
                    ->numeric(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('sourceLocation.name')
                    ->label('Source')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('destinationLocation.name')
                    ->label('Destination')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('distance')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->button(),
                Tables\Actions\DeleteAction::make()->button()
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListDistances::route('/'),
            'create' => Pages\CreateDistance::route('/create'),
            'edit' => Pages\EditDistance::route('/{record}/edit'),
        ];
    }
}

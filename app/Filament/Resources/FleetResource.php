<?php

namespace App\Filament\Resources;

use App\Enums\FleetPermitTypeEnum;
use App\Enums\FleetStatusEnum;
use App\Filament\Resources\FleetResource\Pages;
use App\Filament\Resources\FleetResource\RelationManagers;
use App\Models\Distance;
use App\Models\Fleet;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class FleetResource extends Resource
{
    protected static ?string $model = Fleet::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 4;

    protected static ?string $navigationGroup = 'Manage';

    public static function getNavigationBadge() : ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('owned_by')
                    ->relationship('owner', 'name')
                    ->required(),
                Forms\Components\Select::make('driven_by')
                    ->relationship('driver', 'name'),
                Forms\Components\Select::make('category_id')
                    ->relationship('category', 'name')
                    ->required(),
                Forms\Components\TextInput::make('registration_num')
                    ->unique(Fleet::class, 'registration_num', ignoreRecord: true)
                    ->required(),
                Forms\Components\Select::make('permit_type')
                    ->options([
                        'All India' => FleetPermitTypeEnum::ALL_INDIA->value,
                        'All Assam' => FleetPermitTypeEnum::ALL_ASSAM->value,
                    ])
                    ->required(),
                Forms\Components\DatePicker::make('insurance_expiry')
                    ->required(),
                Forms\Components\DatePicker::make('pollution_expiry')
                    ->required(),
                Forms\Components\DatePicker::make('fitness_expiry')
                    ->required(),
                Forms\Components\Select::make('current_location')
                    ->label('Current location')
                    ->relationship('currentLocation', 'name')
                    ->searchable()
                    ->required(),
                Forms\Components\TextInput::make('max_height')
                    ->label('Max height (cm)')
                    ->numeric()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('available_height', $state);
                    }),
                Forms\Components\TextInput::make('max_length')
                    ->label('Max length (cm)')
                    ->numeric()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('available_length', $state);
                    }),
                Forms\Components\TextInput::make('max_width')
                    ->label('Max width (cm)')
                    ->numeric()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $set('available_width', $state);
                    }),
                Forms\Components\TextInput::make('available_height')
                    ->label('Available height (cm)')
                    ->dehydrated()
                    ->readOnly(),
                Forms\Components\TextInput::make('available_length')
                    ->label('Available length (cm)')
                    ->numeric()
                    ->readOnly(),
                Forms\Components\TextInput::make('available_width')
                    ->label('Available width (cm)')
                    ->numeric()
                    ->readOnly(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Available' => FleetStatusEnum::AVAILABLE->value,
                        'Assigned' => FleetStatusEnum::ASSIGNED->value,
                    ])
                    ->default('Available')
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('registration_num')
                    ->label('Registration no')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('owner.name')
                    ->label('Owner')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('driver.name')
                    ->label('Driver'),
                Tables\Columns\TextColumn::make('category.name')
                    ->label('Category')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('permit_type')
                    ->label('Permit')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('currentLocation.name')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('available_height')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('available_length')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('available_width')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('max_height')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('max_length')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('max_width')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('insurance_expiry')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('pollution_expiry')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('fitness_expiry')
                    ->date()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
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
            'index' => Pages\ListFleets::route('/'),
            'create' => Pages\CreateFleet::route('/create'),
            'edit' => Pages\EditFleet::route('/{record}/edit'),
        ];
    }
}

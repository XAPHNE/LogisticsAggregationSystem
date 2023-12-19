<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatusEnum;
use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Distance;
use App\Models\Location;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\Cast\Double;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    protected static ?int $navigationSort = 5;

    protected static ?string $navigationGroup = 'Manage';

    public static function getNavigationBadge() : ?string
    {
        return (string) static::getModel()::count();
    }

    public static function form(Form $form): Form
    {
        $commonSourceLocations = Distance::all()
            ->unique('source_location')
            ->pluck('source_location');
        $selectedSourceLocation = session('sourceLocation');
        $commonDestinationLocations = Distance::all()
            ->where('source_location', $selectedSourceLocation)
            ->unique('destination_location')
            ->pluck('destination_location');
        return $form
            ->schema([
                Forms\Components\Select::make('source_location')
                    ->label('Source')
//                    ->relationship('sourceLocation', 'name')
                    ->options(Location::all()->whereIn('id', $commonSourceLocations)->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        session(['sourceLocation' => $state]);
                    }),
                Forms\Components\Select::make('destination_location')
                    ->label('Destination')
//                    ->relationship('destinationLocation', 'name')
                    ->options(Location::all()->whereIn('id', $commonDestinationLocations)->pluck('name', 'id'))
                    ->searchable()
                    ->required()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        session(['destinationLocation' => $state]);

                        // Calculate distance when both source_location and destination_location are set
                        $sourceLocation = session('sourceLocation');
                        $destinationLocation = session('destinationLocation');
                        if (!empty($sourceLocation) && !empty($destinationLocation)) {
                            $distance = self::calculateDistance($sourceLocation, $destinationLocation);
                            $set('distance', $distance);
                            session(['distance' => $distance]);
                        }
                    }),
                Forms\Components\DatePicker::make('load_at')
                    ->label('Date')
                    ->required(),
                Forms\Components\TextInput::make('distance')
                    ->readOnly()
                    ->default(session('distance')),
                Forms\Components\TextInput::make('weight')
                    ->numeric()
                    ->live(onBlur: true)
                    ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                        if ($operation !== 'create') {
                            return;
                        }
                        $price = (session('distance') * 4) + ($state * 2);
                        $price = max($price, 2000);
                        $set('price', $price);
                    })
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->readOnly(),
                Forms\Components\Select::make('status')
                    ->options([
                        'Open' => OrderStatusEnum::OPEN->value,
                        'Accepted' => OrderStatusEnum::ACCEPTED->value,
                        'Transit' => OrderStatusEnum::TRANSIT->value,
                        'Completed' => OrderStatusEnum::COMPLETED->value,
                        'Cancelled' => OrderStatusEnum::CANCELLED->value,
                    ])->default('Open'),
                Forms\Components\Select::make('order_placed_by')
                    ->relationship('placedBy', 'name')
                    ->required(),
            ]);
    }

    private static function calculateDistance(string $sourceLocation, string $destinationLocation): ?float
    {
        return Distance::query()
            ->where('source_location', '=', $sourceLocation)
            ->where('destination_location', '=', $destinationLocation)
            ->value('distance');
    }


    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                //
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}

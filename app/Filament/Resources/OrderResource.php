<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use App\Models\ProductSize;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\SelectColumn;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Pedido';

    protected static ?string $pluralModelLabel = 'Pedidos';

    protected static ?string $navigationLabel = 'Pedidos';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Información del Pedido')
                    ->schema([
                        TextInput::make('id')->disabled()->label('ID Pedido'),
                        Select::make('user_id')
                            ->label('Cliente')
                            ->relationship('user', 'name')
                            ->disabled(fn (string $operation): bool => $operation !== 'create')
                            ->dehydrated(),
                        
                        // ← TOTAL REACTIVO AQUÍ (SOLO UNA VEZ)
                        TextInput::make('total_amount')
                            ->label('Total')
                            ->numeric()
                            ->prefix('€')
                            ->live()
                            ->disabled()
                            ->dehydrated(false) // Service lo calcula
                            ->afterStateUpdated(function (callable $set, $get) {
                                $items = $get('items') ?? [];
                                $total = collect($items)->sum(fn($item) => 
                                    ($item['quantity'] ?? 0) * ($item['price'] ?? 0)
                                );
                                $set('total_amount', $total);
                            }),
                        
                        Select::make('status')
                            ->options([
                                'pendiente' => 'Pendiente',
                                'pagado' => 'Pagado',
                                'enviado' => 'Enviado',
                                'cancelado' => 'Cancelado',
                            ])->required(),
                    ])->columns(2),

                Section::make('Productos Comprados')
                    ->schema([
                         Repeater::make('items')
                            ->relationship()  // ← VOLVER A PONER
                            ->defaultItems(1)
                            ->schema([
                                Select::make('product_id')
                                    ->label('Producto')
                                    ->options(\App\Models\Product::pluck('name', 'id'))
                                    ->searchable()
                                    ->reactive()
                                    ->afterStateUpdated(function ($state, callable $set) {
                                        $product = \App\Models\Product::find($state);
                                        $set('price', $product?->price ?? 0);
                                        $set('size', null);
                                    })
                                    ->required(),

                                Select::make('size')
                                    ->label('Talla')
                                    ->options(function ($get) {
                                        $productId = $get('product_id');
                                        if (!$productId) return [];
                                        return \App\Models\ProductSize::where('product_id', $productId)
                                            ->where('stock', '>', 0)
                                            ->orderBy('size')
                                            ->get()
                                            ->mapWithKeys(fn($size) => [
                                                $size->size => "{$size->size} (Stock: {$size->stock})"
                                            ])->toArray();
                                    })
                                    ->reactive()
                                    ->searchable()
                                    ->required(),

                                TextInput::make('quantity')
                                    ->numeric()
                                    ->minValue(1)
                                    ->live()
                                    ->required(),

                                TextInput::make('price')
                                    ->numeric()
                                    ->prefix('€')
                                    ->disabled()
                                    ->dehydrated()  // ← IMPORTANTE: guarda aunque disabled
                                    ->required(),
                            ])
                            ->columns(4)
                            ->addable(fn (string $operation) => $operation === 'create')
                            ->deletable(fn (string $operation) => $operation === 'create'),
                    ]),
            ]);
    }

    
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Pedido #')->sortable(),
                TextColumn::make('user.name')->label('Cliente')->searchable(),
                TextColumn::make('total_amount')
                ->label('Total')
                ->money('EUR')
                ->sortable(),
                SelectColumn::make('status')
                ->label('Estado')
                ->options([
                    'pendiente' => 'Pendiente',
                    'pagado' => 'Pagado',
                    'enviado' => 'Enviado',
                    'cancelado' => 'Cancelado',
                ]),
                TextColumn::make('created_at')
                ->label('Fecha')
                ->dateTime('d/m/Y H:i')
                ->sortable(),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status')
                ->options([
                    'pendiente' => 'Pendiente',
                    'pagado' => 'Pagado',
                ]),
            ])
            
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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

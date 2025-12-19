<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use App\Models\ProductSize;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Producto';

    protected static ?string $pluralModelLabel = 'Productos';

    protected static ?string $navigationLabel = 'Productos';


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->label('Nombre')
                            ->maxLength(255)
                            ->live(onBlur:true)
                            ->afterStateUpdated(function (Set $set, $state){
                                $set('slug', Str::slug($state));
                            }),
                        TextInput::make('slug')
                            ->label('slug(Automático)')
                            //->unique(ignoreRecord: true)
                            ->readOnly(),
                        TextArea::make('description')
                            ->label('Descripción')
                            ->required(),
                        Select::make('category')
                            ->label('Categoria')
                            ->options([
                                'deporte' => 'Deporte',
                                'casual' => 'Casual',
                                'botas' => 'Botas',
                            ])
                            ->required(),
                        TextInput::make('price')
                            ->label('Precio (EUR)')
                            ->required()
                            ->numeric()
                            ->prefix('€')
                            ->inputMode('decimal')
                            ->step(0.01)
                            ->placeholder('10.00'),
                        Repeater::make('sizes')
                            ->relationship() 
                            ->schema([
                                TextInput::make('size')
                                    ->label('Talla')
                                    ->required()
                                    ->maxLength(10),
                                TextInput::make('stock')
                                    ->numeric()
                                    ->required()
                                    ->default(0),
                            ])
                            ->columns(2)
                            ->label('Gestión de Tallas y Stock'),
                        Toggle::make('is_active')
                            ->label('Producto activo')
                            ->default(true)
                            ->inline(false),
                        FileUpload::make('images')
                            ->label('Imagenes')
                            ->multiple()
                            ->image()
                            ->reorderable()
                            ->appendFiles()
                            ->directory('products')
                            ->visibility('public')
                            ->openable(),


                    ])

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Producto'),
                TextColumn::make('price')
                    ->label('Precio'),
                TextColumn::make('stock')
                    ->label('Unds Stock'),
                ImageColumn::make('images')
                    ->label('Imagenes')
                    ->circular()
                    ->stacked()
                    ->limit(4),
                IconColumn::make('is_active')
                    ->label('Esta activo')
                    ->boolean()
            ])
            ->filters([
                SelectFilter::make('gender')
                    ->options([
                        'hombre' => 'Hombre',
                        'mujer' => 'Mujer',
                        'unisex' => 'Unisex',
                    ]),
                SelectFilter::make('category')
                    ->options([
                        'deporte' => 'Deporte',
                        'casual' => 'Casual',
                        'botas'  => 'Botas',
                    ]),
                SelectFilter::make('price')
                    ->form([
                        TextInput::make('min_price')
                            ->numeric()
                            ->label('Precio mínimo'),
                        TextInput::make('max_price')
                            ->numeric()
                            ->label('Precio máx.'),
                    ])
                    ->query(function (Builder $query, array $data): Builder{
                        return $query
                            ->when($data['min_price'], fn ($query, $price) =>$query->where('price', '>=', $price))
                            ->when($data['max_price'], fn ($query, $price) =>$query->where('price', '<=', $price));
                    }),
                SelectFilter::make('Size')
                    ->label('talla')
                    ->options(ProductSize::distinct()->pluck('size', 'size')->toArray())
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when($data['value'], function ($query, $size){
                            $query->whereHas('sizes', fn ($q) => $q->where('size', $size));
                        });
                    }),



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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

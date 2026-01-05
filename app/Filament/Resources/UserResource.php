<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Dom\Text;
use Filament\Forms;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;


class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $modelLabel = 'Usuario';

    protected static ?string $pluralModelLabel = 'Usuarios';

    protected static ?string $navigationGroup = 'Gestión de Usuarios';

    protected static ?string $navigationLabel = 'Usuarios';

    protected static ?int $navigationSort = 1;


    public static function form(Form $form): Form
    {
        return $form
            ->schema([
            Forms\Components\TextInput::make('nombre')
                ->required()
                ->maxLength(255)
                ->label('Nombre'),
                
            Forms\Components\TextInput::make('apellidos')
                ->required()
                ->maxLength(255)
                ->label('Apellidos'),
                
            Forms\Components\TextInput::make('dni')
                ->required()
                ->maxLength(9)
                ->unique(ignoreRecord: true)
                ->label('DNI/NIE')
                ->placeholder('12345678A')
                ->rule('regex:/^[0-9]{8}[A-Z]$/'),
                
            Forms\Components\TextInput::make('email')
                ->email()
                ->required()
                ->maxLength(255)
                ->unique(ignoreRecord: true)
                ->label('Email'),
                
            Forms\Components\TextInput::make('password')
                ->password()
                ->required(fn ($livewire) => $livewire instanceof CreateRecord)
                ->dehydrated(fn ($state) => filled($state))
                ->maxLength(255)
                ->label('Contraseña')
                ->helperText('Dejar vacío para mantener la contraseña actual'),
                
            Forms\Components\Select::make('roles')
                ->relationship('roles', 'name')
                ->multiple()
                ->preload()
                ->label('Roles'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
            Tables\Columns\TextColumn::make('id')
                ->sortable()
                ->label('ID'),
                
            Tables\Columns\TextColumn::make('nombre')
                ->searchable()
                ->sortable()
                ->label('Nombre'),
                
            Tables\Columns\TextColumn::make('apellidos')
                ->searchable()
                ->sortable()
                ->label('Apellidos'),
                
            Tables\Columns\TextColumn::make('dni')
                ->searchable()
                ->label('DNI'),
                
            Tables\Columns\TextColumn::make('email')
                ->searchable()
                ->sortable()
                ->label('Email'),
                
            Tables\Columns\TextColumn::make('roles.name')
                ->badge()
                ->label('Roles'),
                
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime('d/m/Y H:i')
                ->sortable()
                ->label('Registrado')
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            //
        ])
        ->actions([
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name'), 
                Forms\Components\TextInput::make('email') 
                    ->email(), 
                Forms\Components\TextInput::make('password')
                ->password() // Lozinka će biti maskirana prilikom unosa
                ->maxLength(255)
                ->dehydrateStateUsing(fn ($state) => Hash::make($state)) // Hash-ovanje lozinke pre čuvanja u bazi
                ->dehydrated(fn ($state) => filled($state)), // Lozinka se dehidrira samo ako je popunjena
                Forms\Components\Select::make('roles') 
                    ->preload()
                    ->multiple()
                    ->relationship('roles', 'name')
                    ->columnSpan('full'), 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable(), 
                Tables\Columns\TextColumn::make('email'),
                Tables\Columns\TextColumn::make('roles.name'), 
                Tables\Columns\TextColumn::make('created_at') 
                    ->dateTime(), 
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                // Tables\Actions\BulkActionGroup::make([
                //     Tables\Actions\DeleteBulkAction::make(),
                // ]),
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

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}

<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Filters\Filter;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $recordTitleAttribute = 'name';

    protected static array $statuses = [
        'in stock' => 'in stock',
        'sold out' => 'sold out',
        'coming soon' => 'coming soon',
    ];

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Wizard::make([
                    Forms\Components\Wizard\Step::make('Main data')
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                // ->disabledOn('edit')  -> it can not be edited
                                ->unique(ignoreRecord: true),
                            Forms\Components\TextInput::make('price')
                                ->required(),
                        ]),
                    Forms\Components\Wizard\Step::make('Additional data')
                        ->schema([
                            Forms\Components\Radio::make('status')
                                ->options(self::$statuses),
                            Forms\Components\Select::make('category_id')
                                ->relationship('category', 'name'),
                        ]),
                ]) 
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()->sortable(), 
                Tables\Columns\TextColumn::make('price')
                    ->searchable()
                    ->sortable()
                    ->money('eur')
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
                Tables\Columns\TextColumn::make('status')->badge()->color(fn (string $state): string => match ($state) {
                    'in stock' => 'primary',
                    'sold out' => 'danger',
                    'coming soon' => 'info',
                }),
                Tables\Columns\TextColumn::make('category.name'),
                Tables\Columns\TextColumn::make('tags.name')->badge(),
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
                Tables\Filters\SelectFilter::make('status')
                ->options(self::$statuses),
                Tables\Filters\SelectFilter::make('category')
                ->relationship('category', 'name'),
                Tables\Filters\Filter::make('created_at')
                    ->form([
                        Forms\Components\DatePicker::make('created_from'),
                        Forms\Components\DatePicker::make('created_until'),
                    ])
                    ->query(function (Builder $query, array $data): Builder {
                        return $query
                            ->when(
                                $data['created_from'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '>=', $date),
                            )
                            ->when(
                                $data['created_until'],
                                fn (Builder $query, $date): Builder => $query->whereDate('created_at', '<=', $date),
                            );
                    }),
                    Filter::make('price_greater_than')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Price Greater Than')
                            ->numeric()
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            return $query->where('price', '>', $data['value']);
                        }
                        return $query;
                    }),
                
                Filter::make('price_less_than')
                    ->form([
                        Forms\Components\TextInput::make('value')
                            ->label('Price Less Than')
                            ->numeric()
                            ->required(),
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (isset($data['value'])) {
                            return $query->where('price', '<', $data['value']);
                        }
                        return $query;
                    }),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

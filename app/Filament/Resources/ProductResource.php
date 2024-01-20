<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Models\Client;
use App\Models\Company;
use App\Models\Product;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                Forms\Components\TextInput::make('title')
                                    ->maxValue(50)
                                    ->required()
                                    ->columnSpanFull(),

                                Forms\Components\Select::make('company_id')
                                    ->label('Company')
                                    ->options(Company::pluck('title', 'id'))
                                    ->required()
                                    ->reactive(),

                                Forms\Components\Select::make('client_id')
                                    ->label('Client')
                                    ->disabled(fn (Get $get) : bool => ! filled($get('company_id')))
                                    ->options(fn(Get $get) => Client::where('company_id', (int) $get('company_id'))->pluck('name', 'id'))
                                    ->required(),

                                Forms\Components\MarkdownEditor::make('description')
                                    ->columnSpanFull()
                            ])->columns(2)
                    ])->columnSpanFull()
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('title'),

                Tables\Columns\TextColumn::make('company.title'),

                Tables\Columns\TextColumn::make('client.name'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }
}

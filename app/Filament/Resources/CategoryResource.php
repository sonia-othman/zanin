<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CategoryResource\Pages;
use App\Models\Category;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static ?string $navigationIcon = 'heroicon-o-folder';

    protected static ?string $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        TextInput::make('slug')
                            ->required()
                            ->maxLength(255)
                            ->unique(Category::class, 'slug', ignoreRecord: true)
                            ->rules(['alpha_dash'])
                            ->helperText('URL-friendly version of the name'),
                    ]),

                Tabs::make('Translations')
                    ->tabs([
                        Tab::make('English')
                            ->schema([
                                TextInput::make('name_en')
                                    ->label('Name (EN)')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                        $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),
                            ]),
                        Tab::make('Kurdish')
                            ->schema([
                                TextInput::make('name_ku')
                                    ->label('Name (KU)')
                                    ->required()
                                    ->maxLength(255),
                            ]),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('translation.name')
                ->label('Name (EN)')
                ->sortable()
                ->searchable() // simple true, no closure
                ->getStateUsing(fn ($record) => $record->translation('en')->name ?? ''),


                TextColumn::make('slug')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('articles_count')
                    ->counts('articles')
                    ->badge()
                    ->color('success'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
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
            ->defaultSort('name', 'asc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCategories::route('/'),
            'create' => Pages\CreateCategory::route('/create'),
            'edit' => Pages\EditCategory::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }
}

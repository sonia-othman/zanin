<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
use App\Models\SubCategory;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use FilamentTiptapEditor\TiptapEditor;
use FilamentTiptapEditor\Enums\TiptapOutput;

class ArticleResource extends Resource
{
    protected static ?string $model = Article::class;
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Article Information')
                    ->schema([
                        Grid::make(2)
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Article::class, 'slug', ignoreRecord: true)
                                    ->rules(['alpha_dash'])
                                    ->helperText('URL-friendly version of the title'),
                            ]),
                          
                            Select::make('sub_category_id')
                                ->label('Subcategory')
                                ->required()
                                ->options(function () {
                                    return SubCategory::with([
                                        'category' => function ($query) {
                                            $query->with(['translations' => function ($q) {
                                                $q->where('language', 'en');
                                            }]);
                                        },
                                        'translations' => function ($query) {
                                            $query->where('language', 'en');
                                        }
                                    ])
                                    ->get()
                                    ->mapWithKeys(function ($sub) {
                                        // Get category name using the name() method
                                        $categoryName = $sub->category?->name('en') ?? 'Unknown Category';
                                        
                                        // Get subcategory name using the name() method
                                        $subName = $sub->name('en') ?? 'Unknown Subcategory';
                                        
                                        return [$sub->id => "{$categoryName} > {$subName}"];
                                    });
                                })
                                ->searchable()
                                ->preload(),

                        TagsInput::make('tags')
                            ->suggestions(Tag::pluck('name')->toArray())
                            ->splitKeys(['Enter', ','])
                            ->separator(',')
                            ->saveRelationshipsUsing(function ($record, $state) {
                                $record->tags()->sync(
                                    collect($state)->map(function ($name) {
                                        return Tag::firstOrCreate(['name' => $name], [
                                            'slug' => Str::slug($name),
                                        ])->id;
                                    })
                                );
                            }),

                        FileUpload::make('image')
                            ->image()
                            ->directory('articles')
                            ->disk('public')
                            ->imageEditor()
                            ->imageEditorAspectRatios(['16:9', '4:3', '1:1'])
                            ->maxSize(5120)
                            ->helperText('Featured image for the article (max 5MB)'),
                    ])
                    ->columns(1),

                Section::make('Multilingual Content')
                    ->schema([
                        Tabs::make('Translations')
                            ->tabs([
                                Tab::make('English')
                                    ->schema([
                                        TextInput::make('title_en')
                                            ->label('Title (EN)')
                                            ->required()
                                            ->maxLength(255)
                                            ->live(onBlur: true)
                                            ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                                $context === 'create' ? $set('slug', Str::slug($state)) : null
                                            ),

                                        TiptapEditor::make('content_en')
                                            ->label('Content (EN)')
                                            ->required()
                                            ->columnSpanFull()
                                            ->profile('default')
                                            ->disk('public')
                                            ->directory('articles/attachments')
                                            ->output(TiptapOutput::Html),
                                    ]),
                                Tab::make('Kurdish')
                                    ->schema([
                                        TextInput::make('title_ku')
                                            ->label('Title (KU)')
                                            ->required()
                                            ->maxLength(255),

                                        TiptapEditor::make('content_ku')
                                            ->label('Content (KU)')
                                            ->required()
                                            ->columnSpanFull()
                                            ->profile('default')
                                            ->disk('public')
                                            ->directory('articles/attachments')
                                            ->output(TiptapOutput::Html),
                                    ]),
                            ])
                            ->columnSpanFull(),
                    ]),

                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ])
            ->columns(1);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->size(60)
                    ->circular(),

                TextColumn::make('translations.title')
                ->label('Title (EN)')
                ->sortable()
                ->searchable('searchTitle')  // <-- Use the scope method name here
                ->getStateUsing(fn ($record) => $record->translation('en')->title ?? ''),

                TextColumn::make('subCategory.name')
                    ->badge()
                    ->color('primary'),

                TextColumn::make('tags.name')
                    ->badge()
                    ->color('gray')
                    ->separator(',')
                    ->limit(20),

                TextColumn::make('user.name')
                    ->label('Author'),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
              SelectFilter::make('sub_category_id')
                ->label('Subcategory')
                ->options(function () {
                    return SubCategory::with([
                        'category' => function ($query) {
                            $query->with(['translations' => function ($q) {
                                $q->where('language', 'en');
                            }]);
                        },
                        'translations' => function ($query) {
                            $query->where('language', 'en');
                        }
                    ])
                    ->get()
                    ->mapWithKeys(function ($sub) {
                        $categoryName = $sub->category?->name('en') ?? 'Unknown Category';
                        $subName = $sub->name('en') ?? 'Unknown Subcategory';
                        return [$sub->id => "{$categoryName} > {$subName}"];
                    });
                })
                ->searchable()
                ->preload(),

                SelectFilter::make('tags')
                    ->relationship('tags', 'name')
                    ->searchable()
                    ->preload()
                    ->multiple(),
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
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListArticles::route('/'),
            'create' => Pages\CreateArticle::route('/create'),
            'edit' => Pages\EditArticle::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['slug'];
    }

    public static function getEagerLoadRelations(): array
    {
        return ['translations', 'subCategory', 'tags', 'user'];
    }
}
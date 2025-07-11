<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ArticleResource\Pages;
use App\Models\Article;
use App\Models\Category;
use App\Models\Tag;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;

// 🔥 Import TipTap Editor
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
                                TextInput::make('title')
                                    ->required()
                                    ->maxLength(255)
                                    ->live(onBlur: true)
                                    ->afterStateUpdated(fn (string $context, $state, callable $set) =>
                                        $context === 'create' ? $set('slug', Str::slug($state)) : null
                                    ),

                                TextInput::make('slug')
                                    ->required()
                                    ->maxLength(255)
                                    ->unique(Article::class, 'slug', ignoreRecord: true)
                                    ->rules(['alpha_dash'])
                                    ->helperText('URL-friendly version of the title'),
                            ]),

                        Grid::make(2)
                            ->schema([
                                Select::make('category_id')
                                    ->label('Category')
                                    ->options(Category::all()->pluck('name', 'id'))
                                    ->required()
                                    ->reactive()
                                    ->afterStateUpdated(fn ($state, callable $set) => $set('sub_category_id', null))
                                    ->dehydrated(false)
                                    ->searchable(),
                                
                                Select::make('sub_category_id')
                                    ->label('Subcategory')
                                    ->required()
                                    ->options(function (callable $get) {
                                        $categoryId = $get('category_id');
                                        return $categoryId ? \App\Models\SubCategory::where('category_id', $categoryId)->pluck('name', 'id') : [];
                                    })
                                    ->searchable(),
                            ]),

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
                            ->imageEditorAspectRatios([
                                '16:9',
                                '4:3',
                                '1:1',
                            ])
                            ->maxSize(5120)
                            ->helperText('Featured image for the article (max 5MB)'),
                    ])
                    ->columns(1),

                Section::make('Article Content')
                    ->schema([
                        // 🔥 Replace RichEditor with TipTap Editor
                        TiptapEditor::make('content')
                            ->required()
                            ->columnSpanFull()
                            ->profile('default')
                            ->tools([
                                'heading',
                                'bullet-list',
                                'ordered-list',
                                'checked-list',
                                'blockquote',
                                'hr',
                                '|',
                                'bold',
                                'italic',
                                'strike',
                                'underline',
                                'superscript',
                                'subscript',
                                'align-left',
                                'align-center',
                                'align-right',
                                '|',
                                'link',
                                'media',
                                'table', // 🎉 Table support!
                                'grid',
                                'grid-builder',
                                '|',
                                'code',
                                'code-block',
                                'source',
                            ])
                            ->disk('public')
                            ->directory('articles/attachments')
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/gif', 'image/webp'])
                            ->maxFileSize(5120)
                            ->output(TiptapOutput::Html)
                            ->helperText('Write your article content here. Full table support and advanced formatting available.'),
                    ])
                    ->columns(1),

                Forms\Components\Hidden::make('user_id')
                    ->default(auth()->id()),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image')
                    ->disk('public')
                    ->size(60)
                    ->circular(),

                TextColumn::make('title')
                    ->searchable()
                    ->sortable()
                    ->weight('bold')
                    ->limit(40),

                TextColumn::make('subCategory.name')
                    ->badge()
                    ->color('primary')
                    ->searchable(),

                TextColumn::make('tags.name')
                    ->badge()
                    ->color('gray')
                    ->separator(',')
                    ->limit(20),

                TextColumn::make('user.name')
                    ->label('Author')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->since(),
            ])
            ->filters([
                SelectFilter::make('subCategory')
                    ->relationship('subCategory', 'name')
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
        return ['title', 'content'];
    }

    public static function getEagerLoadRelations(): array
    {
        return ['subCategory', 'tags', 'user'];
    }
}
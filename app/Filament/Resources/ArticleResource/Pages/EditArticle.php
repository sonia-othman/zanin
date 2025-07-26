<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Models\ArticleTranslation;
use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ArticleResource;

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Fix: Add ->first() to get the actual model instance
        $data['title_en'] = $this->record->translation('en')->first()?->title;
        $data['content_en'] = $this->record->translation('en')->first()?->content;

        $data['title_ku'] = $this->record->translation('ku')->first()?->title;
        $data['content_ku'] = $this->record->translation('ku')->first()?->content;

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        // Update English
        $record->translations()->updateOrCreate(
            ['language' => 'en'],
            ['title' => $this->data['title_en'], 'content' => $this->data['content_en'], 'excerpt' => $this->data['excerpt_en']]
        );

        // Update Kurdish
        $record->translations()->updateOrCreate(
            ['language' => 'ku'],
            ['title' => $this->data['title_ku'], 'content' => $this->data['content_ku'], 'excerpt' => $this->data['excerpt_ku']]
        );
    }
}
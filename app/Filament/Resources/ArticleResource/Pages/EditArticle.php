<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use Filament\Resources\Pages\EditRecord;
use App\Filament\Resources\ArticleResource;
use Tiptap\Editor; // ✅ TipTap HTML renderer

class EditArticle extends EditRecord
{
    protected static string $resource = ArticleResource::class;

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $enTranslation = $this->record->translations()->where('language', 'en')->first();
        $kuTranslation = $this->record->translations()->where('language', 'ku')->first();

        $data['title_en'] = $enTranslation?->title ?? '';
        $data['content_en'] = $enTranslation?->content ?? ''; // JSON
        $data['excerpt_en'] = $enTranslation?->excerpt ?? '';

        $data['title_ku'] = $kuTranslation?->title ?? '';
        $data['content_ku'] = $kuTranslation?->content ?? ''; // JSON
        $data['excerpt_ku'] = $kuTranslation?->excerpt ?? '';

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        $record->translations()->updateOrCreate(
            ['language' => 'en'],
            [
                'title' => $this->data['title_en'] ?? '',
                'content' => is_array($this->data['content_en'])
                    ? json_encode($this->data['content_en'])
                    : $this->data['content_en'],
                'excerpt' => $this->data['excerpt_en'] ?? ''
            ]
        );

        $record->translations()->updateOrCreate(
            ['language' => 'ku'],
            [
                'title' => $this->data['title_ku'] ?? '',
                'content' => is_array($this->data['content_ku'])
                    ? json_encode($this->data['content_ku'])
                    : $this->data['content_ku'],
                'excerpt' => $this->data['excerpt_ku'] ?? ''
            ]
        );
    }
}
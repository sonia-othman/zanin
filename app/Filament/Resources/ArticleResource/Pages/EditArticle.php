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
        $data['content_en'] = $enTranslation?->content ?? '';
        $data['excerpt_en'] = $enTranslation?->excerpt ?? '';

        $data['title_ku'] = $kuTranslation?->title ?? '';
        $data['content_ku'] = $kuTranslation?->content ?? '';
        $data['excerpt_ku'] = $kuTranslation?->excerpt ?? '';

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;

        $editor = new Editor(); // ✅ Create TipTap editor instance

        // Convert JSON to HTML (English)
        $htmlEn = is_array($this->data['content_en']) 
            ? $editor->setContent($this->data['content_en'])->getHTML() 
            : $this->data['content_en'];

        // Convert JSON to HTML (Kurdish)
        $htmlKu = is_array($this->data['content_ku']) 
            ? $editor->setContent($this->data['content_ku'])->getHTML() 
            : $this->data['content_ku'];

        // Save English
        $record->translations()->updateOrCreate(
            ['language' => 'en'],
            [
                'title' => $this->data['title_en'] ?? '',
                'content' => $htmlEn,
                'excerpt' => $this->data['excerpt_en'] ?? ''
            ]
        );

        // Save Kurdish
        $record->translations()->updateOrCreate(
            ['language' => 'ku'],
            [
                'title' => $this->data['title_ku'] ?? '',
                'content' => $htmlKu,
                'excerpt' => $this->data['excerpt_ku'] ?? ''
            ]
        );
    }
}

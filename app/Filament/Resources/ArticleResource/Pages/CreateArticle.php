<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Models\ArticleTranslation;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ArticleResource;
use Tiptap\Editor; // ✅ TipTap JSON to HTML converter

class CreateArticle extends CreateRecord
{
    protected static string $resource = ArticleResource::class;

    protected function afterCreate(): void
    {
        $this->saveTranslations();
    }

    protected function saveTranslations(): void
    {
        $data = $this->form->getState();
        $editor = new Editor(); // ✅ Initialize TipTap renderer

        foreach (['en', 'ku'] as $lang) {
            // Convert JSON to HTML if it's array (TipTap content)
            $htmlContent = is_array($data["content_{$lang}"])
                ? $editor->setContent($data["content_{$lang}"])->getHTML()
                : $data["content_{$lang}"];

            ArticleTranslation::updateOrCreate(
                [
                    'article_id' => $this->record->id,
                    'language' => $lang,
                ],
                [
                    'title' => $data["title_{$lang}"],
                    'excerpt' => $data["excerpt_{$lang}"],
                    'content' => $htmlContent, // ✅ Save as HTML
                ]
            );
        }
    }
}

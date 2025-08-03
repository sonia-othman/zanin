<?php

namespace App\Filament\Resources\ArticleResource\Pages;

use App\Models\ArticleTranslation;
use Filament\Resources\Pages\CreateRecord;
use App\Filament\Resources\ArticleResource;

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

       foreach (['en', 'ku'] as $lang) {
    ArticleTranslation::updateOrCreate(
        [
            'article_id' => $this->record->id,
            'language' => $lang,
        ],
        [
            'title' => $data["title_{$lang}"],
            'excerpt' => $data["excerpt_{$lang}"],
            'content' => is_array($data["content_{$lang}"])
                ? json_encode($data["content_{$lang}"])
                : $data["content_{$lang}"], 
                
        ]
    );
}
    }
}   
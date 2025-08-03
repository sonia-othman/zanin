<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\CategoryTranslation;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterCreate(): void
    {
        $this->saveTranslations();
    }

protected function saveTranslations(): void
{
    $data = $this->form->getState();

    foreach (['en', 'ku'] as $lang) {
        CategoryTranslation::updateOrCreate(
            [
                'category_id' => $this->record->id,
                'language' => $lang,
            ],
            [
                'title' => $data["title_{$lang}"],
                'excerpt' => $data["excerpt_{$lang}"] ?? null,
                'content' => is_array($data["content_{$lang}"])
                    ? json_encode($data["content_{$lang}"])
                    : $data["content_{$lang}"],
            ]
        );
    }
}
}
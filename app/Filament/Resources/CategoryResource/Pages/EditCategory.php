<?php

namespace App\Filament\Resources\CategoryResource\Pages;

use App\Filament\Resources\CategoryResource;
use App\Models\CategoryTranslation;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    protected static string $resource = CategoryResource::class;

    protected function afterSave(): void
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
                    'name' => $data["name_{$lang}"],
                ]
            );
        }
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $translations = $this->record->translations->keyBy('language');

        $data['name_en'] = $translations['en']->name ?? null;
        $data['name_ku'] = $translations['ku']->name ?? null;

        return $data;
    }
}

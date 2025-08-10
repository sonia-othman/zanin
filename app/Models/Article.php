<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

/**
 * @property \App\Models\ArticleTranslation|null $translation
 */
class Article extends Model
{
    use Searchable;

    protected $fillable = ['slug', 'image', 'user_id', 'sub_category_id'];

    public function translations()
    {
        return $this->hasMany(ArticleTranslation::class);
    }

    public function translation()
    {
        return $this->hasOne(ArticleTranslation::class)->where('language', app()->getLocale());
    }

    public function translationByLang($lang)
    {
        return $this->hasOne(ArticleTranslation::class)->where('language', $lang);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }

    public function subCategory()
    {
        return $this->belongsTo(\App\Models\SubCategory::class);
    }

    public function category()
    {
        return $this->hasOneThrough(
            Category::class,
            SubCategory::class,
            'category_id',
            'id',
            'sub_category_id',
            'id'
        );
    }

    public function author()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    // Updated for Meilisearch - include all translatable content
    public function toSearchableArray()
    {
        // Load all translations
        $this->load(['translations', 'subCategory.translations', 'author']);
        
        $searchableData = [
            'id' => $this->id,
            'slug' => $this->slug,
            'views' => $this->views,
            'created_at' => $this->created_at?->timestamp,
            'user_id' => $this->user_id,
            'sub_category_id' => $this->sub_category_id,
            'author_name' => $this->author?->name,
        ];

        // Add all translations to make them searchable
        $translations = [];
        foreach ($this->translations as $translation) {
            $translations[$translation->language] = [
                'title' => $translation->title,
                'excerpt' => $translation->excerpt,
                'content' => $this->stripJsonContent($translation->content), // Strip HTML/JSON for search
            ];
        }
        $searchableData['translations'] = $translations;

        // Add subcategory info
        if ($this->subCategory) {
            $subCategoryTranslations = [];
            foreach ($this->subCategory->translations as $trans) {
                $subCategoryTranslations[$trans->language] = $trans->name;
            }
            $searchableData['subcategory'] = $subCategoryTranslations;
        }

        return $searchableData;
    }

    public function searchableAs(): string
    {
        return 'articles';
    }

    // Helper method to extract plain text from JSON content for search indexing
    private function stripJsonContent($content)
    {
        if (empty($content)) return '';
        
        if (is_string($content)) {
            $decoded = json_decode($content, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $content = $decoded;
            } else {
                return strip_tags($content); // Fallback for HTML content
            }
        }

        if (!is_array($content) || !isset($content['content'])) {
            return '';
        }

        return $this->extractTextFromBlocks($content['content']);
    }

    private function extractTextFromBlocks($blocks)
    {
        $text = '';
        
        foreach ($blocks as $block) {
            if (isset($block['content'])) {
                foreach ($block['content'] as $element) {
                    if (isset($element['text'])) {
                        $text .= $element['text'] . ' ';
                    }
                    if (isset($element['content'])) {
                        $text .= $this->extractTextFromBlocks($element['content']) . ' ';
                    }
                }
            }
        }
        
        return trim($text);
    }

    // Scope for backward compatibility, but recommend using Scout search
    public function scopeSearchTitle($query, $search)
    {
        $lang = app()->getLocale();
        $query->whereHas('translations', function ($q) use ($search, $lang) {
            $q->where('language', $lang)->where('title', 'like', "%{$search}%");
        });
    }

    public function getTitleEnAttribute()
    {
        return $this->translations->where('language', 'en')->first()?->title;
    }
}
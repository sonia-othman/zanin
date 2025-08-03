<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['slug'];

    public function subCategories()
    {
        return $this->hasMany(SubCategory::class);
    }

    public function translations()
    {
        return $this->hasMany(CategoryTranslation::class);
    }

    public function translation($lang = null)
    {
        $lang = $lang ?? app()->getLocale();
        return $this->hasOne(CategoryTranslation::class)->where('language', $lang);
    }

    public function name($lang = 'en')
    {
        return $this->translations->where('language', $lang)->first()?->name ?? null;
    }

    public function articles()
{
    return $this->hasManyThrough(
        \App\Models\Article::class,
        \App\Models\SubCategory::class,
        'category_id',       // Foreign key on SubCategory
        'sub_category_id',   // Foreign key on Article
        'id',                // Local key on Category
        'id'                 // Local key on SubCategory
    );
}

}

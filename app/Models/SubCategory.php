<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * @property string|null $name
 * @property \App\Models\SubCategoryTranslation|null $translation
 */


class SubCategory extends Model
{
    protected $fillable = ['slug', 'category_id'];

    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function articles() {
        return $this->hasMany(Article::class);
    }
    
    public function translations()
    {
        return $this->hasMany(SubCategoryTranslation::class);
    }

    public function translation($lang = 'en')
    {
        return $this->hasOne(SubCategoryTranslation::class)->where('language', $lang);
    }
    
    public function getNameAttribute()
    {
        // Fix: Add ->first() to execute the query and get the model instance
        return $this->translation(app()->getLocale())->first()?->name;
    }
    
    public function name($lang = 'en')
    {
        return $this->translations->where('language', $lang)->first()?->name ?? null;
    }
}
<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

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

    public function toSearchableArray()
    {
        return [
            'title' => $this->title,
        ];
    }

    public function searchableAs(): string
    {
        return 'articles';
    }
    // In Article.php
public function category()
{
    return $this->hasOneThrough(
        Category::class,      // final related
        SubCategory::class,   // through
        'category_id',        // FK on SubCategory to Category
        'id',                 // PK on Category
        'sub_category_id',    // FK on Article to SubCategory
        'id'                  // PK on SubCategory
    );
}


    
}

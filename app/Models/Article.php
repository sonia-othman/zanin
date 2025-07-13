<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;

class Article extends Model
{
    use Searchable;

    protected $fillable = ['title', 'slug', 'content', 'image', 'sub_category_id', 'user_id'];

    public function subCategory() {
        return $this->belongsTo(SubCategory::class);
    }

    public function tags() {
        return $this->belongsToMany(Tag::class);
    }

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
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
        Category::class,
        SubCategory::class,
        'id',            
        'id',           
        'sub_category_id',
        'category_id'     
    );
}

    
}

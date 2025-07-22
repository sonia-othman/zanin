<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::table('articles', function ($table) {
    $table->index('slug', 'idx_articles_slug');
    $table->index(['sub_category_id', 'views'], 'idx_articles_sub_category_views');
});

Schema::table('article_translations', function ($table) {
    $table->index(['language', 'title'], 'idx_article_translations_lang_title');
});

Schema::table('category_translations', function ($table) {
    $table->index(['language', 'category_id'], 'idx_category_translations_lang');
});

Schema::table('sub_category_translations', function ($table) {
    $table->index(['language', 'sub_category_id'], 'idx_subcategory_translations_lang');
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('articles', function ($table) {
    $table->dropIndex('idx_articles_slug');
    $table->dropIndex('idx_articles_sub_category_views');
});

Schema::table('article_translations', function ($table) {
    $table->dropIndex('idx_article_translations_lang_title');
});

Schema::table('category_translations', function ($table) {
    $table->dropIndex('idx_category_translations_lang');
});

Schema::table('sub_category_translations', function ($table) {
    $table->dropIndex('idx_subcategory_translations_lang');
});

    }
};

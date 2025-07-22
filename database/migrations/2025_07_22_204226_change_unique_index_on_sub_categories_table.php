<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeUniqueIndexOnSubCategoriesTable extends Migration
{
    public function up()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // Drop the old unique index on slug
            $table->dropUnique('sub_categories_slug_unique'); // name of index from error message
            
            // Add a new unique index on category_id and slug
            $table->unique(['category_id', 'slug']);
        });
    }

    public function down()
    {
        Schema::table('sub_categories', function (Blueprint $table) {
            // Reverse the changes if rollback needed
            $table->dropUnique(['category_id', 'slug']);
            $table->unique('slug');
        });
    }
}

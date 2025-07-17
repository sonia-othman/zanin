<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubCategoryTranslationsTable extends Migration
{
    public function up()
    {
        Schema::create('sub_category_translations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
            $table->string('language', 5);
            $table->timestamps();

            $table->unique(['sub_category_id', 'language']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sub_category_translations');
    }
}

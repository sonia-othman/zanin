<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::create('article_translations', function (Blueprint $table) {
        $table->id();
        $table->foreignId('article_id')->constrained()->onDelete('cascade');
        $table->string('language', 5);
        $table->string('title');
        $table->longText('content');
        $table->timestamps();

        $table->unique(['article_id', 'language']);
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('article_translations');
    }
};

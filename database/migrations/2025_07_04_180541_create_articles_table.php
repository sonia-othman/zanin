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
    Schema::create('articles', function (Blueprint $table) {
        $table->id();
        $table->string('title_en');
        $table->string('title_ku');
        $table->string('slug_en')->unique();
        $table->string('slug_ku')->unique();
        $table->longText('content_en');
        $table->longText('content_ku');
        $table->string('image')->nullable();
        $table->foreignId('sub_category_id')->constrained()->onDelete('cascade');
        $table->foreignId('user_id')->constrained()->onDelete('cascade'); // admin who added it
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};

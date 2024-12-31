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
            $table->string('title');
            $table->string('source');
            $table->string('author')->nullable();
            $table->string('category')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('content')->nullable();
            $table->date('published_at')->nullable();
            $table->timestamps();

            $table->unique(['title', 'source']);
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

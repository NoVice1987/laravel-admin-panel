<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->longText('content');
            $table->text('excerpt')->nullable();
            $table->string('template')->default('default'); // default, full-width, sidebar, etc.
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->text('meta_keywords')->nullable();
            $table->boolean('is_published')->default(true);
            $table->integer('order')->default(0);
            $table->foreignId('author_id')->constrained('admin_users')->onDelete('cascade');
            $table->timestamps();
            $table->softDeletes();
            
            $table->index('slug');
            $table->index('is_published');
            $table->index('order');
        });
    }

    public function down()
    {
        Schema::dropIfExists('pages');
    }
};

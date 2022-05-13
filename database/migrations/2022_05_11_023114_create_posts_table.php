<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->integer('category_id', false, true);
            $table->integer('user_id', false, true);
            $table->string('title');
            $table->string('summary')->nullable();
            $table->string('seo_title')->nullable();
            $table->string('seo_keywords')->nullable();
            $table->string('seo_description')->nullable();
            $table->string('thumb')->nullable()->comment('封面');
            $table->text('content');
            $table->string('original_url')->nullable()->comment('原址');
            $table->boolean('show')->default(true)->comment('是否在列表展示');
            $table->boolean('status')->default(true);
            $table->dateTime('recommend_until')->nullable()->comment('推荐截至时间');
            $table->timestamps();
            $table->index('title');
            $table->unique('original_url');
            $table->index(['status', 'show', 'created_at', 'updated_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};

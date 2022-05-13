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
    public function up()
    {
        Schema::create('visitors', function (Blueprint $table) {
            $table->id();
            $table->integer('unique_view')->default(0)->comment('唯一IP访问量');
            $table->integer('page_view')->default(0)->comment('页面点击');
            $table->integer('baidu_spider')->default(0);
            $table->integer('google_spider')->default(0);
            $table->integer('360_spider')->default(0);
            $table->integer('bing_spider')->default(0);
            $table->integer('sougo_spider')->default(0);
            $table->integer('soso_spider')->default(0);
            $table->integer('byte_spider')->default(0);
            $table->integer('other_spider')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('visitors');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('parent_id')->nullable()->default(0)->comment('父级分类ID');
            $table->string('name')->comment('分类名称');
            $table->string('description')->nullable()->comment('分类描述');
            $table->integer('level')->default(0)->comment('分类层级');
            $table->timestamps();
            $table->softDeletes();
        });

        DB::statement("ALTER TABLE `categories` comment '商品分类表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('categories');
    }
};

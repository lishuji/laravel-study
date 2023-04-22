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
        Schema::create('goods', function (Blueprint $table) {
            $table->id();
            $table->string('code')->nullable()->comment('商品编码');
            $table->string('name')->comment('商品名称');
            $table->string('description')->nullable()->comment('商品描述');
            $table->string('image')->nullable()->comment('商品图片');
            $table->integer('price')->nullable()->default(0)->comment('价格,单位分');
            $table->integer('stock')->nullable()->default(0)->comment('库存');
            $table->timestamps();
            $table->softDeletes();

            $table->index('code');
            $table->index('name');
        });

        DB::statement("ALTER TABLE `goods` comment '商品表'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('goods');
    }
};

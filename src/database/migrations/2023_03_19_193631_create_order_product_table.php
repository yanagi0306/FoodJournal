<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        Schema::create('order_product', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('注文商品ID');
            $table->unsignedBigInteger('order_id')->comment('注文ID');
            $table->unsignedBigInteger('product_master_id')->comment('商品マスタID');
            $table->integer('quantity')->comment('数量');
            $table->integer('unit_price')->comment('単価');
            $table->string('order_options', 255)->nullable()->comment('注文オプション（細かな注文要望情報を記載）');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreign('product_master_id')->references('id')->on('product_master')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE order_product = COMMENT '注文商品テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};

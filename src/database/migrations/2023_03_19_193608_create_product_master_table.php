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
        Schema::create('product_master', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('注文商品ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('category1_id')->nullable()->comment('商品カテゴリID1');
            $table->unsignedBigInteger('category2_id')->nullable()->comment('商品カテゴリID2');
            $table->unsignedBigInteger('category3_id')->nullable()->comment('商品カテゴリID3');
            $table->unsignedBigInteger('category4_id')->nullable()->comment('商品カテゴリID4');
            $table->string('product_cd', 15)->comment('商品コード');
            $table->string('product_name', 15)->comment('商品名');
            $table->integer('unit_price')->comment('単価');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['store_id', 'product_cd']);

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('category1_id')->references('id')->on('product_category')->onDelete('cascade');
            $table->foreign('category2_id')->references('id')->on('product_category')->onDelete('cascade');
            $table->foreign('category3_id')->references('id')->on('product_category')->onDelete('cascade');
            $table->foreign('category4_id')->references('id')->on('product_category')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE `product_master` COMMENT '商品マスタテーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_master');
    }
};

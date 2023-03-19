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
        Schema::create('product_category', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('商品カテゴリID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->string('cat_cd', 4)->comment('商品カテゴリコード');
            $table->string('cat_name', 8)->comment('商品カテゴリ名');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['store_id', 'cat_cd']);

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE product_category = COMMENT '商品カテゴリ'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('product_category');
    }
};

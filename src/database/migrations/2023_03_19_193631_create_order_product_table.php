<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     * @return void
     */
    public function up(): void
    {
        Schema::create('order_product', function(Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('注文商品ID');
            $table->unsignedBigInteger('order_info_id')->comment('注文ID');
            $table->string('product_cd', 30)->comment('商品コード');
            $table->string('product_name', 30)->comment('商品名');
            $table->string('category1')->comment('カテゴリ1');
            $table->string('category2')->nullable()->default(null)->comment('カテゴリ2');
            $table->string('category3')->nullable()->default(null)->comment('カテゴリ3');
            $table->string('category4')->nullable()->default(null)->comment('カテゴリ4');
            $table->string('category5')->nullable()->default(null)->comment('カテゴリ5');
            $table->integer('quantity')->comment('数量');
            $table->integer('unit_price')->comment('単価');
            $table->integer('unit_cost')->comment('理論原価');
            $table->string('order_options', 255)->nullable()->comment('注文オプション（細かな注文要望情報を記載）');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('order_info_id')->references('id')->on('order_info')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset   = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE order_product IS '注文商品マスタテーブル'");
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product');
    }
};

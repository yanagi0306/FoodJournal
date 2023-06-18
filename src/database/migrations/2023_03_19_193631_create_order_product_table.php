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
            $table->unsignedBigInteger('order_product_master_id')->comment('注文商品マスタID');
            $table->integer('quantity')->comment('数量');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['order_info_id', 'order_product_master_id']);

            // 外部キー制約の設定
            $table->foreign('order_info_id')->references('id')->on('order_info')->onDelete('cascade');
            $table->foreign('order_product_master_id')->references('id')->on('order_product_master')->onDelete('cascade');

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

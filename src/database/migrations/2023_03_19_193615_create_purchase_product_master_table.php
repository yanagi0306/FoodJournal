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
    public function up(): void
    {
        Schema::create('purchase_product_master', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('仕入商品マスタID');
            $table->unsignedBigInteger('purchase_supplier_id')->comment('仕入業者ID');
            $table->unsignedBigInteger('parent_purchase_category_id')->comment('仕入親カテゴリID');
            $table->unsignedBigInteger('purchase_category_id')->comment('仕入カテゴリID');
            $table->string('product_cd', 15)->comment('商品コード');
            $table->string('product_name', 50)->comment('商品名');
            $table->integer('unit_price')->comment('単価');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['purchase_supplier_id', 'product_cd']);

            // 外部キー制約の設定
            $table->foreign('purchase_supplier_id')->references('id')->on('purchase_supplier')->onDelete('cascade');
            $table->foreign('parent_purchase_category_id')->references('id')->on('parent_purchase_category')->onDelete('cascade');
            $table->foreign('purchase_category_id')->references('id')->on('purchase_category')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE purchase_product_master IS '仕入商品マスタテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_product_master');
    }
};

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
        Schema::create('purchase_product', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('仕入商品ID');
            $table->unsignedBigInteger('purchase_id')->comment('仕入情報ID');
            $table->unsignedBigInteger('purchase_product_master_cd')->comment('仕入商品マスタコード');
            $table->string('product_name', 50)->comment('商品名');
            $table->integer('quantity')->comment('数量');
            $table->integer('unit_price')->comment('単価');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('purchase_id')->references('id')->on('purchase')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE purchase_product IS '仕入商品テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_product');
    }
};

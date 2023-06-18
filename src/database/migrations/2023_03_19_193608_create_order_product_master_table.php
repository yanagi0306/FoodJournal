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
        Schema::create('order_product_master', function(Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('注文商品ID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('product_cd', 15)->comment('商品コード');
            $table->string('product_name', 20)->comment('商品名');
            $table->double('unit_cost')->nullable()->comment('理論原価');
            $table->integer('unit_price')->comment('単価');
            $table->string('category1', 20)->comment('商品カテゴリ1');
            $table->string('category2', 20)->nullable()->comment('商品カテゴリ2');
            $table->string('category3', 20)->nullable()->comment('商品カテゴリ3');
            $table->string('category4', 20)->nullable()->comment('商品カテゴリ4');
            $table->string('category5', 20)->nullable()->comment('商品カテゴリ5');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id',
                            'product_cd']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset   = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE order_product_master IS '注文商品マスタテーブル'");
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_product_master');
    }
};

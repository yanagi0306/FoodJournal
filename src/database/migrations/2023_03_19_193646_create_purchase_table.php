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
        Schema::create('purchase', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('仕入ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('purchase_supplier_id')->comment('仕入業者ID');
            $table->string('voucher', 15)->comment('伝票番号');
            $table->date('date')->comment('仕入日時');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['store_id', 'purchase_supplier_id', 'voucher', 'date']);

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('purchase_supplier_id')->references('id')->on('purchase_supplier')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE `purchase` COMMENT '仕入情報テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase');
    }
};

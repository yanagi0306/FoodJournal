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
        Schema::create('purchase_info', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('仕入ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedInteger('store_id')->comment('店舗ID');
            $table->unsignedInteger('purchase_supplier_id')->comment('仕入業者ID');
            $table->string('purchase_number', 20)->comment('伝票番号');
            $table->date('purchase_date')->comment('仕入日時');
            $table->integer('amount')->comment('支払額');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'store_id', 'purchase_supplier_id', 'purchase_number', 'purchase_date']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('purchase_supplier_id')->references('id')->on('purchase_supplier')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE purchase_info IS '仕入情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_info');
    }
};

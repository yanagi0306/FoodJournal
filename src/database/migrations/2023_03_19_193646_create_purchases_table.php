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
        Schema::create('purchases', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('仕入ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedInteger('store_id')->comment('店舗ID');
            $table->unsignedInteger('purchase_supplier_id')->comment('仕入業者ID');
            $table->string('purchase_number', 20)->comment('仕入伝票番号');
            $table->date('purchase_date')->comment('仕入日時');
            $table->integer('purchase_amount')->comment('仕入支払額');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'store_id', 'purchase_supplier_id', 'purchase_number']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('purchase_supplier_id')->references('id')->on('purchase_suppliers')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE purchases IS '仕入情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

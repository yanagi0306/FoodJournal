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
        Schema::create('purchase_supplier', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('仕入業者ID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('supplier_cd', 30)->comment('仕入業者コード（仕入先CD）');
            $table->string('supplier_name', 30)->comment('仕入業者名');
            $table->integer('is_no_slip_num')->nullable()->default(null)->comment('伝票番号を発行しない業者（null: 有, 1: 無）');
            $table->integer('is_hidden')->nullable()->default(null)->comment('非表示フラグ（null: 表示, 1: 非表示）');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'supplier_cd']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE purchase_supplier = COMMENT '仕入業者テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_supplier');
    }
};

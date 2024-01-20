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
        Schema::create('purchase_suppliers', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('仕入業者ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->string('supplier_code', 30)->comment('仕入業者コード（仕入先CD）');
            $table->string('name', 30)->comment('仕入業者名');
            $table->string('abbr', 6)->comment('仕入業者略名');
            $table->unsignedInteger('is_no_slip_num')->nullable()->comment('伝票番号を発行しない業者（null: 有, 1: 無）');
            $table->unsignedInteger('is_hidden')->nullable()->comment('非表示フラグ（null: 表示, 1: 非表示）');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'supplier_code']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE purchase_suppliers IS '仕入業者テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_suppliers');
    }
};

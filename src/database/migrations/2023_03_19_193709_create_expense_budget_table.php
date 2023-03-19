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
        Schema::create('expense_budget', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('予算ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('expense_category_mapping_id')->comment('支出カテゴリマッピングID');
            $table->date('budget_month')->comment('予算年月');
            $table->integer('budget_amount')->comment('予算額');
            $table->date('confirmed_date')->nullable()->comment('確定日');
            $table->integer('confirmed_amount')->nullable()->comment('確定金額');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['store_id', 'expense_category_mapping_id', 'budget_month']);

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('expense_category_mapping_id')->references('id')->on('expense_category_mapping')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE expense_budget COMMENT '支出予算テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_budget');
    }
};

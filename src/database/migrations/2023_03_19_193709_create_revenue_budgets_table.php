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
        Schema::create('revenue_budgets', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('収支予算ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('revenue_category_id')->comment('収支カテゴリID');
            $table->date('budget_month')->comment('収支予算年月');
            $table->integer('budget_amount')->comment('予算額');
            $table->date('confirmed_date')->nullable()->comment('確定日');
            $table->integer('confirmed_amount')->nullable()->comment('確定金額');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'store_id', 'revenue_category_id', 'budget_month']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');
            $table->foreign('revenue_category_id')->references('id')->on('revenue_categories')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE revenue_budgets IS '収支予算テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_budgets');
    }
};

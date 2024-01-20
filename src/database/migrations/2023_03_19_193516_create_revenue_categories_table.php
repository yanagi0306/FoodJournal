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
        Schema::create('revenue_categories', function(Blueprint $table) {
            // カラム定義
            $table->id()->comment('収支カテゴリID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedInteger('parent_revenue_category_id')->comment('親収支カテゴリID');
            $table->unsignedInteger('revenue_div')->comment('収支区分 1:収入 2:支出');
            $table->string('category_code', 3)->comment('収支カテゴリコード');
            $table->string('name', 30)->comment('収支カテゴリ名');
            $table->string('abbr', 6)->comment('収支カテゴリ略名');
            $table->unsignedInteger('revenue_type')->comment('収支タイプ 1:固定収支 2:月次支出 3:日次収支');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'parent_revenue_category_id', 'revenue_div', 'category_code']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('parent_revenue_category_id')->references('id')->on('parent_revenue_categories')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset   = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE revenue_categories IS '収支カテゴリテーブル'");
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_categories');
    }
};

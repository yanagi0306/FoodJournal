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
        Schema::create('income_category_mapping', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('収入カテゴリマッピングID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->unsignedBigInteger('parent_income_category_id')->comment('親収入カテゴリID');
            $table->unsignedBigInteger('income_category_id')->comment('収入カテゴリID');
            $table->unsignedBigInteger('income_type_id')->comment('収入カテゴリタイプID');
            $table->string('position', 3)->nullable()->comment('収入カテゴリ表示順番(非表示はnull)');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'parent_income_category_id', 'income_category_id']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('parent_income_category_id')->references('id')->on('parent_income_category')->onDelete('cascade');
            $table->foreign('income_category_id')->references('id')->on('income_category')->onDelete('cascade');
            $table->foreign('income_type_id')->references('id')->on('income_type')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE income_category_mapping IS '収入カテゴリマッピング'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('income_category_mapping');
    }
};

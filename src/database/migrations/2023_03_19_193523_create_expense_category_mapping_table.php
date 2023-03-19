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
        Schema::create('expense_category_mapping', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('支出カテゴリマッピングID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->unsignedBigInteger('parent_expense_category_id')->comment('親支出カテゴリID');
            $table->unsignedBigInteger('expense_category_id')->comment('支出カテゴリID');
            $table->unsignedBigInteger('expense_type_id')->comment('支出カテゴリタイプID');
            $table->string('position', 3)->nullable()->comment('支出カテゴリ表示順番(非表示はnull)');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'parent_expense_category_id', 'expense_category_id']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('parent_expense_category_id')->references('id')->on('parent_expense_category')->onDelete('cascade');
            $table->foreign('expense_category_id')->references('id')->on('expense_category')->onDelete('cascade');
            $table->foreign('expense_type_id')->references('id')->on('expense_type')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE expense_category_mapping = COMMENT '支出カテゴリマッピング'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('expense_category_mapping');
    }
};

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
    public function up()
    {
        Schema::create('parent_expense_category', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('親支出カテゴリID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('cat_cd', 2)->comment('親支出カテゴリコード');
            $table->string('cat_name', 10)->comment('親支出カテゴリ名');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE parent_expense_category IS '親支出カテゴリテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_expense_category');
    }
};

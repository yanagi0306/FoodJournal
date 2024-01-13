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
        Schema::create('parent_revenue_category', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('親収支カテゴリID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedInteger('revenue_div')->comment('収支区分 1:収入 2:支出');
            $table->string('prevenue_cat_code', 3)->comment('親収支カテゴリコード');
            $table->string('prevenue_cat_name', 8)->comment('親収支カテゴリ名');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'prevenue_cat_code']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE parent_revenue_category IS '親収支カテゴリテーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('parent_revenue_category');
    }
};

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
        Schema::create('income', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('収入ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('income_category_mapping_id')->comment('収入カテゴリマッピングID');
            $table->integer('amount')->comment('収入金額');
            $table->date('date')->comment('収入年月');
            $table->string('comment', 255)->nullable()->comment('コメント');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('income_category_mapping_id')->references('id')->on('income_category_mapping')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE income = COMMENT '収入情報テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('income');
    }
};

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
        Schema::create('parent_purchase_category', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('仕入業者ID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('cat_cd', 30)->comment('親仕入カテゴリコード');
            $table->string('cat_name', 30)->comment('親支出カテゴリ名');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'cat_cd']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE parent_purchase_category COMMENT '仕入親カテゴリテーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('parent_purchase_category');
    }
};

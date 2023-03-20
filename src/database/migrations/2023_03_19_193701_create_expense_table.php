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
        Schema::create('expense', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('支出ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('expense_category_mapping_id')->comment('支出カテゴリマッピングID');
            $table->integer('amount')->comment('支出金額');
            $table->date('date')->comment('支出年月');
            $table->string('comment', 255)->nullable()->comment('コメント');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('expense_category_mapping_id')->references('id')->on('expense_category_mapping')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE expense IS '支出情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('expense');
    }
};

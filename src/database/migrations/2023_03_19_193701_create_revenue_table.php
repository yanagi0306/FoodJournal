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
        Schema::create('revenue_info', function(Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('収支ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedInteger('store_id')->comment('店舗ID');
            $table->unsignedInteger('revenue_category_id')->comment('収支カテゴリID');
            $table->integer('amount')->comment('支出金額');
            $table->date('date')->comment('収支日');
            $table->string('comment', 255)->nullable()->comment('コメント');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('revenue_category_id')->references('id')->on('revenue_category')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset   = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE revenue_info IS '収支情報テーブル'");
    }

    /**
     * Reverse the migrations.
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_info');
    }
};

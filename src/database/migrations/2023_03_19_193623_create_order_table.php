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
        Schema::create('order', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('注文ID');
            $table->unsignedBigInteger('store_id')->comment('店舗ID');
            $table->unsignedBigInteger('income_category_mapping_id')->comment('収入カテゴリマッピングID');
            $table->unsignedBigInteger('customer_type_id')->comment('客層ID');
            $table->string('voucher', 15)->comment('伝票番号');
            $table->date('order_date')->comment('注文日時');
            $table->timestamp('payment_date')->nullable()->comment('決済日時');
            $table->integer('men_count')->nullable()->comment('男性客数');
            $table->integer('women_count')->nullable()->comment('女性客数');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['store_id', 'voucher']);

            // 外部キー制約の設定
            $table->foreign('store_id')->references('id')->on('store')->onDelete('cascade');
            $table->foreign('income_category_mapping_id')->references('id')->on('income_category_mapping')->onDelete('cascade');
            $table->foreign('customer_type_id')->references('id')->on('customer_type')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE order = COMMENT '注文情報テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order');
    }
};

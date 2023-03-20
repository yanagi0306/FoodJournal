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
        Schema::create('order_payment', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('注文支払ID');
            $table->unsignedBigInteger('order_id')->comment('注文ID');
            $table->unsignedBigInteger('payment_method_id')->comment('支払い方法');
            $table->integer('amount')->comment('支払額');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['order_id', 'payment_method_id']);

            // 外部キー制約の設定
            $table->foreign('order_id')->references('id')->on('order')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_method')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE order_payment IS '注文支払テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payment');
    }
};

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
        Schema::create('order_payments', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('注文支払ID');
            $table->unsignedInteger('order_id')->comment('注文ID');
            $table->unsignedInteger('payment_method_id')->comment('注文支払方法');
            $table->integer('order_amount')->comment('注文支払額');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['order_id', 'payment_method_id']);

            // 外部キー制約の設定
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
            $table->foreign('payment_method_id')->references('id')->on('payment_methods')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE order_payments IS '注文支払テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};

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
        Schema::create('orders', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('注文ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->unsignedInteger('store_id')->comment('店舗ID');
            $table->string('order_number', 20)->comment('注文番号');
            $table->date('order_date')->comment('注文日');
            $table->timestamp('payment_date')->nullable()->comment('決済日時');
            $table->string('customer_type_name', 20)->nullable()->comment('客層名');
            $table->integer('male_customer_count')->nullable()->comment('男性客数');
            $table->integer('female_customer_count')->nullable()->comment('女性客数');
            $table->integer('other_customer_count')->nullable()->comment('その他客数');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'store_id', 'order_number']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE orders IS '注文情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

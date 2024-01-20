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
        Schema::create('stores', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('店舗ID');
            $table->unsignedInteger('company_id')->comment('会社ID');
            $table->string('order_store_code', 20)->nullable()->comment('注文システム店舗コード');
            $table->string('purchase_store_code', 20)->nullable()->comment('仕入システム店舗コード');
            $table->string('name', 30)->comment('店舗名');
            $table->string('abbr', 6)->comment('店舗略名');
            $table->string('mail', 30)->nullable()->comment('メールアドレス');
            $table->integer('is_closed')->nullable()->comment('閉店フラグ');
            $table->timestamps();

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('companies')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE stores IS '店舗情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};

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
        Schema::create('store', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('店舗ID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('store_cd', 4)->comment('店舗コード');
            $table->string('purchase_store_cd', 4)->comment('仕入店舗コード（アスピット）');
            $table->string('store_name', 30)->comment('店舗名');
            $table->string('mail', 30)->nullable()->comment('メールアドレス');
            $table->integer('is_closed')->nullable()->comment('閉店フラグ');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique('store_cd');
            $table->unique(['company_id', 'store_cd']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE store IS '店舗情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('store');
    }
};

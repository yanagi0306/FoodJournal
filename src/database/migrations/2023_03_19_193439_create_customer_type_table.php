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
        Schema::create('customer_type', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('客層ID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('type_cd',3)->comment('客層コード');
            $table->string('type_name', 15)->comment('客層名');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'type_cd']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE `customer_type` COMMENT '客層テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_type');
    }
};

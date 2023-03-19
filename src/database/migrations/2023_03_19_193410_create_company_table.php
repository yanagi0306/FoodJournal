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
        Schema::create('company', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('会社ID');
            $table->string('company_name', 30)->comment('会社名');
            $table->string('company_cd', 4)->comment('会社コード');
            $table->string('purchase_store_cd', 4)->comment('仕入会社コード');
            $table->string('mail', 255)->nullable()->comment('メールアドレス');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique('company_cd');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

            // テーブルコメントの設定
            DB::statement("ALTER TABLE `company` COMMENT '会社情報テーブル'");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('company');
    }
};

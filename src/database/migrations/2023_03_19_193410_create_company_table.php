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
            $table->unsignedInteger('order_company_cd')->comment('注文システム会社コード');
            $table->unsignedInteger('purchase_store_cd')->comment('仕入システムコード');
            $table->string('mail', 255)->nullable()->comment('メールアドレス');
            $table->timestamps();

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE company IS '会社情報テーブル'");
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

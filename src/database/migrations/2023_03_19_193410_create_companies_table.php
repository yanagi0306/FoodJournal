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
        Schema::create('companies', function (Blueprint $table) {
            // カラム定義
            $table->id()->comment('会社ID');
            $table->string('name', 30)->unique()->comment('会社名');
            $table->string('abbr', 6)->comment('会社名略名');
            $table->unsignedInteger('order_system_id')->nullable()->comment('注文システムID');
            $table->unsignedInteger('purchase_system_id')->nullable()->comment('仕入システムID');
            $table->string('order_company_code', 20)->nullable()->comment('注文システム会社コード');
            $table->string('purchase_company_code', 20)->nullable()->comment('仕入システム会社コード');
            $table->string('mail', 255)->nullable()->comment('メールアドレス');
            $table->timestamps();

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE companies IS '会社情報テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};

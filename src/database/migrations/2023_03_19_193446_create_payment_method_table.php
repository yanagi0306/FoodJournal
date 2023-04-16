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
        Schema::create('payment_method', function (Blueprint $table) {
            // カラム定義
            $table->id()->autoIncrement()->comment('支払方法ID');
            $table->unsignedBigInteger('company_id')->comment('会社ID');
            $table->string('payment_cd', 3)->comment('支払方法コード（表示の優先度）');
            $table->string('payment_name', 15)->comment('支払方法名');
            $table->decimal('commission_rate', 4, 4)->comment('支払手数料');
            $table->integer('is_hidden')->nullable()->default(null)->comment('非表示フラグ（null: 表示, 1: 非表示）');
            $table->timestamps();

            // ユニークキーの設定
            $table->unique(['company_id', 'payment_cd']);

            // 外部キー制約の設定
            $table->foreign('company_id')->references('id')->on('company')->onDelete('cascade');

            // 文字コードと照合順序の設定
            $table->charset = 'utf8';
            $table->collation = 'utf8_general_ci';

        });
        // テーブルコメントの設定
        DB::statement("COMMENT ON TABLE payment_method IS '支払方法テーブル'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_method');
    }
};

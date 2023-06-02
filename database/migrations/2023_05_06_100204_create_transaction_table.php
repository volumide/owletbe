<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->string("user_id");
            $table->string("type");
            $table->string("requestId")->nullable();
            $table->string("transaction_id");
            $table->string("tx_ref");
            $table->string("amount");
            $table->string("status")->default("initiated");
            $table->string("status_flutter")->default("initiated");
            $table->string("phone");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction');
    }
};
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
        Schema::create('cart_settings', function (Blueprint $table) {
            $table->id();
            // $table->foreignId('customer_id')->default(1)->constrained('contacts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('deposit_to_account_id')->constrained('accounts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->date('date')->nullable();
            // $table->text('description');
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
        Schema::dropIfExists('cart_settings');
    }
};

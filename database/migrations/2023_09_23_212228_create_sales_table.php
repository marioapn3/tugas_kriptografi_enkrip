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
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->string('no_transaction')->unique();
            $table->date('date');
            $table->text('description')->nullable();
            $table->foreignId('customer_id')->constrained('contacts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('deposit_to_account_id')->constrained('accounts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->foreignId('journal_id')->constrained('journals')->cascadeOnUpdate()->cascadeOnDelete();
            $table->softDeletes();
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
        Schema::dropIfExists('sales');
    }
};

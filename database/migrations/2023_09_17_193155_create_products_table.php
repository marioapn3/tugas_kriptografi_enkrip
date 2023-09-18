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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('code', 20)->unique();
            $table->string('name', 100);
            $table->string('description', 255)->nullable();
            $table->decimal('purchase_price', 48, 2);
            $table->foreignId('purchase_account')->constrained('accounts')->cascadeOnUpdate()->cascadeOnDelete();
            $table->decimal('sale_price', 48, 2);
            $table->foreignId('sale_account')->constrained('accounts')->cascadeOnUpdate()->cascadeOnDelete();

            // inventory account 
            $table->foreignId('inventory_account')->constrained('accounts')->cascadeOnUpdate()->cascadeOnDelete();

            $table->integer('stock')->default(0);
            $table->string('unit', 20)->nullable();
            $table->string('image', 255)->nullable();

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
        Schema::dropIfExists('products');
    }
};

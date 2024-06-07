<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('reports', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id');
            $table->string('customer_name')->nullable();
            $table->string('product_code')->nullable();
            $table->string('amount')->nullable();
            $table->string('quantity')->nullable();
            $table->timestamps();
        });
    }

};

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
        Schema::create('inputs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_purchase_order')->constrained('purchase_order')->onDelete('cascade')->onUpdate('cascade');
            $table->string('InputName',80);
            $table->string('UnitMeasurement',10);
            $table->integer('InitialQuantity');
            $table->integer('CurrentStock');
            $table->double('UnityPrice',10,3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inputs');
    }
};

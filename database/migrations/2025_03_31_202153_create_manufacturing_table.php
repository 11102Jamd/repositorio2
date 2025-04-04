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
        Schema::create('manufacturing', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ID_product')->constrained('product')->onDelete('cascade')->onUpdate('cascade');
            $table->integer('ManufacturingTime');
            $table->double('Labour',10,3);
            $table->double('ManufactureProductG',10,3);
            $table->double('TotalCostProduction',10,3);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manufacturing');
    }
};

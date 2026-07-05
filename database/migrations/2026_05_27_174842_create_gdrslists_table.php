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
        Schema::create('gdrslists', function (Blueprint $table) {
            $table->id();
            $table->string('description')->nullable();
            $table->string('grds_rds_no')->nullable();
            $table->integer('retention_period')->nullable();
            $table->string('document_status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('gdrslists');
    }
};

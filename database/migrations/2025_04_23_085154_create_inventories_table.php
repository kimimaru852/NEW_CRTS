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
        Schema::create('inventories', function (Blueprint $table) {
            $table->id();
            $table->string('office_origin');
            $table->string('prepared_by');
            $table->string('list_no')->nullable();
            $table->string('loc_code')->nullable();
            $table->string('received_by')->nullable();
            $table->string('received_date')->nullable();
            $table->string('manager_approval')->nullable();
            $table->string('verified_by')->nullable();
            $table->string('verified_date')->nullable();
            $table->string('disposal_status')->nullable();
            $table->dateTime('disposed_date')->nullable();
            $table->integer('rack_no')->nullable();
            $table->timestamps();

            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('office_id')->nullable()->constrained('offices')->onDelete('set null');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories');
    }
};

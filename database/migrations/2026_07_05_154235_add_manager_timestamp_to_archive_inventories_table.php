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
        Schema::table('archive_inventories', function (Blueprint $table) {
            //
            $table->timestamp('manager_approval_date')->nullable()->after('manager_approval');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('archive_inventories', function (Blueprint $table) {
            //
            $table->dropColumn('manager_approval_date');
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->date('data_inizio')->nullable()->change();
            $table->date('data_fine')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('requests', function (Blueprint $table) {
            $table->date('data_inizio')->nullable(false)->change();
            $table->date('data_fine')->nullable(false)->change();
        });
    }
};

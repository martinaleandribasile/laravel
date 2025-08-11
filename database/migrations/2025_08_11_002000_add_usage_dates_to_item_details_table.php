<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('item_details', function (Blueprint $table) {
            $table->date('data_inizio_uso')->nullable()->after('stato');
            $table->date('data_fine_uso')->nullable()->after('data_inizio_uso');
        });
    }
    public function down(): void
    {
        Schema::table('item_details', function (Blueprint $table) {
            $table->dropColumn(['data_inizio_uso', 'data_fine_uso']);
        });
    }
};

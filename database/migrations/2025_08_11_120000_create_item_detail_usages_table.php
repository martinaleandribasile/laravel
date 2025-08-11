<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_detail_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_detail_id')->constrained('item_details')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('request_id')->nullable()->constrained('requests')->onDelete('set null');
            $table->date('data_inizio');
            $table->date('data_fine')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('item_detail_usages');
    }
};

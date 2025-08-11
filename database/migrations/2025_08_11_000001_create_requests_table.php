<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_detail_id')->constrained('item_details')->onDelete('cascade');
            $table->date('data_inizio');
            $table->date('data_fine');
            $table->text('note')->nullable();
            $table->string('stato')->default('in_attesa');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('requests');
    }
};

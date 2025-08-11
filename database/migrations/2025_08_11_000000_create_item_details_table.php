<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->string('seriale')->unique();
            $table->string('colore')->nullable();
            $table->string('ram')->nullable();
            $table->string('altro')->nullable();
            $table->enum('stato', ['disponibile', 'in_uso', 'in_attesa'])->default('disponibile');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('item_details');
    }
};

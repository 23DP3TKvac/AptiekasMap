<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        if (!Schema::hasTable('medicine_set_items')) {
            Schema::create('medicine_set_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('set_id')->constrained('medicine_sets')->onDelete('cascade');
                $table->foreignId('medicine_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                $table->unique(['set_id', 'medicine_id']);
            });
        }
    }
    public function down(): void { Schema::dropIfExists('medicine_set_items'); }
};

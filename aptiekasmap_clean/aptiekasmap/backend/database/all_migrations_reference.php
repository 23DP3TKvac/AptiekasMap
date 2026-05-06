<?php
// =============================================================
// database/migrations — visas migrācijas
// Palaist: php artisan migrate --seed
// =============================================================

// ── 2025_01_01_000001_create_users_table.php ─────────────────
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email', 50)->unique();
            $table->string('password');
            $table->enum('role', ['admin', 'user', 'pharmacy_rep'])->default('user');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('users'); }
};

// ── 2025_01_01_000002_create_medicines_table.php ─────────────
return new class extends Migration {
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('active_substance', 100);
            $table->string('form', 50);
            $table->string('dose', 50);
            $table->string('manufacturer', 100);
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('medicines'); }
};

// ── 2025_01_01_000003_create_pharmacies_table.php ────────────
return new class extends Migration {
    public function up(): void
    {
        Schema::create('pharmacies', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('address', 255);
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 10, 7);
            $table->string('phone', 20)->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pharmacies'); }
};

// ── 2025_01_01_000004_create_availability_table.php ──────────
return new class extends Migration {
    public function up(): void
    {
        Schema::create('availability', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pharmacy_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->integer('quantity')->default(0);
            $table->decimal('price', 8, 2);
            $table->enum('status', ['available', 'unavailable'])->default('available');
            $table->timestamp('last_updated')->useCurrent()->useCurrentOnUpdate();
            $table->unique(['pharmacy_id', 'medicine_id']);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('availability'); }
};

// ── 2025_01_01_000005_create_search_history_table.php ────────
return new class extends Migration {
    public function up(): void
    {
        Schema::create('search_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('medicine_name', 100);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('search_history'); }
};

// ── 2025_01_01_000006_create_notifications_table.php ─────────
return new class extends Migration {
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('medicine_id')->constrained()->cascadeOnDelete();
            $table->string('message', 255);
            $table->enum('is_read', ['read', 'not_read'])->default('not_read');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('notifications'); }
};

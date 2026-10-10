
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jamaahs', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('domicile');
            $table->date('birth_date')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('status')->default('aktif');
            $table->text('notes')->nullable();

            // Akun login bersifat opsional untuk data jamaah.
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['domicile', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jamaahs');
    }
};

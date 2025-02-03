<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('image')->nullable();
            $table->date('dob')->nullable(); // Tanggal lahir
            $table->text('address')->nullable(); // Alamat
            $table->enum('status', ['active', 'inactive'])->default('active'); // Status customer
            $table->boolean('verified')->default(false); // Apakah customer sudah verifikasi?
            $table->text('notes')->nullable(); // Catatan tambahan (misalnya rich text)
            $table->timestamps();
            $table->softDeletes(); // Supaya bisa dihapus sementara
        });
    }

    /**
     * Reverse migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

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
        Schema::create('obats', function (Blueprint $table) {
            $table->id();
            // Tambahkan nullable atau default value
            $table->decimal('harga', 15, 2)->nullable(); // Bisa bernilai null
            // ATAU
            $table->decimal('harga', 15, 2)->default(0); // Default 0
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }
};

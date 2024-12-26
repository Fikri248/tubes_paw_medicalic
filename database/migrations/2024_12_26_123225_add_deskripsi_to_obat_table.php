<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->text('deskripsi')->nullable(); // Menambah kolom deskripsi dengan tipe text
        });
    }

    public function down()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('deskripsi');
        });
    }
};

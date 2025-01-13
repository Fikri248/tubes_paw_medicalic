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
            $table->decimal('harga', 10, 2)->after('stok');  // Menambahkan kolom harga setelah kolom stok
        });
    }

    public function down()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('harga');  // Menghapus kolom harga jika migrasi di-rollback
        });
    }
};

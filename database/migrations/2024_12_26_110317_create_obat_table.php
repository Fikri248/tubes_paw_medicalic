<?php

// database/migrations/xxxx_xx_xx_xxxxxx_create_obat_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObatTable extends Migration
{
    public function up()
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->foreignId('category_id')->constrained('categories'); // Kolom kategori yang menjadi relasi ke tabel categories
            $table->string('jenis');
            $table->integer('stok');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obat');
    }
}


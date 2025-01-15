<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->decimal('harga', 10, 2)->default(0);
        });
    }

    public function down()
    {
        Schema::table('obat', function (Blueprint $table) {
            $table->dropColumn('harga');
        });
    }

};

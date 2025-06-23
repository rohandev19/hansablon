<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('produk', function (Blueprint $table) {
            //
            $table->integer('stok')->default(0)->after('deskripsi'); // Menambahkan kolom stok dengan default 0
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void 
     */
    public function down()
    {
        Schema::table('produk', function (Blueprint $table) {
            //
            $table->dropColumn('stok'); // Menghapus kolom stok jika migrasi dibatalkan
        });
    }
};

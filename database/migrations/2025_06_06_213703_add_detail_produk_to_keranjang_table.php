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
        Schema::table('keranjang', function (Blueprint $table) {
            $table->string('foto_produk1')->nullable();
            $table->string('nama_produk')->nullable();
            $table->string('harga_produk')->nullable();    //
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('keranjang', function (Blueprint $table) {
            $table->dropColumn(['foto_produk1', 'nama_produk', 'harga_produk']); //
        });
    }
};

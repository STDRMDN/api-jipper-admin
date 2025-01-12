<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->string('name')->unique(); // Kolom nama wajib unik
            $table->string('slug')->unique(); // Kolom slug wajib unik
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });
    }

    /**
     * Hapus tabel jika rollback.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('categories');
    }
}

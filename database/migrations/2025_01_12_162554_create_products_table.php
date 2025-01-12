<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Jalankan migrasi.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Primary key
            $table->foreignId('cat_id')->constrained('categories')->onDelete('cascade'); // Foreign key ke tabel categories
            $table->string('name'); // Nama produk
            $table->string('slug')->unique(); // Slug produk wajib unik
            $table->string('front'); // Gambar depan produk (required)
            $table->string('back'); // Gambar belakang produk (required)
            $table->double('price'); // Harga produk (required)
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
        Schema::dropIfExists('products');
    }
}

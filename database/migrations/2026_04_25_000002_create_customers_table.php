<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('nama_lengkap');
            $table->string('email')->nullable();
            $table->string('phone');
            $table->string('nik', 16);
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat');
            $table->string('pekerjaan');
            $table->bigInteger('penghasilan_bulanan');
            $table->enum('status_pernikahan', ['Belum Menikah', 'Menikah', 'Cerai'])->default('Belum Menikah');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};

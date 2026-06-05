<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mortgage_documents', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mortgage_request_id')
                ->constrained('mortgage_requests')
                ->cascadeOnDelete();
            $table->string('name');          // Nama dokumen (input bebas)
            $table->string('file_path');     // Path file di storage
            $table->timestamps();

            $table->index('mortgage_request_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mortgage_documents');
    }
};

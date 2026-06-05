<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('investor_cities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('investor_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->foreignId('city_id')
                ->constrained('cities')
                ->cascadeOnDelete();
            $table->unique(['investor_id', 'city_id']);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('investor_cities');
    }
};

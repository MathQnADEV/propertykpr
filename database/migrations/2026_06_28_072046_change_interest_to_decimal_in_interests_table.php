<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('interests', function (Blueprint $table) {
            $table->decimal('interest', 5, 2)->change();
        });
    }

    public function down(): void
    {
        Schema::table('interests', function (Blueprint $table) {
            $table->integer('interest')->change();
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->string('thumbnail')->nullable()->change();
            $table->text('about')->nullable()->change();
            $table->string('certificate')->nullable()->change();
            $table->integer('price')->nullable()->change();
            $table->integer('bedroom')->nullable()->change();
            $table->integer('bathroom')->nullable()->change();
            $table->integer('electric')->nullable()->change();
            $table->integer('land_area')->nullable()->change();
            $table->integer('building_area')->nullable()->change();
        });
    }

    public function down(): void
    {
        //
    }
};

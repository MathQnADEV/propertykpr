<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->integer('bedroom')->nullable()->change();
            $table->integer('bathroom')->nullable()->change();
            $table->integer('electric')->nullable()->change();
            $table->integer('land_area')->nullable()->change();
            $table->integer('building_area')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->integer('bedroom')->nullable(false)->default(0)->change();
            $table->integer('bathroom')->nullable(false)->default(0)->change();
            $table->integer('electric')->nullable(false)->default(0)->change();
            $table->integer('land_area')->nullable(false)->default(0)->change();
            $table->integer('building_area')->nullable(false)->default(0)->change();
        });
    }
};

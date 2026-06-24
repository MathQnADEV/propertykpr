<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            if (!Schema::hasColumn('houses', 'facilities')) {
                $table->text('facilities')->nullable()->after('building_area');
            }
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            if (Schema::hasColumn('houses', 'facilities')) {
                $table->dropColumn('facilities');
            }
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('instagram')->nullable()->after('phone');
            $table->string('facebook')->nullable()->after('instagram');
            $table->string('whatsapp')->nullable()->after('facebook');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['instagram', 'facebook', 'whatsapp']);
        });
    }
};

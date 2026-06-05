<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Bagian investor dari komisi agent — diset oleh master
            // share_type: 'percentage' | 'nominal'
            // share_value: nilai % atau Rp nominal
            $table->string('investor_share_type')->nullable()->after('whatsapp');
            $table->decimal('investor_share_value', 15, 2)->nullable()->after('investor_share_type');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['investor_share_type', 'investor_share_value']);
        });
    }
};

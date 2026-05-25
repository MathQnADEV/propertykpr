<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mortgage_requests', function (Blueprint $table) {
            $table->string('payment_type')->default('kpr')->after('id');
            $table->foreignId('interest_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('mortgage_requests', function (Blueprint $table) {
            $table->dropColumn('payment_type');
            $table->foreignId('interest_id')->nullable(false)->change();
        });
    }
};

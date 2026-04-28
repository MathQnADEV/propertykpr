<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mortgage_requests', function (Blueprint $table) {
            // make user_id nullable so old data isn't broken
            $table->foreignId('user_id')->nullable()->change();

            $table->foreignId('customer_id')
                ->nullable()
                ->after('user_id')
                ->constrained('customers')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('mortgage_requests', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropColumn('customer_id');
            $table->foreignId('user_id')->nullable(false)->change();
        });
    }
};

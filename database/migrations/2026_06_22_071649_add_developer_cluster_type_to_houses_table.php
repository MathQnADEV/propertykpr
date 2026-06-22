<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->foreignId('developer_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('cluster_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('type_id')->nullable()->constrained()->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropForeign(['developer_id']);
            $table->dropForeign(['cluster_id']);
            $table->dropForeign(['type_id']);
            $table->dropColumn(['developer_id', 'cluster_id', 'type_id']);
        });
    }
};

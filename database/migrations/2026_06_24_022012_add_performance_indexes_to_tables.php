<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            if (! $this->hasIndex('houses', 'houses_agent_id_index')) {
                $table->index('agent_id');
            }
        });

        Schema::table('mortgage_requests', function (Blueprint $table) {
            if (! $this->hasIndex('mortgage_requests', 'mortgage_requests_status_index')) {
                $table->index('status');
            }
            if (! $this->hasIndex('mortgage_requests', 'mortgage_requests_house_id_status_index')) {
                $table->index(['house_id', 'status']);
            }
            if (! $this->hasIndex('mortgage_requests', 'mortgage_requests_user_id_index')) {
                $table->index('user_id');
            }
        });

        Schema::table('commissions', function (Blueprint $table) {
            if (! $this->hasIndex('commissions', 'commissions_agent_id_index')) {
                $table->index('agent_id');
            }
        });

        Schema::table('commission_requests', function (Blueprint $table) {
            if (! $this->hasIndex('commission_requests', 'commission_requests_agent_id_index')) {
                $table->index('agent_id');
            }
            if (! $this->hasIndex('commission_requests', 'commission_requests_agent_id_status_index')) {
                $table->index(['agent_id', 'status']);
            }
        });

        Schema::table('system_notifications', function (Blueprint $table) {
            if (! $this->hasIndex('system_notifications', 'system_notifications_user_id_is_read_index')) {
                $table->index(['user_id', 'is_read']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('houses', fn (Blueprint $t) => $t->dropIndexIfExists('houses_agent_id_index'));
        Schema::table('mortgage_requests', function (Blueprint $t) {
            $t->dropIndexIfExists('mortgage_requests_status_index');
            $t->dropIndexIfExists('mortgage_requests_house_id_status_index');
            $t->dropIndexIfExists('mortgage_requests_user_id_index');
        });
        Schema::table('commissions', fn (Blueprint $t) => $t->dropIndexIfExists('commissions_agent_id_index'));
        Schema::table('commission_requests', function (Blueprint $t) {
            $t->dropIndexIfExists('commission_requests_agent_id_index');
            $t->dropIndexIfExists('commission_requests_agent_id_status_index');
        });
        Schema::table('system_notifications', fn (Blueprint $t) => $t->dropIndexIfExists('system_notifications_user_id_is_read_index'));
    }

    private function hasIndex(string $table, string $indexName): bool
    {
        return collect(DB::select("SHOW INDEX FROM `{$table}`"))
            ->pluck('Key_name')
            ->contains($indexName);
    }
};

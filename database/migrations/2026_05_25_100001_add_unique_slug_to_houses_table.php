<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add a UNIQUE index on houses.slug.
     *
     * Before adding the constraint we deduplicate any existing duplicate slugs
     * by appending the row ID to all but the first occurrence.
     */
    public function up(): void
    {
        // ── Step 1: resolve any existing duplicate slugs ──────────────────────
        $duplicates = DB::table('houses')
            ->select('slug')
            ->groupBy('slug')
            ->havingRaw('COUNT(*) > 1')
            ->pluck('slug');

        foreach ($duplicates as $slug) {
            // Keep the first row (lowest id) untouched; rename the rest
            $rows   = DB::table('houses')->where('slug', $slug)->orderBy('id')->get();
            $suffix = 1;

            foreach ($rows->skip(1) as $row) {
                DB::table('houses')
                    ->where('id', $row->id)
                    ->update(['slug' => $slug . '-' . $suffix++]);
            }
        }

        // ── Step 2: add the unique index ──────────────────────────────────────
        Schema::table('houses', function (Blueprint $table) {
            $table->unique('slug');
        });
    }

    public function down(): void
    {
        Schema::table('houses', function (Blueprint $table) {
            $table->dropUnique(['slug']);
        });
    }
};

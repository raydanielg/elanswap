<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        // 1) Add phone column if missing
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'phone')) {
                $table->string('phone', 20)->nullable()->after('method');
            }
        });

        // 2) Add indexes if they don't already exist (SQLite-friendly)
        $existingIndexes = collect(DB::select("PRAGMA index_list('payments')"))
            ->pluck('name')
            ->map(fn($n) => strtolower((string) $n))
            ->all();

        $ensureIndex = function (string $column, string $indexName) use ($existingIndexes) {
            if (!in_array(strtolower($indexName), $existingIndexes, true) && Schema::hasColumn('payments', $column)) {
                Schema::table('payments', function (Blueprint $table) use ($column, $indexName) {
                    $table->index($column, $indexName);
                });
            }
        };

        $ensureIndex('orderid', 'payments_orderid_index');
        $ensureIndex('provider_reference', 'payments_provider_reference_index');
        $ensureIndex('transid', 'payments_transid_index');
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'phone')) {
                $table->dropColumn('phone');
            }
            // Drop indexes if they exist
            try { $table->dropIndex('payments_orderid_index'); } catch (\Throwable $e) {}
            try { $table->dropIndex('payments_provider_reference_index'); } catch (\Throwable $e) {}
            try { $table->dropIndex('payments_transid_index'); } catch (\Throwable $e) {}
        });
    }
};

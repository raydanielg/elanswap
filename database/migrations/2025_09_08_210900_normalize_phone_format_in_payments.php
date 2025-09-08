<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration {
    public function up(): void
    {
        // Ensure phone column exists (defensive)
        if (!Schema::hasColumn('payments', 'phone')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->string('phone', 20)->nullable()->after('method');
            });
        }

        // Add index on phone if not present
        $driver = DB::getDriverName();
        $existingIndexes = [];
        try {
            if (in_array($driver, ['mysql', 'mysqli'])) {
                $existingIndexes = collect(DB::select('SHOW INDEX FROM payments'))
                    ->pluck('Key_name')
                    ->map(fn($n) => strtolower((string) $n))
                    ->unique()
                    ->values()
                    ->all();
            } elseif ($driver === 'sqlite') {
                $existingIndexes = collect(DB::select("PRAGMA index_list('payments')"))
                    ->pluck('name')
                    ->map(fn($n) => strtolower((string) $n))
                    ->all();
            } elseif ($driver === 'pgsql') {
                $existingIndexes = collect(DB::select("SELECT indexname FROM pg_indexes WHERE tablename = 'payments'"))
                    ->pluck('indexname')
                    ->map(fn($n) => strtolower((string) $n))
                    ->all();
            }
        } catch (\Throwable $e) {
            $existingIndexes = [];
        }

        if (!in_array('payments_phone_index', $existingIndexes, true)) {
            Schema::table('payments', function (Blueprint $table) {
                try { $table->index('phone', 'payments_phone_index'); } catch (\Throwable $e) {}
            });
        }

        // Normalize all existing phones to 255XXXXXXXXX (no plus)
        $this->normalizeExistingPhones();
    }

    public function down(): void
    {
        // Optionally drop the index (leave normalized data as-is)
        Schema::table('payments', function (Blueprint $table) {
            try { $table->dropIndex('payments_phone_index'); } catch (\Throwable $e) {}
        });
    }

    private function normalizeExistingPhones(): void
    {
        // Process in chunks to avoid memory spikes
        $lastId = 0;
        $batch = 1000;
        do {
            $rows = DB::table('payments')
                ->select('id', 'phone')
                ->where('id', '>', $lastId)
                ->orderBy('id')
                ->limit($batch)
                ->get();

            if ($rows->isEmpty()) {
                break;
            }

            foreach ($rows as $row) {
                $lastId = $row->id;
                $current = (string) ($row->phone ?? '');
                $normalized = $this->toE164Tz($current);
                if ($normalized !== $current) {
                    DB::table('payments')->where('id', $row->id)->update(['phone' => $normalized]);
                }
            }
        } while (true);
    }

    private function toE164Tz(string $input): string
    {
        if ($input === '') return $input;
        // Keep digits and plus only
        $raw = preg_replace('/[^0-9+]/', '', $input) ?? '';
        if ($raw === '') return $input;
        if (str_starts_with($raw, '+')) { $raw = substr($raw, 1); }

        // If already 2557XXXXXXXX (12 digits starting with 2557/2556)
        if (str_starts_with($raw, '255') && strlen($raw) >= 12) {
            return substr($raw, 0, 12);
        }

        // If local like 07XXXXXXXX or 06XXXXXXXX
        if (preg_match('/^0[67][0-9]{8}$/', $raw)) {
            return '255' . substr($raw, 1);
        }

        // If short 7XXXXXXXX or 6XXXXXXXX
        if (preg_match('/^[67][0-9]{8}$/', $raw)) {
            return '255' . $raw;
        }

        // Fallback: try to coerce to 255 + last 9 digits
        $digits = preg_replace('/[^0-9]/', '', $raw) ?? '';
        if (strlen($digits) >= 9) {
            return '255' . substr($digits, -9);
        }

        // If unknown, return digits so we don't lose data
        return $digits;
    }
};

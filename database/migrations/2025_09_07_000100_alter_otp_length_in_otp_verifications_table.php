<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // We now store bcrypt hash in a separate 'otp_hash' column; no need to alter 'otp' on SQLite.
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mysqli'])) {
            try {
                DB::statement("ALTER TABLE otp_verifications MODIFY otp VARCHAR(255)");
            } catch (\Throwable $e) {
                // Ignore if alteration fails; 'otp' remains VARCHAR(6) which is fine with new otp_hash usage.
            }
        }
        // For other drivers (sqlite, pgsql), do nothing.
    }

    public function down(): void
    {
        $driver = DB::getDriverName();
        if (in_array($driver, ['mysql', 'mysqli'])) {
            try {
                DB::statement("ALTER TABLE otp_verifications MODIFY otp VARCHAR(6)");
            } catch (\Throwable $e) {
                // no-op
            }
        }
    }
};

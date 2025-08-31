<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('payments')) {
            return; // table missing; nothing to alter
        }
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'provider_reference')) {
                $table->string('provider_reference')->nullable()->after('method');
                $table->index(['provider_reference']);
            }
        });
    }

    public function down(): void
    {
        if (!Schema::hasTable('payments')) {
            return;
        }
        Schema::table('payments', function (Blueprint $table) {
            if (Schema::hasColumn('payments', 'provider_reference')) {
                $table->dropIndex(['provider_reference']);
                $table->dropColumn('provider_reference');
            }
        });
    }
};

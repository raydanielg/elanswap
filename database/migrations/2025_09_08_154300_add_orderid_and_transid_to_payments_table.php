<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            // orderid is required (NOT NULL). Provide a default to avoid failing on existing rows.
            $table->string('orderid')->default('')->after('provider_reference');
            $table->string('transid')->nullable()->after('orderid');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['orderid', 'transid']);
        });
    }
};

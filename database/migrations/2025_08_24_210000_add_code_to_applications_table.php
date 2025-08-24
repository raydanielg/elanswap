<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->string('code')->nullable()->unique()->after('id');
        });

        // Backfill codes for existing rows
        $rows = DB::table('applications')->select('id', 'code')->whereNull('code')->orderBy('id')->get();
        foreach ($rows as $row) {
            $code = 'ELS' . str_pad((string) $row->id, 4, '0', STR_PAD_LEFT);
            DB::table('applications')->where('id', $row->id)->update(['code' => $code]);
        }
    }

    public function down(): void
    {
        Schema::table('applications', function (Blueprint $table) {
            $table->dropUnique(['code']);
            $table->dropColumn('code');
        });
    }
};

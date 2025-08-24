<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class InstallController extends Controller
{
    public function index(): View
    {
        return view('installer.index', [
            'env' => [
                'DB_HOST' => env('DB_HOST', '127.0.0.1'),
                'DB_PORT' => env('DB_PORT', '3306'),
                'DB_DATABASE' => env('DB_DATABASE'),
                'DB_USERNAME' => env('DB_USERNAME'),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'db_connection' => ['required', 'in:mysql'],
            'db_host' => ['required','string'],
            'db_port' => ['required','numeric'],
            'db_database' => ['required','string'],
            'db_username' => ['required','string'],
            'db_password' => ['nullable','string'],

            'admin_name' => ['required','string','max:255'],
            'admin_email' => ['required','email','max:255'],
            'admin_phone' => ['required','string','min:9','max:20'],
            'admin_password' => ['required','string','min:6'],
        ]);

        // Write DB config to .env
        $this->setEnv([
            'DB_CONNECTION' => $data['db_connection'],
            'DB_HOST' => $data['db_host'],
            'DB_PORT' => $data['db_port'],
            'DB_DATABASE' => $data['db_database'],
            'DB_USERNAME' => $data['db_username'],
            'DB_PASSWORD' => $data['db_password'] ?? '',
        ]);

        // Reboot config cache
        try { Artisan::call('config:clear'); } catch (\Throwable $e) {}

        // Test DB connection
        try {
            DB::purge();
            DB::reconnect();
            DB::connection()->getPdo();
        } catch (\Throwable $e) {
            return back()->withErrors(['db' => 'Failed to connect: '.$e->getMessage()])->withInput();
        }

        // Run migrations
        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Throwable $e) {
            return back()->withErrors(['migrate' => 'Migration failed: '.$e->getMessage()])->withInput();
        }

        // Normalize phone to 255XXXXXXXXX
        $raw = preg_replace('/\D+/', '', $data['admin_phone']);
        if (str_starts_with($raw, '0') && strlen($raw) === 10) {
            $phone = '255'.substr($raw, 1);
        } elseif (strlen($raw) === 9) {
            $phone = '255'.$raw;
        } elseif (str_starts_with($raw, '255')) {
            $phone = substr($raw, 0, 12);
        } else {
            $phone = $raw;
        }

        // Create superadmin (upsert by email)
        User::updateOrCreate(
            ['email' => $data['admin_email']],
            [
                'name' => $data['admin_name'],
                'email' => $data['admin_email'],
                'phone' => $phone,
                'password' => Hash::make($data['admin_password']),
                'role' => 'superadmin',
                'is_verified' => true,
                'phone_verified_at' => now(),
            ]
        );

        // Mark installed
        Storage::disk('local')->put('installed', now()->toDateTimeString());

        return redirect()->route('login')->with('status', 'Installation complete. You can now log in.');
    }

    protected function setEnv(array $pairs): void
    {
        $path = base_path('.env');
        try {
            $contents = file_get_contents($path);
        } catch (\Throwable $e) {
            $contents = '';
        }
        $lines = preg_split("/[\r\n]+/", $contents ?? '');
        $env = [];
        foreach ($lines as $line) {
            if ($line === '' || str_starts_with($line, '#')) continue;
            $pos = strpos($line, '=');
            if ($pos !== false) {
                $key = substr($line, 0, $pos);
                $value = substr($line, $pos + 1);
                $env[$key] = $value;
            }
        }
        foreach ($pairs as $k => $v) {
            $env[$k] = $this->escapeEnv($v);
        }
        $out = '';
        foreach ($env as $k => $v) {
            $out .= $k.'='.$v."\n";
        }
        file_put_contents($path, $out);
    }

    protected function escapeEnv($value): string
    {
        if ($value === null) return '';
        $v = (string)$value;
        if (preg_match('/\s/', $v) || str_contains($v, '#')) {
            return '"'.str_replace('"', '\\"', $v).'"';
        }
        return $v;
    }
}

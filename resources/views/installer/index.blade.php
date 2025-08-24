<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>ElanSwap Installer</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root{
            --bg:#0f172a; --card:#111827; --muted:#94a3b8; --primary:#22c55e; --danger:#ef4444; --border:#1f2937; --input:#0b1220;
        }
        *{ box-sizing: border-box; }
        body{ margin:0; font-family: 'Inter', system-ui, -apple-system, Segoe UI, Roboto, Helvetica, Arial, sans-serif; background: radial-gradient(1200px 600px at 10% 10%, #0b1220, #0f172a), #0f172a; color:#e5e7eb; }
        .container{ max-width:900px; margin:40px auto; padding:20px; }
        .card{ background:linear-gradient(180deg, rgba(255,255,255,0.02), rgba(255,255,255,0)); border:1px solid var(--border); border-radius:16px; padding:24px; box-shadow: 0 10px 40px rgba(0,0,0,0.35); }
        h1{ margin:0 0 6px; font-size:28px; font-weight:700; letter-spacing:0.2px; }
        p.muted{ margin:0 0 20px; color:var(--muted); }
        .grid{ display:grid; grid-template-columns:1fr 1fr; gap:16px; }
        label{ display:block; margin:0 0 8px; font-size:13px; color:#cbd5e1; }
        input{ width:100%; background:var(--input); border:1px solid var(--border); color:#e5e7eb; padding:12px 14px; border-radius:10px; outline:none; transition: border .2s ease; }
        input:focus{ border-color:#334155; }
        .actions{ display:flex; gap:12px; margin-top:18px; }
        .btn{ padding:12px 16px; border-radius:10px; border:1px solid transparent; cursor:pointer; font-weight:600; }
        .btn-primary{ background:linear-gradient(90deg, #16a34a, #22c55e); color:#07110a; }
        .btn-outline{ background:transparent; border-color:#334155; color:#cbd5e1; }
        .error{ background:#1f2937; border:1px solid #374151; color:#fecaca; padding:10px 12px; border-radius:10px; }
        .two-col{ display:grid; grid-template-columns:1fr 1fr; gap:24px; }
        .section-title{ margin:12px 0; font-size:16px; color:#d1d5db; font-weight:700; }
        .foot{ margin-top:10px; color:var(--muted); font-size:12px; }
    </style>
</head>
<body>
<div class="container">
    <div class="card">
        <h1>ElanSwap Installer</h1>
        <p class="muted">Set up database and create the first Super Admin account.</p>

        @if ($errors->any())
            <div class="error" style="margin-bottom:12px;">
                <strong>Fix the following:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('installer.store') }}">
            @csrf

            <div class="section-title">Database (MySQL)</div>
            <div class="two-col">
                <div>
                    <label>DB Host</label>
                    <input name="db_host" value="{{ old('db_host', $env['DB_HOST'] ?? '127.0.0.1') }}" required />
                </div>
                <div>
                    <label>DB Port</label>
                    <input name="db_port" value="{{ old('db_port', $env['DB_PORT'] ?? '3306') }}" required />
                </div>
                <div>
                    <label>DB Name</label>
                    <input name="db_database" value="{{ old('db_database', $env['DB_DATABASE'] ?? '') }}" required />
                </div>
                <div>
                    <label>DB Username</label>
                    <input name="db_username" value="{{ old('db_username', $env['DB_USERNAME'] ?? '') }}" required />
                </div>
                <div style="grid-column: 1 / span 2;">
                    <label>DB Password</label>
                    <input type="password" name="db_password" value="{{ old('db_password') }}" />
                </div>
                <input type="hidden" name="db_connection" value="mysql" />
            </div>

            <div class="section-title">Super Admin</div>
            <div class="grid">
                <div>
                    <label>Full Name</label>
                    <input name="admin_name" value="{{ old('admin_name') }}" required />
                </div>
                <div>
                    <label>Email</label>
                    <input type="email" name="admin_email" value="{{ old('admin_email') }}" required />
                </div>
                <div>
                    <label>Phone (e.g. 255700000001)</label>
                    <input name="admin_phone" value="{{ old('admin_phone') }}" required />
                </div>
                <div>
                    <label>Password</label>
                    <input type="password" name="admin_password" required />
                </div>
            </div>

            <div class="actions">
                <button class="btn btn-primary" type="submit">Install</button>
                <a class="btn btn-outline" href="{{ route('login') }}">Cancel</a>
            </div>

            <div class="foot">
                Installer is controlled by INSTALLER_ENABLED=true in your .env. After success, it will be disabled and marked installed.
            </div>
        </form>

    </div>
</div>
</body>
</html>

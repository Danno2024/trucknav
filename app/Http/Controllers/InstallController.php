<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\Installer;
use Database\Seeders\ForumCategorySeeder;
use Database\Seeders\ForumDemoSeeder;
use Database\Seeders\RoadRestrictionSeeder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class InstallController extends Controller
{
    // ─── Step 1: Welcome + requirements ──────────────────────────

    public function welcome(): View
    {
        $checks = Installer::requirements();
        $blocked = Installer::hasFailures($checks);

        return view('install.welcome', compact('checks', 'blocked'));
    }

    // ─── Step 2: Database ────────────────────────────────────────

    public function showDatabase(): View
    {
        return view('install.database');
    }

    public function storeDatabase(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'driver' => 'required|in:sqlite,mysql',
            'host' => 'required_if:driver,mysql|string|max:255',
            'port' => 'required_if:driver,mysql|integer|min:1|max:65535',
            'database' => 'required|string|max:255',
            'username' => 'nullable|string|max:255',
            'password' => 'nullable|string',
        ]);

        $driver = $validated['driver'];

        if ($driver === 'sqlite') {
            $dbPath = $validated['database'];
            if (! str_contains($dbPath, DIRECTORY_SEPARATOR) && ! str_contains($dbPath, '/')) {
                $dbPath = database_path($dbPath);
            }
            $dbConfig = ['database' => $dbPath];
        } else {
            $dbConfig = [
                'host' => $validated['host'],
                'port' => (int) $validated['port'],
                'database' => $validated['database'],
                'username' => $validated['username'] ?? '',
                'password' => $validated['password'] ?? '',
            ];
        }

        if ($error = Installer::testConnection($driver, $dbConfig)) {
            return back()->withInput()->withErrors(['database' => 'Connection failed: '.$error]);
        }

        $env = [
            'DB_CONNECTION' => $driver,
            'SESSION_DRIVER' => 'file',
            'CACHE_STORE' => 'file',
            'QUEUE_CONNECTION' => 'sync',
        ];

        if ($driver === 'sqlite') {
            $env['DB_DATABASE'] = $dbConfig['database'];
        } else {
            $env['DB_HOST'] = $dbConfig['host'];
            $env['DB_PORT'] = $dbConfig['port'];
            $env['DB_DATABASE'] = $dbConfig['database'];
            $env['DB_USERNAME'] = $dbConfig['username'];
            $env['DB_PASSWORD'] = $dbConfig['password'];
        }

        Installer::writeEnv($env);
        $this->applyDatabaseConfig($driver, $dbConfig);

        try {
            Artisan::call('migrate', ['--force' => true]);
        } catch (\Throwable $e) {
            return back()->withInput()->withErrors(['database' => 'Migration failed: '.$e->getMessage()]);
        }

        return redirect()->route('install.settings');
    }

    // ─── Step 3: App settings ────────────────────────────────────

    public function showSettings(): View
    {
        return view('install.settings');
    }

    public function storeSettings(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:100',
            'app_url' => 'required|url|max:255',
        ]);

        if (empty(env('APP_KEY'))) {
            Artisan::call('key:generate', ['--force' => true]);
        }

        Installer::writeEnv([
            'APP_NAME' => $validated['app_name'],
            'APP_URL' => rtrim($validated['app_url'], '/'),
            'APP_ENV' => 'production',
            'APP_DEBUG' => 'false',
        ]);

        return redirect()->route('install.admin');
    }

    // ─── Step 4: Admin account ───────────────────────────────────

    public function showAdmin(): View
    {
        return view('install.admin');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'password' => 'required|string|min:8|confirmed',
            'site_content' => 'required|in:demo,blank',
        ]);

        User::updateOrCreate(
            ['email' => $validated['email']],
            [
                'name' => $validated['name'],
                'password' => Hash::make($validated['password']),
                'role' => 'admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        // Forum categories are structural — always seeded so the forum works.
        (new ForumCategorySeeder)->run();

        if ($validated['site_content'] === 'demo') {
            (new RoadRestrictionSeeder)->run();
            (new ForumDemoSeeder)->run();
        }

        return redirect()->route('install.complete');
    }

    // ─── Step 5: Done ────────────────────────────────────────────

    public function complete(): View
    {
        file_put_contents(Installer::lockPath(), 'Installed at '.now()->toDateTimeString());

        $admin = User::where('role', 'admin')->orderBy('id')->first();

        return view('install.complete', compact('admin'));
    }

    // ─── Helpers ─────────────────────────────────────────────────

    protected function applyDatabaseConfig(string $driver, array $dbConfig): void
    {
        config(['database.default' => $driver]);

        if ($driver === 'sqlite') {
            config(['database.connections.sqlite.database' => $dbConfig['database']]);
        } else {
            config([
                'database.connections.mysql.host' => $dbConfig['host'],
                'database.connections.mysql.port' => $dbConfig['port'],
                'database.connections.mysql.database' => $dbConfig['database'],
                'database.connections.mysql.username' => $dbConfig['username'],
                'database.connections.mysql.password' => $dbConfig['password'],
            ]);
        }

        DB::purge($driver);
        DB::purge();
    }
}

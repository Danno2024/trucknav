<?php

namespace App\Support;

use PDO;
use PDOException;

class Installer
{
    public static function lockPath(): string
    {
        return storage_path('installed');
    }

    public static function isInstalled(): bool
    {
        return is_file(self::lockPath());
    }

    /**
     * Ensure an APP_KEY exists before anything needs encryption
     * (cookies, sessions, CSRF). Persists to .env when writable;
     * otherwise sets a runtime-only key so the installer can still
     * render (the requirements step will flag the unwritable .env).
     */
    public static function ensureEnvAndKey(): void
    {
        if (! empty(config('app.key'))) {
            return;
        }

        $key = 'base64:'.base64_encode(random_bytes(32));

        try {
            self::writeEnv(['APP_KEY' => $key]);
        } catch (\Throwable $e) {
            // .env not writable yet — runtime key only.
        }

        config(['app.key' => $key]);
    }

    /**
     * Server requirements checklist. Each item: ['label', 'pass', 'hint'].
     */
    public static function requirements(): array
    {
        $checks = [];

        $checks[] = [
            'label' => 'PHP 8.2 or higher',
            'pass' => version_compare(PHP_VERSION, '8.2.0', '>='),
            'hint' => 'Current: PHP '.PHP_VERSION,
        ];

        foreach (['openssl', 'pdo', 'mbstring', 'tokenizer', 'xml', 'ctype', 'json', 'fileinfo'] as $ext) {
            $checks[] = [
                'label' => "PHP extension: {$ext}",
                'pass' => extension_loaded($ext),
                'hint' => extension_loaded($ext) ? 'Loaded' : 'Missing — enable it in php.ini',
            ];
        }

        $checks[] = [
            'label' => 'Database driver (pdo_sqlite or pdo_mysql)',
            'pass' => extension_loaded('pdo_sqlite') || extension_loaded('pdo_mysql'),
            'hint' => (extension_loaded('pdo_sqlite') || extension_loaded('pdo_mysql'))
                ? 'Available'
                : 'Missing — enable pdo_sqlite or pdo_mysql',
        ];

        foreach (['storage' => 'Storage directory writable', 'bootstrap/cache' => 'Bootstrap cache writable'] as $path => $label) {
            $full = base_path($path);
            $checks[] = [
                'label' => $label,
                'pass' => is_dir($full) && is_writable($full),
                'hint' => $full,
            ];
        }

        $envFile = base_path('.env');
        $checks[] = [
            'label' => '.env writable (or creatable)',
            'pass' => (is_file($envFile) && is_writable($envFile)) || (! is_file($envFile) && is_writable(base_path())),
            'hint' => $envFile,
        ];

        return $checks;
    }

    public static function hasFailures(array $checks): bool
    {
        foreach ($checks as $check) {
            if (! $check['pass']) {
                return true;
            }
        }

        return false;
    }

    /**
     * Test a database connection without touching app config.
     * Returns null on success, error message on failure.
     */
    public static function testConnection(string $driver, array $config): ?string
    {
        try {
            if ($driver === 'sqlite') {
                $path = $config['database'] ?? database_path('database.sqlite');
                $dir = dirname($path);
                if (! is_dir($dir) && ! mkdir($dir, 0755, true)) {
                    return "Could not create directory: {$dir}";
                }
                if (! is_file($path) && @touch($path) === false) {
                    return "Could not create SQLite file: {$path}";
                }
                new PDO('sqlite:'.$path);
            } else {
                $dsn = "mysql:host={$config['host']};port={$config['port']};dbname={$config['database']}";
                new PDO($dsn, $config['username'], $config['password'] ?? '', [
                    PDO::ATTR_TIMEOUT => 5,
                ]);
            }
        } catch (PDOException $e) {
            return $e->getMessage();
        }

        return null;
    }

    /**
     * Safely set key/value pairs in the .env file.
     */
    public static function writeEnv(array $values): void
    {
        $path = base_path('.env');

        if (! is_file($path)) {
            $example = base_path('.env.example');
            copy($example, $path);
        }

        $contents = file_get_contents($path);

        foreach ($values as $key => $value) {
            $value = (string) $value;
            if ($value === '' || preg_match('/[\s#\'"]/', $value)) {
                $value = '"'.str_replace('"', '\\"', $value).'"';
            }

            $pattern = "/^{$key}=.*$/m";
            $line = "{$key}={$value}";

            if (preg_match($pattern, $contents)) {
                $contents = preg_replace($pattern, $line, $contents);
            } else {
                $contents = rtrim($contents)."\n{$line}\n";
            }
        }

        file_put_contents($path, $contents);
    }
}

<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->ensureDatabaseExistsAndMigrated();
    }

    /**
     * Automatically create database and run migrations/seeders if not present
     */
    protected function ensureDatabaseExistsAndMigrated(): void
    {
        // Only run for MySQL connection in local or testing environments
        if (config('database.default') !== 'mysql' || !app()->environment('local', 'testing')) {
            return;
        }

        // Avoid recursion during artisan commands
        if (app()->runningInConsole() && !request()->isMethod('*')) {
            return;
        }

        $dbName = config('database.connections.mysql.database');
        if (!$dbName) {
            return;
        }

        $needsMigrationAndSeed = false;

        try {
            // Test connecting to the configured database
            DB::connection('mysql')->getPdo();

            // If connected but migrations table doesn't exist, we need to run migrations & seeds
            if (!Schema::connection('mysql')->hasTable('migrations')) {
                $needsMigrationAndSeed = true;
            }
        } catch (\Throwable $e) {
            // Database does not exist yet (Code 1049) -> Create it via raw PDO
            $host = config('database.connections.mysql.host', '127.0.0.1');
            $port = config('database.connections.mysql.port', 3306);
            $user = config('database.connections.mysql.username', 'root');
            $pass = config('database.connections.mysql.password', '');

            try {
                $pdo = new \PDO("mysql:host={$host};port={$port}", $user, $pass, [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                ]);

                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;");

                DB::purge('mysql');
                DB::reconnect('mysql');

                $needsMigrationAndSeed = true;
            } catch (\Throwable $ex) {
                Log::warning("Auto-database creation failed: " . $ex->getMessage());
                return;
            }
        }

        if ($needsMigrationAndSeed) {
            try {
                Artisan::call('migrate', ['--force' => true]);
                Artisan::call('db:seed', ['--force' => true]);
            } catch (\Throwable $mEx) {
                Log::warning("Auto-migration/seed failed: " . $mEx->getMessage());
            }
        }
    }
}

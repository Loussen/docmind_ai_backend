<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Command\Command as CommandAlias;

class MigrateToMysql extends Command
{
    protected $signature = 'db:migrate-to-mysql {--dry-run : Show what would be migrated without actually doing it}';
    protected $description = 'Migrate data from SQLite to MySQL';

    private array $tables = [
        'users',
        'password_reset_tokens',
        'sessions',
        'documents',
        'summaries',
        'subscriptions',
        'usage_logs',
        'personal_access_tokens',
    ];

    public function handle(): int
    {
        $dryRun = $this->option('dry-run');

        $this->info('🚀 Starting SQLite to MySQL migration...');
        $this->newLine();

        // Check if SQLite database exists
        $sqlitePath = database_path('database.sqlite');
        if (!file_exists($sqlitePath)) {
            $this->error('SQLite database not found at: ' . $sqlitePath);
            return CommandAlias::FAILURE;
        }

        // Configure SQLite connection temporarily
        config(['database.connections.sqlite_source' => [
            'driver' => 'sqlite',
            'database' => $sqlitePath,
            'prefix' => '',
            'foreign_key_constraints' => false,
        ]]);

        // Check MySQL connection
        try {
            DB::connection('mysql')->getPdo();
            $this->info('✅ MySQL connection successful');
        } catch (\Exception $e) {
            $this->error('❌ Cannot connect to MySQL: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Make sure your .env file has correct MySQL credentials:');
            $this->line('DB_CONNECTION=mysql');
            $this->line('DB_HOST=127.0.0.1');
            $this->line('DB_PORT=3306');
            $this->line('DB_DATABASE=docsmind_db');
            $this->line('DB_USERNAME=root');
            $this->line('DB_PASSWORD=');
            return CommandAlias::FAILURE;
        }

        if ($dryRun) {
            $this->warn('🔍 DRY RUN MODE - No data will be modified');
            $this->newLine();
        }

        // Run migrations on MySQL first
        if (!$dryRun) {
            $this->info('📦 Running migrations on MySQL...');
            $this->call('migrate', ['--database' => 'mysql', '--force' => true]);
            $this->newLine();
        }

        // Migrate each table
        foreach ($this->tables as $table) {
            $this->migrateTable($table, $dryRun);
        }

        $this->newLine();
        $this->info('✅ Migration completed successfully!');
        
        if (!$dryRun) {
            $this->newLine();
            $this->warn('📝 Next steps:');
            $this->line('1. Update your .env file: DB_CONNECTION=mysql');
            $this->line('2. Test your application');
            $this->line('3. Backup and remove the SQLite file when ready');
        }

        return CommandAlias::SUCCESS;
    }

    private function migrateTable(string $table, bool $dryRun): void
    {
        try {
            // Check if source table exists
            if (!Schema::connection('sqlite_source')->hasTable($table)) {
                $this->warn("⏭️  Table '{$table}' does not exist in SQLite, skipping...");
                return;
            }

            // Get data from SQLite
            $data = DB::connection('sqlite_source')->table($table)->get();
            $count = $data->count();

            if ($count === 0) {
                $this->line("📭 Table '{$table}': 0 records (empty)");
                return;
            }

            if ($dryRun) {
                $this->info("📊 Table '{$table}': {$count} records would be migrated");
                return;
            }

            // Clear existing data in MySQL table
            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::connection('mysql')->table($table)->truncate();

            // Insert data in chunks
            $chunks = $data->chunk(100);
            $bar = $this->output->createProgressBar($count);
            $bar->setFormat(" %current%/%max% [%bar%] %percent:3s%% -- {$table}");

            foreach ($chunks as $chunk) {
                $records = $chunk->map(function ($item) {
                    return (array) $item;
                })->toArray();

                DB::connection('mysql')->table($table)->insert($records);
                $bar->advance(count($records));
            }

            DB::connection('mysql')->statement('SET FOREIGN_KEY_CHECKS=1;');

            $bar->finish();
            $this->newLine();
            $this->info("✅ Table '{$table}': {$count} records migrated");

        } catch (\Exception $e) {
            $this->error("❌ Error migrating '{$table}': " . $e->getMessage());
        }
    }
}

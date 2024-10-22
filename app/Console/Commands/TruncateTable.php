<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TruncateTable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'table:truncate {table : The name of the table to truncate}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Truncate the specified table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Retrieve the table name from the command argument
        $table = $this->argument('table');

        // Check if the table exists
        if (! DB::getSchemaBuilder()->hasTable($table)) {
            $this->error("Table '$table' does not exist.");

            return;
        }

        // Perform the truncation
        try {
            DB::table($table)->truncate();
            $this->info("Table '$table' has been truncated successfully.");
        } catch (\Exception $e) {
            $this->error('An error occurred while truncating the table: '.$e->getMessage());
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Import data from SQL file
        $sqlFile = database_path('seeders/wowboxshop_data.sql');
        
        if (file_exists($sqlFile)) {
            DB::unprepared(file_get_contents($sqlFile));
            $this->command->info('Data imported successfully from SQL file!');
        } else {
            $this->command->error('SQL file not found: ' . $sqlFile);
        }
    }
}

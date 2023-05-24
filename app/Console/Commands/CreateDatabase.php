<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use PDO;

class CreateDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new mysql database schema based on the userid';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        try {

            $users = User::whereHas('stores', function ($query) {
                $query->where('status', config('params.active'));
            })
                ->where('role', '!=', config('params.admin_role'))
                ->where('is_db_created', 0)->get();

            if (!empty($users) && count($users) > 0) {

                foreach ($users as $user) {
                    $user_db_name = config('params.user_db_name');
                    $dbname = $user_db_name . '_' . $user->id;

                    $connection = DB::connection()->getPDO()->getAttribute(PDO::ATTR_DRIVER_NAME);

                    $hasDb = DB::connection($connection)->select("SELECT SCHEMA_NAME FROM INFORMATION_SCHEMA.SCHEMATA WHERE SCHEMA_NAME = " . "'" . $dbname . "'");
                    if (empty($hasDb)) {
                        DB::connection($connection)->select('CREATE DATABASE ' . $dbname);
                        //connect new database
                        Config::set('database.connections.mysql.database', $dbname);

                        DB::reconnect('mysql');
                        if (!Schema::hasTable('migrationa')) {
                            Artisan::call("create:migration"); //create migrations table for new created db
                        }

                        //get all migration files to create table in new db
                        $migration_files = config('migrations.file_names');

                        if (!empty($migration_files)) {
                            foreach ($migration_files as $migration_file) {
                                Artisan::call('migrate', ['--path' => 'database/migrations/' . $migration_file, '--force' => true]);
                            }
                        }

                        DB::purge('mysql');

                        //connect main database back
                        Config::set('database.connections.mysql.database', env('DB_DATABASE', 'sterio'));
                        DB::reconnect('mysql');

                        $user->is_db_created = 1;
                        $user->save();

                        $this->info("Database '$dbname' created for '$connection' connection");
                    }
                }
            } else {
                $this->info("User not found for creating database.");
            }

        } catch (\Exception $e) {
            $this->error($e->getMessage());
        }
    }
}

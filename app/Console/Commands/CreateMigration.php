<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMigration extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:migration';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create migration table for new user database';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Schema::create('migrations', function (Blueprint $table) {
            $table->id();
            $table->string('migration');
            $table->integer('batch');
        });
        return true;
    }
}
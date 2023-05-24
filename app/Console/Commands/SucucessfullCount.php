<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class SucucessfullCount extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sucucessfullcount:cron';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = User::whereHas('getOrders')->where('status', config('params.active'))->get();
		print_r($users); die;
		return Command::SUCCESS;
    }
}

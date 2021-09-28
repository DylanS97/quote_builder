<?php

namespace App\Console\Commands;

use App\Mail\mailCron;
use App\Models\Quote;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class cronEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:mail';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $date = date('d/m/Y', strtotime('today'));

        $quotes = Quote::whereDate('created_at', date('Y/m/d', strtotime('today')))->get();
        
        Mail::to('admin@admin.com')->send(new mailCron($date, $quotes));
    }
}

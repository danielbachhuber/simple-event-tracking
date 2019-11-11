<?php

namespace App\Console\Commands;

use App\Event;
use Illuminate\Console\Command;

class PruneEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'set:prune-events {daysAgo}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Prunes events older than a given time.';

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
     * @return mixed
     */
    public function handle()
    {
        $affected = Event::where('created_at', '<', now()->sub($this->argument('daysAgo'), 'days'))->delete();
        $this->info(sprintf('Deleted %d entries.', $affected));
    }
}

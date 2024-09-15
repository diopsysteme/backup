<?php

namespace App\Console\Commands;

use App\Jobs\ArchiveDetteJob;
use Illuminate\Console\Command;

class ArchiveDettes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:archive-dettes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        dispatch(new ArchiveDetteJob());
    }
}

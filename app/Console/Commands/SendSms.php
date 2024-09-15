<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SendDebtReminderSms;

class SendSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-sms';

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
        dispatch(new SendDebtReminderSms());

        $this->info('Sms sent au client.');
    }
}

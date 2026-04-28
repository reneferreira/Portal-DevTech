<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Minishlink\WebPush\VAPID;

class GenerateVapidKeys extends Command
{
    protected $signature = 'push:vapid-keys';

    protected $description = 'Generate VAPID keys for browser push notifications.';

    public function handle(): int
    {
        $keys = VAPID::createVapidKeys();

        $this->line('Add these values to your environment:');
        $this->newLine();
        $this->line('VAPID_PUBLIC_KEY=' . $keys['publicKey']);
        $this->line('VAPID_PRIVATE_KEY=' . $keys['privateKey']);

        return self::SUCCESS;
    }
}

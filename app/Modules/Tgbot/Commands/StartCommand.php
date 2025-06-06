<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use Telegram\Bot\Commands\Command;

class StartCommand extends Command
{
    protected string $name = 'start';
    protected string $description = 'вкл. бот';

    public function handle()
    {
        $this->telegram->triggerCommand('set_timezone', $this->update);
    }
}

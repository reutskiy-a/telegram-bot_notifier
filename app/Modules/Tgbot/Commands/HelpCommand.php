<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use Telegram\Bot\Commands\Command;

class HelpCommand extends Command
{
    protected string $name = 'help';
    protected string $description = 'список доступных команд';

    public function handle()
    {
        // Получаем список всех зарегистрированных команд
        $commands = $this->getTelegram()->getCommands();

        // Формируем текстовый ответ
        $text = "Доступные команды:\n";
        foreach ($commands as $name => $command) {
            $text .= sprintf("/%s - %s\n", $name, $command->getDescription());
        }

        // Отправляем ответ пользователю
        $this->replyWithMessage([
            'text' => $text
        ]);
    }
}

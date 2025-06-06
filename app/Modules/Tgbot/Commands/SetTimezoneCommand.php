<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Models\Chat;
use App\Modules\Tgbot\Utils\TgHelper;
use Carbon\Carbon;
use Telegram\Bot\Commands\Command;

class SetTimezoneCommand extends Command
{
    protected string $name = 'set_timezone';
    protected string $description = 'установить часовой пояс';

    public function handle()
    {
        $message = $this->update->getMessage();

        $utc = TgHelper::getUtcTimezoneFromUserCommand($message->text);

        if ($utc === null) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => 'установите ваш часовой пояс (UTC), например:' . PHP_EOL .
                    'для Красноярска /set_timezone#+07:00' . PHP_EOL .
                    'для Москвы /set_timezone#+03:00' . PHP_EOL .
                    'для New Your /set_timezone#-04:00'
            ]);

            return;
        }

        $chat = Chat::updateOrCreate(
            [
                'id' => $message->chat->id,
            ],
            [
                'id' => $message->chat->id,
                'type' => $message->chat->type,
                'timezone' => $utc
            ]
        );

        $this->telegram->sendMessage([
            'chat_id' => $chat->id,
            'parse_mode' => 'HTML',
            'text' => 'часовой пояс для заметок установлен ' . $chat->timezone
        ]);
    }
}

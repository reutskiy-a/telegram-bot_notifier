<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Models\Chat;
use Telegram\Bot\Commands\Command;

class GetTimezoneCommand extends Command
{
    protected string $name = 'get_timezone';
    protected string $description = 'установленный часовой пояс';

    public function handle()
    {
        $message = $this->update->getMessage();

        $chat = Chat::find($message->chat->id);

        if (null === $chat) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => 'часовой пояс не установлен'
            ]);

            return;
        }

        $this->telegram->sendMessage([
            'chat_id' => $message->chat->id,
            'parse_mode' => 'HTML',
            'text' => 'часовой пояс для заметок ' . $chat->timezone
        ]);
    }
}

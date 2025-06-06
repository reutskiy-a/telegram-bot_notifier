<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Models\PendingNotice;
use Telegram\Bot\Commands\Command;

class CancelCommand extends Command
{
    protected string $name = 'cancel';
    protected string $description = 'отменить активный диалог с ботом';

    public function handle()
    {
        $message = $this->getUpdate()->getMessage();

        $pendingNotice = PendingNotice::where('chat_id', $message->chat->id)
            ->where('user_id', $message->from->id)
            ->first();

        if ($pendingNotice === null) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => 'Нет активных операций',
            ]);
        } else {
            $pendingNotice->delete();

            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => 'Операция отменена',
            ]);
        }
    }
}

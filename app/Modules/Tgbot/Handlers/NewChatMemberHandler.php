<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Handlers;

use App\Modules\Tgbot\Models\Chat;
use App\Modules\Tgbot\Utils\TgHelper;
use Telegram\Bot\Objects\Update;

class NewChatMemberHandler
{

    public function handle(Update $update): void
    {
        if (TgHelper::didTheyKickedBot($update, config('app.tg_bot_id'))) {
            $this->deleteUser($update->getChat()->id);
        }

        if (TgHelper::didTheyAddBot($update, config('app.tg_bot_id'))) {
            $this->restoreUser($update->getChat()->id);
        }
    }

    private function deleteUser(string|int $chatId)
    {
        $chat = Chat::find($chatId);
        $chat?->delete();
    }

    private function restoreUser(string|int $chatId)
    {
        $chat = Chat::onlyTrashed()->find($chatId);
        $chat?->restore();
    }
}

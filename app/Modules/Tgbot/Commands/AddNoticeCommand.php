<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Conversations\Constants\AddNoticeMsg;
use App\Modules\Tgbot\Enums\NoticeConversationStatus;
use App\Modules\Tgbot\Models\Chat;
use App\Modules\Tgbot\Models\PendingNotice;
use App\Modules\Tgbot\Models\TgUser;
use Telegram\Bot\Commands\Command;

class AddNoticeCommand extends Command
{
    protected string $name = 'add_notice';
    protected string $description = 'добавить заметку';

    public function handle()
    {
        $message = $this->update->getMessage();

        if (! $this->isTimezoneSet($message->chat->id)) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => "Прервано. Сначала установите часовой пояс."
            ]);

            $this->telegram->triggerCommand('set_timezone', $this->update);
            return;
        }

        $user = TgUser::updateOrCreate(
            ['id' => $message->from->id],
            $message->from->toArray()
        );

        $response = $this->telegram->sendMessage([
            'chat_id' => $message->chat->id,
            'parse_mode' => 'HTML',
            'text' => "$user->first_name, " . AddNoticeMsg::SET_DAY_OF_WEEK_MSG
        ]);


        PendingNotice::updateOrCreate(
            [
                'chat_id' => (string) $message->chat->id,
                'user_id' => (string) $message->from->id,
            ],
            [
                'chat_id' => (string) $message->chat->id,
                'user_id' => (string) $message->from->id,
                'day_of_week' => null,
                'time' => null,
                'text' => null,
                'status' => NoticeConversationStatus::DAY_OF_WEEK_WAITING,
            ]
        );
    }

    private function deleteBotLastMessage(int|string $chatId, int|string $userId): bool
    {
        $botLastMessageId = PendingNotice::getByChatIdAndUserId($chatId, $userId)
            ->bot_last_message_id ?? null;

        if (null === $botLastMessageId) {
            return false;
        }

        $this->telegram->deleteMessage([
            'chat_id' => $chatId,
            'message_id' => $botLastMessageId
        ]);

        return true;
    }

    private function isTimezoneSet(int|string $chatId): bool
    {
        $chatTimezone = Chat::where('id', $chatId)->first();
        $timezone = $chatTimezone->timezone ?? null;
        return $timezone !== null;
    }

}

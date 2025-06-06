<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Conversations\Constants\OperationNoticeMsg;
use App\Modules\Tgbot\Models\Notice;
use Telegram\Bot\Commands\Command;

class DeleteNoticeCommand extends Command
{
    protected string $name = 'delete';
    protected string $description = 'удалить заметку: /delete#id';

    public function handle()
    {
        $message = $this->update->getMessage();

        $id = $this->getIdFromUserCommand($message->text);

        if ($id === null) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => OperationNoticeMsg::DELETE_NOTICE_ID_EMPTY,
            ]);

            return;
        }

        $notice = Notice::find($id);

        if ($notice === null || $notice->chat_id != $message->chat->id) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => OperationNoticeMsg::NOTICE_ID_ERROR,
            ]);

            return;
        }

        $notice->delete();

        $this->telegram->sendMessage([
            'chat_id' => $message->chat->id,
            'parse_mode' => 'HTML',
            'text' => "заметка <b>id: $id</b> удалена",
        ]);

    }

    private function getIdFromUserCommand(string $text): ?int
    {
        $doesValueSet = str_contains($text, '#');

        if ($doesValueSet) {
            $value =  explode('#', $text)[1] ?? null;

            if (is_numeric($value)) {
                return (int) $value;
            };

            return null;
        }

        return null;
    }
}

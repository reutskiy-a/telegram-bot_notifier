<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Conversations\Constants\OperationNoticeMsg;
use App\Modules\Tgbot\Models\Notice;
use Telegram\Bot\Commands\Command;

class GetNoticeCommand extends Command
{
    protected string $name = 'get_notice';
    protected string $description = 'показать заметку полностью /get_notice#id';

    public function handle()
    {
        $message = $this->update->getMessage();

        $id = $this->getIdFromUserCommand($message->text);

        if ($id === null) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => OperationNoticeMsg::GET_NOTICE_ID_EMPTY,
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

        $dayOfWeek = implode(" ", json_decode($notice->day_of_week));
        $this->telegram->sendMessage([
            'chat_id' => $message->chat->id,
            'parse_mode' => 'HTML',
            'text' => "<b>id:</b> $notice->id, <b>дни:</b> $dayOfWeek, <b>время:</b> $notice->time",
        ]);

        $this->telegram->sendMessage([
            'chat_id' => $message->chat->id,
            'parse_mode' => 'HTML',
            'text' => $notice->text,
        ]);

    }

    private function getIdFromUserCommand(string $string): ?int
    {
        $doesValueSet = str_contains($string, '#');

        if ($doesValueSet) {
            $value =  explode('#', $string)[1] ?? null;

            if (is_numeric($value)) {
                return (int) $value;
            };

            return null;
        }

        return null;
    }

}

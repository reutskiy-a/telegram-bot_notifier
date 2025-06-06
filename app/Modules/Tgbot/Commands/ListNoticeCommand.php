<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Commands;

use App\Modules\Tgbot\Conversations\Constants\OperationNoticeMsg;
use App\Modules\Tgbot\Models\Notice;
use Telegram\Bot\Commands\Command;

class ListNoticeCommand extends Command
{
    protected string $name = 'list';
    protected string $description = 'список заметок. Вывод по 10 шт., смещение /list#10';
    private const LIMIT = 10;

    public function handle()
    {
        $message = $this->update->getMessage();

        $offset = $this->getOffsetFromUserCommand($message->text);

        $noticeCount = Notice::where('chat_id', $message->chat->id)
            ->where('user_id', $message->from->id)
            ->count();

        if ($offset >= $noticeCount) {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'parse_mode' => 'HTML',
                'text' => OperationNoticeMsg::NOTICE_OFFSET_ERROR . " $noticeCount",
            ]);

            return;
        }

        $notices = Notice::where('chat_id', $message->chat->id)
            ->where('user_id', $message->from->id)
            ->offset($offset)->limit(self::LIMIT)->get();


        $formId = $offset + 1;
//        $toId = $offset + self::LIMIT;
        $toId = (($noticeCount - $offset) > self::LIMIT) ? $offset + self::LIMIT : $offset + ($noticeCount - $offset);
        $answer = "Всего заметок: $noticeCount шт. | с $formId по $toId:" . PHP_EOL . PHP_EOL;


        foreach ($notices as $notice) {

            $text = $notice->text;

            $dayOfWeek = implode(" ", json_decode($notice->day_of_week));

            if (mb_strlen($text) > 50) {
                $text = mb_substr($text, 0, 50) . '..';
            }

            $answer .= "<b>id:</b> $notice->id, <b>дни:</b> $dayOfWeek, <b>время:</b> $notice->time" . PHP_EOL . "<b>text:</b> $text" . PHP_EOL . PHP_EOL;
        }

        $this->telegram->sendMessage([
            'chat_id' => $message->chat->id,
            'parse_mode' => 'HTML',
            'text' => $answer,
        ]);
    }

    private function getOffsetFromUserCommand($string): int
    {
        $doesValueSet = str_contains($string, '#');

        if ($doesValueSet) {
            $value =  explode('#', $string)[1] ?? 0;

            if (is_numeric($value)) {
                return (int) $value;
            };

            return 0;
        }

        return 0;
    }
}

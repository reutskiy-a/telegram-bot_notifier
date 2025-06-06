<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Conversations;

use App\Modules\Tgbot\Conversations\Constants\AddNoticeMsg;
use App\Modules\Tgbot\Enums\NoticeConversationStatus;
use App\Modules\Tgbot\Models\Chat;
use App\Modules\Tgbot\Models\Notice;
use App\Modules\Tgbot\Models\PendingNotice;
use App\Modules\Tgbot\Utils\TgHelper;
use App\Modules\Tgbot\Validation\UserAnswerValidation;
use Carbon\Carbon;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class AddNoticeConversation
{
    private Api $telegram;

    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    public function handle(Update $update)
    {
        $message = $update->getMessage();

        $pendingNotice = PendingNotice::where('chat_id', $message->chat->id)
            ->where('user_id', $message->from->id)
            ->first();

        if ($pendingNotice === null) {
            return;
        }

        $method = $this->defineMethod($pendingNotice->status);
        $this->$method($pendingNotice, $message);
    }

    private function defineMethod(string $status): string
    {
        return match($status) {
            NoticeConversationStatus::DAY_OF_WEEK_WAITING->value => 'handleDayOfWeekWaiting',
            NoticeConversationStatus::TIME_WAITING->value => 'handleTimeWaiting',
            NoticeConversationStatus::TEXT_WAITING->value => 'handleTextWaiting',
        };
    }

    private function handleDayOfWeekWaiting(PendingNotice $pendingNotice, Message $message): void
    {
        $user = $pendingNotice->user;
        $validatedAnswer = UserAnswerValidation::isDaysOfWeekValid($message->text);

        if (! $validatedAnswer->valid) {
            $this->telegram->sendMessage([
                'chat_id' => $pendingNotice->chat_id,
                'parse_mode' => 'HTML',
                'text' => "$user->first_name, $validatedAnswer->errorMsg"
            ]);

            return;
        }

        $pendingNotice->day_of_week = $validatedAnswer->data;
        $pendingNotice->status = NoticeConversationStatus::TIME_WAITING;
        $pendingNotice->save();

        $this->telegram->sendMessage([
            'chat_id' => $pendingNotice->chat_id,
            'parse_mode' => 'HTML',
            'text' => "$user->first_name, " . AddNoticeMsg::SET_TIME_MSG
        ]);
    }

    private function handleTimeWaiting(PendingNotice $pendingNotice, Message $message): void
    {
        $user = $pendingNotice->user;

        $validatedAnswer = UserAnswerValidation::isTimeValid($message->text);

        if (! $validatedAnswer->valid) {
            $this->telegram->sendMessage([
                'chat_id' => $pendingNotice->chat_id,
                'parse_mode' => 'HTML',
                'text' => "$user->first_name, $validatedAnswer->errorMsg"
            ]);

            return;
        }

        $pendingNotice->time = $validatedAnswer->data;
        $pendingNotice->status = NoticeConversationStatus::TEXT_WAITING;
        $pendingNotice->save();

        $this->telegram->sendMessage([
            'chat_id' => $pendingNotice->chat_id,
            'parse_mode' => 'HTML',
            'text' => "$user->first_name, " . AddNoticeMsg::SET_TEXT_MSG,
        ]);
    }

    private function handleTextWaiting(PendingNotice $pendingNotice, Message $message): void
    {
        $user = $pendingNotice->user;

        $data = [
            'chat_id' => $pendingNotice->chat_id,
            'user_id' => $pendingNotice->user_id,
            'day_of_week' => $pendingNotice->day_of_week,
            'time' => $pendingNotice->time,
            'text' => $message->text,
        ];

        $notice = Notice::create($data);

        $pendingNotice->delete();

        $this->telegram->sendMessage([
            'chat_id' => $notice->chat_id,
            'parse_mode' => 'HTML',
            'text' => "$user->first_name, " . AddNoticeMsg::CONFIRM_MSG . PHP_EOL .
                "в дни: " . implode(' ', json_decode($notice->day_of_week)) . PHP_EOL .
                "время: {$notice->time}"
        ]);
    }
}

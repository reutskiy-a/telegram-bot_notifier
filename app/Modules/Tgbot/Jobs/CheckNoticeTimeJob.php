<?php

namespace App\Modules\Tgbot\Jobs;

use App\Modules\Tgbot\Models\Chat;
use App\Modules\Tgbot\Models\Notice;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Telegram\Bot\Api;

class CheckNoticeTimeJob implements ShouldQueue
{
    use Queueable;

    public int $tries = 5;
    public int $timeout = 60;
    public int|array $backoff = 2;

    protected Chat $chat;

    /**
     * Create a new job instance.
     */
    public function __construct(Chat $chat)
    {
        $this->chat = $chat;
    }

    /**
     * Execute the job.
     */
    public function handle(Api $api): void
    {
        $notices = Notice::getForCurrentDayAndTimeByChat($this->chat);

        foreach ($notices as $notice) {
            $api->sendMessage([
                'chat_id' => $notice->chat_id,
                'text' => $notice->text
            ]);
        }

    }
}

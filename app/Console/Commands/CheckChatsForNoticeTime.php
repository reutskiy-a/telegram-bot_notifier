<?php

namespace App\Console\Commands;

use App\Modules\Tgbot\Jobs\CheckNoticeTimeJob;
use App\Modules\Tgbot\Models\Chat;
use Illuminate\Console\Command;


class CheckChatsForNoticeTime extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'telegram:check-chats-for-notice-time';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $chats = Chat::all();

        foreach ($chats as $chat) {
            CheckNoticeTimeJob::dispatch($chat);
        }
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Controllers;

use App\Modules\Tgbot\Conversations\AddNoticeConversation;
use App\Modules\Tgbot\Handlers\NewChatMemberHandler;
use App\Modules\Tgbot\Utils\TgHelper;
use Illuminate\Http\Request;


class TgController extends BaseController
{
    public function index()
    {
        echo '<br><br>done';
    }

    public function install()
    {
        echo '<pre>';
        var_dump($this->telegram->deleteWebhook());
        var_dump($this->telegram->setWebhook(['url' => config('app.tg_bot_webhook_url')]));
        echo '</pre>';
    }

    public function handle(Request $request)
    {
        $update = $this->telegram->getWebhookUpdate();


        if ($update->has('message')) {
            if (TgHelper::isBotCommand($update->getMessage())) {
                $this->telegram->commandsHandler(true);
            }

            if (! TgHelper::isBotCommand($update->getMessage())) {
                $addNoticeConversation = new AddNoticeConversation($this->telegram);
                $addNoticeConversation->handle($update);
            }
        }


        if ($update->has('my_chat_member')) {
            $newChatMemberHandler = new NewChatMemberHandler();
            $newChatMemberHandler->handle($update);
        }


        return response([], 204);
    }


}

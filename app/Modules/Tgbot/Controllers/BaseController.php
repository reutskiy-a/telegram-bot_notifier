<?php

namespace App\Modules\Tgbot\Controllers;

use App\Modules\Tgbot\Commands\AddNoticeCommand;
use App\Modules\Tgbot\Commands\CancelCommand;
use App\Modules\Tgbot\Commands\DeleteNoticeCommand;
use App\Modules\Tgbot\Commands\GetNoticeCommand;
use App\Modules\Tgbot\Commands\GetTimezoneCommand;
use App\Modules\Tgbot\Commands\HelpCommand;
use App\Modules\Tgbot\Commands\ListNoticeCommand;
use App\Modules\Tgbot\Commands\SetTimezoneCommand;
use App\Modules\Tgbot\Commands\StartCommand;
use Illuminate\Routing\Controller;
use Monolog\Formatter\LineFormatter;
use Monolog\Handler\RotatingFileHandler;
use Monolog\Logger;
use Telegram\Bot\Api;

abstract class BaseController extends Controller
{

    protected Api $telegram;
    protected Logger $logger;


    public function __construct(Api $api)
    {
        $this->telegram = $api;

        $this->telegram->addCommands([
            StartCommand::class,
            HelpCommand::class,
            CancelCommand::class,
            SetTimezoneCommand::class,
            GetTimezoneCommand::class,
            AddNoticeCommand::class,
            ListNoticeCommand::class,
            GetNoticeCommand::class,
            DeleteNoticeCommand::class,
        ]);


        $this->logger = new Logger('tg_bot');
        $handler = new RotatingFileHandler(
            storage_path('/logs/tg_bot.log'),
            5,
            Logger::DEBUG
        );

        $formatter = new LineFormatter(
            "[%datetime%] %level_name%: %message% %context%\n",
            'Y-m-d H:i:s',
            true
        );
        $formatter->setJsonPrettyPrint(true);
        $handler->setFormatter($formatter);
        $this->logger->pushHandler($handler);
    }
}

<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Conversations\Constants;

class AddNoticeMsg
{
    public const SET_DAY_OF_WEEK_MSG = "в какие дни отправлять заметку?" . PHP_EOL .
                                        "укажите дни недели цифрой от 0 до 6 в любом порядке, где" . PHP_EOL .
                                        "0 - воскресенье," . PHP_EOL .
                                        "1 - понедельник," . PHP_EOL .
                                        "2 - вторник и т.д.";
    public const SET_TIME_MSG = 'укажите время в формате ЧЧ:ММ. Например: 10:35';
    public const SET_TEXT_MSG = 'напишите вашу заметку';
    public const CONFIRM_MSG = 'заметка добавлена';
}

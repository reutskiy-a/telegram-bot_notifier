<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Enums;

enum NoticeConversationStatus: string
{
    case DAY_OF_WEEK_WAITING = 'dayOfWeekWaiting';
    case TIME_WAITING = 'timeWaiting';
    case TEXT_WAITING = 'textWaiting';
}

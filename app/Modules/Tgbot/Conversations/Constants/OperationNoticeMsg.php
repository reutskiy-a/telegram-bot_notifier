<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Conversations\Constants;

class OperationNoticeMsg
{
    public const NOTICE_ID_ERROR = 'заметки с таким id не существует';
    public const GET_NOTICE_ID_EMPTY = 'вы не указали id. Например /get_notice#12';
    public const DELETE_NOTICE_ID_EMPTY = 'вы не указали id. Например /delete#12';
    public const NOTICE_OFFSET_ERROR = 'смещение больше общего количества заметок';
}

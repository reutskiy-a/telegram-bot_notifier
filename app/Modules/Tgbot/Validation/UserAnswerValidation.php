<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Validation;

use App\Modules\Tgbot\Conversations\Constants\AddNoticeMsg;
use App\Modules\Tgbot\Validation\Dto\ValidatedAnswer;

class UserAnswerValidation
{
    public static function isDaysOfWeekValid(string $answer): ValidatedAnswer
    {
        $dayNums = preg_replace('/[^0-9]/', '', $answer);

        if (empty($dayNums)) {
            return new ValidatedAnswer(false, [], AddNoticeMsg::SET_DAY_OF_WEEK_MSG);
        }

        $dayNums = mb_str_split($dayNums);
        $dayNums = array_unique($dayNums);
        $dayNums = array_values($dayNums);

        foreach ($dayNums as $dayNum) {
            if ($dayNum < 0 || $dayNum > 6) {
                return new ValidatedAnswer(false, null,
                    AddNoticeMsg::SET_DAY_OF_WEEK_MSG);
            }
        }

        return new ValidatedAnswer(true, $dayNums);
    }

    public static function isTimeValid(string $time): ValidatedAnswer
    {
        $result = (bool) (preg_match('/^([0-1][0-9]|2[0-3]):[0-5][0-9]$/', $time));

        if (false === $result) {
            return new ValidatedAnswer(false, null, AddNoticeMsg::SET_TIME_MSG);
        }

        return new ValidatedAnswer(true, $time);
    }
}

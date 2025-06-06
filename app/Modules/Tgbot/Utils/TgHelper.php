<?php

declare(strict_types=1);

namespace App\Modules\Tgbot\Utils;

use Carbon\Carbon;
use Telegram\Bot\Objects\Message;
use Telegram\Bot\Objects\Update;

class TgHelper
{
    private const STATUS_KICKED = 'kicked';
    private const STATUS_LEFT = 'left';
    private const STATUS_MEMBER = 'member';

    public static function didTheyKickedBot(Update $update, string|int $botId): bool
    {
        $botId = (int)$botId;
        $userId = $update->get('my_chat_member')->new_chat_member->user->id;
        $userStatus = $update->get('my_chat_member')->new_chat_member->status;

        if ($userId === $botId && $userStatus === self::STATUS_KICKED || $userStatus === self::STATUS_LEFT) {
            return true;
        }

        return false;
    }

    public static function didTheyAddBot(Update $update, string|int $botId)
    {
        $botId = (int)$botId;
        $userId = $update->get('my_chat_member')->new_chat_member->user->id;
        $userStatus = $update->get('my_chat_member')->new_chat_member->status;

        if ($userId === $botId && $userStatus === self::STATUS_MEMBER) {
            return true;
        }

        return false;
    }

    public static function isBotCommand(Message $message): bool
    {
        $entities = $message->entities ?? null;

        if (null === $entities) {
            return false;
        }

        foreach ($entities as $entity) {
            $type = $entity->type ?? null;

            if ($type === 'bot_command') {
                return true;
            }
        }

        return false;
    }

    public static function getUtcTimezoneFromUserCommand(string $text): ?string
    {
        if (!str_contains($text, '#')) {
            return null;
        }

        $value = explode('#', $text, 2)[1] ?? null;

        if (null === $value) {
            return null;
        }

        try {
            $timezone = new \DateTimeZone($value);
            $offset = $timezone->getOffset(new \DateTime('now', new \DateTimeZone('UTC')));
            $hours = floor(abs($offset) / 3600);
            $minutes = (abs($offset) % 3600) / 60;

            if ($offset >= -12 * 3600 && $offset <= 14 * 3600 && in_array($minutes, [0, 30])) {
                return Carbon::createFromTimestampUTC(0)->setTimezone($value)->format('P');
            }
        } catch (\Exception) {
            return null;
        }

        return null;
    }
}

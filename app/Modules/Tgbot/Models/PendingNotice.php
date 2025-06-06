<?php

namespace App\Modules\Tgbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PendingNotice extends Model
{
    protected $table = 'pending_notices';
    protected $guarded = false;

    public function user(): HasOne
    {
        return $this->hasOne(TgUser::class, 'id', 'user_id');
    }

    public static function getByChatIdAndUserId(int|string $chatId, int|string $userId): ?PendingNotice
    {
        return PendingNotice::where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->first();
    }

    public static function deleteByChatIdAndUserId(int|string $chatId, int|string $userId): bool
    {
        return PendingNotice::where('chat_id', $chatId)
            ->where('user_id', $userId)
            ->delete();
    }
}

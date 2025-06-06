<?php

namespace App\Modules\Tgbot\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chat extends Model
{
    use SoftDeletes;

    protected $table = 'chats';
    protected $guarded = false;
    protected $keyType = 'string';
    public $incrementing = false;

    protected static function boot()
    {
        parent::boot();

        static::deleting(function($chat) {
            $chat->notices()->delete();
        });

        static::restoring(function($chat) {
            $chat->notices()->onlyTrashed()->restore();
        });

        parent::boot();
    }

    public function notices(): HasMany
    {
        return $this->hasMany(Notice::class, 'chat_id', 'id');
    }
}

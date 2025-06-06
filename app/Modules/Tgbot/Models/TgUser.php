<?php

namespace App\Modules\Tgbot\Models;


use Illuminate\Database\Eloquent\Model;

class TgUser extends Model
{
    protected $table = 'tg_users';
    protected $guarded = false;
    public $incrementing = false;
    protected $fillable = [
        'id',
        'first_name',
        'last_name',
        'username',
        'language_code',
        'is_bot',
        'role',
    ];
}

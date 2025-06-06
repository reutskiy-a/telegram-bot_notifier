<?php

namespace App\Modules\Tgbot\Models;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\MySqlConnection;
use Illuminate\Database\PostgresConnection;
use Illuminate\Database\SQLiteConnection;
use Illuminate\Support\Facades\DB;

class Notice extends Model
{
    use SoftDeletes;

    protected $table = 'notices';
    protected $guarded = false;
    protected $with = ['chat'];

    public function chat()
    {
        return $this->belongsTo(Chat::class, 'chat_id', 'id');
    }

    public static function getForCurrentDayAndTimeByChat(Chat $chat): Collection
    {
        $date = now()->setTimezone($chat->timezone);

        if (DB::connection() instanceof SQLiteConnection) {

            return Notice::where('time', $date->rawFormat('H:i'))
                ->where('chat_id', $chat->id)
                ->whereRaw('EXISTS (SELECT * FROM json_each(day_of_week) WHERE CAST(value AS INTEGER) = ?)',
                    [$date->dayOfWeek])
                ->get();
        }

        if (DB::connection() instanceof PostgresConnection) {
            return Notice::where('time', $date->rawFormat('H:i'))
                ->where('chat_id', $chat->id)
                ->whereRaw('? = ANY(day_of_week)', [$date->dayOfWeek])
                ->get();
        }

        if (DB::connection() instanceof MySqlConnection) {
            return Notice::where('time', $date->rawFormat('H:i'))
                ->where('chat_id', $chat->id)
                ->whereRaw('JSON_CONTAINS(day_of_week, ?)', [$date->dayOfWeek])
                ->get();
        }

        throw new Exception('database type not defined');
    }

}

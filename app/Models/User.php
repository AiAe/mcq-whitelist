<?php

namespace App\Models;

use App\Utils\Rcon;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Http;

class User extends Authenticatable
{
    protected $fillable = [
        'quaver_user_id',
        'username',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function whitelistPlayer($remove = false)
    {
        $rcon = new Rcon(config('app.rcon_host'), config('app.rcon_port'), config('app.rcon_password'), 3);

        if ($rcon->connect())
        {
            $method = $remove ? "remove" : "add";

            $rcon->sendCommand(sprintf("whitelist %s %s", $method, $this->username));
        }
    }

    public static function whitelistList()
    {
        $rcon = new Rcon(config('app.rcon_host'), config('app.rcon_port'), config('app.rcon_password'), 3);

        if ($rcon->connect())
        {
            $list = $rcon->sendCommand("whitelist list");

            $players = explode(":", $list)[1];
            $players = explode(",", $players);

            return array_map(function ($item) {
                return trim(mb_strtolower($item));
            }, $players);
        }
    }

    public static function isDonator($user_groups)
    {
        return (bool)(($user_groups & (1 << 8)));
    }

    public static function quaverUser($user_id)
    {
        $response = Http::get(sprintf("https://api.quavergame.com/v1/users?id=%s", $user_id))->json();
        // Get first user from API
        return head($response['users']);
    }
}

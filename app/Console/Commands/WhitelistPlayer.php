<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class WhitelistPlayer extends Command
{
    protected $signature = 'app:whitelist-player {player} {--remove}';

    protected $description = 'Adds or removes player to whitelist';

    public function handle()
    {
        $user = User::where('username', $this->argument('player'))->firstOrFail();
        $user->whitelistPlayer(($this->option('remove')));
    }
}

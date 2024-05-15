<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class VerifyWhitelist extends Command
{
    protected $signature = 'app:verify-whitelist';

    protected $description = 'Syncs the database with server and deletes old players';

    public function handle()
    {
        User::whereNotIn('username', User::whitelistList())->each(fn ($user) => $user->delete());
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DonatorCheck extends Command
{
    protected $signature = 'app:donator-check {player?}';

    protected $description = 'Removes access to players who are not donators.';


    public function handle()
    {
        $users = User::query();

        if($this->argument('player')) {
            $users->where('username', $this->argument('player'));
        }

        foreach ($users->get() as $user) {
            $api_user = User::quaverUser($user->quaver_user_id);
            $user->is_donator = (User::isDonator($api_user['usergroups']));
            $user->save();
            $user->whitelistPlayer(!$user->is_donator);
        }
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke() {
        return view('welcome');
    }

    public function post(Request $request)
    {
        $username = $request->get('username');
        $check = $request->get('donator');

        if($username) $this->handle_username($username);
        if($check) $this->handle_donator($username);

        return back();
    }

    private function handle_username($username)
    {
        if(preg_match('/^[a-zA-Z0-9_]{2,16}$/m', $username)) {
            $user = Auth::user();
            $user->username = mb_strtolower($username);
            $user->save();
            $user->whitelistPlayer();
        }
    }

    private function handle_donator($username)
    {
        Artisan::call('app:donator-check', [
            'player' => $username,
        ]);
    }
}

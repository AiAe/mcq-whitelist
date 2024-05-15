<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function __invoke() {
        $users = null;

        if(Auth::user() && Auth::user()->quaver_user_id == config("app.quaver_user_id_owner")) {
            $users = User::query()->get();
        }

        return view('welcome', compact('users'));
    }

    public function post(Request $request)
    {
        $username = $request->get('username');
        $check = $request->get('donator');
        $reset_mc_name = $request->get('reset_mc_name');
        $delete_user = $request->get('delete_user');

        if($username) $this->handle_username($username);
        if($check) $this->handle_donator($username);
        if($reset_mc_name) $this->handle_reset();
        if($delete_user) $this->handle_delete();

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

    private function handle_reset()
    {
        $user_id = \request()->get('user_id');
        $user = User::where('id', $user_id)->firstOrFail();

        $user->whitelistPlayer(true);
        $user->username = null;
        $user->save();

        return "done";
    }

    private function handle_delete()
    {
        $user_id = \request()->get('user_id');
        $user = User::where('id', $user_id)->firstOrFail();

        $user->whitelistPlayer(true);
        $user->delete();

        return "done";
    }
}

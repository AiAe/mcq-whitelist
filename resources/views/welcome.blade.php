@guest
    <a href="https://quavergame.com/oauth2/authorize?client_id=f6ec940703636f33aac0a11562983579&redirect_uri=http://minequa.test/oauth/quaver/callback">Login</a>
@endguest

@auth
    <div>
        Welcome, {{ Auth::user()->username??"Unknown" }}
        @if(Auth::user()->username && Auth::user()->is_donator)
            <div>IP <code>mc.aiae.dev</code></div>
            <div>Minecraft 1.20.4</div>
            <small>If you have new Minecraft username or issues, please contact AiAe!</small>
            <div>
                <h2>Rules</h2>
                <ol>
                    <li>No griefing</li>
                    <li>No cheats</li>
                    <li>Just have fun :)</li>
                </ol>
            </div>
        @else
            <form method="POST" action="/">
                @csrf
                @if(Auth::user()->username == null)
                    <div>For you to be able to join the server, please enter your name below, so you can be added to the
                        whitelist!</div>
                    <label>Minecraft Username</label>
                    <input type="text" name="username" pattern="^[a-zA-Z0-9_]{2,16}$">
                    <input type="submit" value="Whitelist me!">
                @elseif(!Auth::user()->is_donator)
                    <div>You are currently not Quaver Donator, you wont be able to play.</div>
                    <input type="submit" name="donator" value="I am donator!">
                @endif
            </form>
        @endif
    </div>
@endauth

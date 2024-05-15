@guest
    <a href="{{ config('app.quaver_auth_url') }}">Login</a>
@endguest

@auth
    <div>
        Welcome, {{ Auth::user()->username??"Unknown" }}
        @if(Auth::user()->username && Auth::user()->is_donator)
            <div>IP <code>{{ config('app.server_ip') }}</code></div>
            <div>{{ config('app.server_name') }}</div>
            <small>If you have new Minecraft username or issues, please contact site owner!</small>
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
                        whitelist!
                    </div>
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

    @if(Auth::user()->quaver_user_id == config("app.quaver_user_id_owner"))
        <hr>
        <div><h1>Manage whitelist</h1></div>
        <div>
            <table border="1">
                <thead>
                <th>Quaver Username</th>
                <th>Minecraft Username</th>
                <th>Donator</th>
                <th>Actions</th>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->quaver_username }}</td>
                        <td>{{ $user->username }}</td>
                        <td>{{ $user->is_donator ? 'Yes' : 'No' }}</td>
                        <td>
                            <button type="button" id="reset_mc_name" onclick="formSubmit(this, '{{ $user->id }}')">Reset MC Name</button>
                            <button type="button" id="delete_user" onclick="formSubmit(this, '{{ $user->id }}')">Delete</button>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        <script>
            function formSubmit(btn, userId) {
                btn.setAttribute('disabled', 'disabled');
                const dataForm = new FormData();
                dataForm.append(btn.getAttribute('id'), btn.getAttribute('id'));
                dataForm.append('user_id', userId);

                fetch("{{ route('home') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-Token': "{{ csrf_token() }}"
                    },
                    body: dataForm
                }).then(data => {
                    location.reload();
                }).catch(error => {
                    location.reload();
                });
            }
        </script>
    @endif
@endauth



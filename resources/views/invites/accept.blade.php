<form action="{{ route('invites.complete', $invite->token) }}" method="post">
    {{ csrf_field() }}
    <label for="email">Email Address</label>
    <input type="email" name="email" value={{$invite->email}} />
    <label for="name">Name</label>
    <input type="text" name="name" />
    <label for="Password">Password</label>
    <input type="password" name="password" />
    <label for="password_confirm">Password Confirm</label>
    <input type="password" name="password_confirm" />

    <button type="submit">Accept Invitation</button>
</form>
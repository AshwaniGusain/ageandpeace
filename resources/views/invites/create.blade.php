// make use of the named route so that if the URL ever changes,
// the form will not break #winning
<form action="{{ route('invites.store') }}" method="post">
{{ csrf_field() }}
    <input type="email" name="email" />
    <select id="role" name="role">
        <option value="">Select a Role</option>
        @foreach($roles as $role)
            <option value="{{$role->name}}">{{ucfirst($role->name)}}</option>
        @endforeach
    </select>

    <button type="submit">Send invite</button>
</form>
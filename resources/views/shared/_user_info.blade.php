<a href="{{ route('users.show',$user->id) }}">
    <img src="{{ $user->gravatar('60') }}" alt="{{ $user->name }}" class="gravatar">
</a>
<h2>{{ $user->name }}</h2>
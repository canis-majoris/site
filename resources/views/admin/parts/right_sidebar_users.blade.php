@foreach($active_admins as $a_admin)
    <div class="info-item message-item">
        @if(!$a_admin->avatar)
            <img src="{{URL::asset('/img/site/default_avatar.jpg')}}" class="circle" alt="{{ $a_admin->username }}">
        @else
            <img src="{{url('/img/admins/avatars').'/'.$a_admin->avatar}}" class="circle" alt="{{ $a_admin->username }}">
        @endif
        <div class="message-info">
            <div class="message-author">{{ $a_admin->username }}</div>
            <small>{{ \Carbon\Carbon::parse($a_admin->last_login)->diffForHumans() }}</small>
        </div>
    </div>
@endforeach
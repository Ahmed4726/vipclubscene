<div>
    <a href="{{ route('notifications.index') }}"><i class="fas fa-bullhorn"></i>
        @lang('navigation.myNotifications') 
        <span class="notifc">{{ auth()->user()->unreadNotifications()->count() }}</span>
    </a>
</div>

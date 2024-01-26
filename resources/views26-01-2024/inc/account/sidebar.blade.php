<ul class="list-group">
    <a class="list-group-item  {{ isPageActive('account', 2) }}" href="{{ route('myaccount') }}">
        My Account
    </a>
    <a class="list-group-item  {{ isPageActive('change-password', 1) }}" href="{{ route('auth.password.change') }}">
        Change Password
    </a>
    <a class="list-group-item {{ isPageActive('tips', 2) }}" href="{{ route('tips') }}">
        Tips
    </a>
</ul>
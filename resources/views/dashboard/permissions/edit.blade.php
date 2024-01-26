<div class="row">
    <div class="col-md-12">
        @include('messages.message')
        <form id="user-update-form" action="{{ route('user.update', $user->id) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="profession">Position</label>
                <select name="profession_type" id="profession"
                        class="form-control select ">
                    <option value="">--Select--</option>
                    @foreach(config('profession_types') as $profession)
                        <option
                                {{ $selected = old('profession_type', $user->profession_type) == $profession->id ? "selected" : "" }}
                                value="{{ $profession->id }}">{{ $profession->profession_name }} </option>
                    @endforeach
                </select>
                @error('profession_type')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" value="{{ old('name', $user->name) }}" id="name" type="text"
                       class="form-control @error('name') {{ errCls() }} @enderror" placeholder="Enter name">
                @error('name')
                {!! errMsg($message) !!}
                @enderror

            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input name="email" value="{{ old('email', $user->email) }}" id="email" type="text"
                       class="form-control @error('email') {{ errCls() }} @enderror"
                       placeholder="Enter email">
                @error('email')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input name="username" value="{{ old('username', $user->username) }}" id="username" type="text"
                       class="form-control @error('username') {{ errCls() }} @enderror"
                       placeholder="Enter username">
                @error('username')
                {!! errMsg($message) !!}
                @enderror
            </div>

            <div class="form-group">
                <label for="is_active">Status</label>
                <select name="is_active" id="is_active" class="form-control select">
                    @foreach(config('user_status') as $key => $value)
                        <option {{ $selected = (old("is_active", $user->is_active) == $key) ? "selected" : "" }} value="{{ $key }}">{{ $value }}</option>
                    @endforeach
                </select>
                @error('is_active')
                {!! errMsg($message) !!}
                @enderror
            </div>

            <div class="panel-group accordion">
                <div class="panel panel-primary">
                    <div class="panel-heading">
                        <h4 class="panel-title">
                            <a href="#accOneColOne">
                                Click to update password
                            </a>
                        </h4>
                    </div>
                    <div class="panel-body @if(!empty( old('password') ) || !empty( old('password_confirmation') )) {{ "panel-body-open" }} @endif"
                         id="accOneColOne">
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input autocomplete="chrome-off" name="password" value="{{ old('password') }}" id="password"
                                   type="password"
                                   class="form-control @error('password') {{ errCls('password') }} @enderror"
                                   placeholder="Enter password">
                            @error('password')
                            {!! errMsg($message) !!}
                            @enderror
                        </div>
                        <div class="form-group">
                            <label for="confirm-password">Confirm Password</label>
                            <input id="confirm-password" name="confirm_password"
                                   value="{{ old('password_confirmation') }}" id="confirm-password" type="password"
                                   class="form-control @error('password_confirmation') {{ errCls('password') }} @enderror"
                                   placeholder="Confirm password">
                            @error('password_confirmation')
                            {!! errMsg($message) !!}
                            @enderror
                        </div>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label for="user_type">Role</label>
                <select id="user_type" class="select form-control" name="role">
                    <option value="">--Select Role--</option>
                    @foreach(config('roles') as $role)
                        <option {{ $selected = (old('role', $user->role) == $role) ? "selected" : ""}} value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                @error('role')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update Team Member</button>
            </div>
    </div>
    </form>
</div>
</div>
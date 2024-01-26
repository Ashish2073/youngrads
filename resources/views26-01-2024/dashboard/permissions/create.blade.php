<div class="row">
    <div class="col-md-12">
        @include('messages.message')
        <form id="user-create-form" action="{{ route('user.store') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="profession">Position</label>
                <select name="profession_type" id="profession"
                        class="form-control select ">
                    <option value="">--Select--</option>
                    @foreach(config('profession_types') as $profession)
                        <option {{ $selected = (old('profession_type') == $profession->id) ? "selected" : "" }} value="{{ $profession->id }}">{{ $profession->profession_name }}</option>
                    @endforeach
                </select>
                @error('profession_type')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <label for="name">Name</label>
                <input name="name" value="{{ old('name') }}" id="name" type="text"
                       class="form-control @error('name') {{ errCls() }} @enderror" placeholder="Enter name">
                @error('name')
                {!! errMsg($message) !!}
                @enderror

            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input name="email" value="{{ old('email') }}" id="email" type="text"
                       class="form-control @error('email') {{ errCls() }} @enderror"
                       placeholder="Enter email">
                @error('email')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <label for="username">Username</label>
                <input name="username" value="{{ old('username') }}" id="username" type="text"
                       class="form-control @error('username') {{ errCls() }} @enderror"
                       placeholder="Enter username">
                @error('username')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <label for="password">Password</label>
                <input name="password" value="{{ old('password') }}" id="password" type="password"
                       class="form-control @error('password') {{ errCls() }} @enderror"
                       placeholder="Enter password">
                @error('password')
                {!! errMsg($message) !!}
                @enderror
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <input id="confirm-password" name="password_confirmation" value=""
                       id="confirm-password" type="password"
                       class="form-control "
                       placeholder="Confirm password">
            </div>

            <div class="form-group">
                <label for="user_type">Role</label>
                <select id="user_type" class="select form-control" name="role">
                    <option value="">--Select Role--</option>
                    @foreach(config('roles') as $role)
                        <option {{ $selected = (old('role') == $role) ? "selected" : ""}} value="{{ $role }}">{{ ucfirst($role) }}</option>
                    @endforeach
                </select>
                @error('role')
                {!! errMsg($message) !!}
                @enderror
            </div>
            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Add Team Member</button>
            </div>
    </div>
    </form>
</div>
</div>
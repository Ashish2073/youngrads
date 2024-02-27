@if (session('permissionerror'))
    <div class="alert alert-danger mt-2 py-2" role="alert" style="font-size: 20px">
        <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span
                aria-hidden="true">&times;</span></button>
        <strong>Fail!</strong> {{ session('permissionerror') }}
    </div>
@endif


<form action="{{ route('email-change', $user->id) }}" method="post" id="change-email">
  @csrf
  @include('dashboard.common.fields.text', [
    'label_name' => 'Email Address',
    'id' => 'email',
    'name' => 'email',
    'placeholder' => 'Enter Email Address',
    'input_attribute' => [
        'type' => 'email',
        'value' => old('email'),
        'required' => 'required'
    ],
    'classes' => '',
  ])
  @include('dashboard.common.fields.text', [
    'label_name' => 'Confirm Email Address',
    'id' => 'confirm-email',
    'name' => 'confirm_email',
    'placeholder' => 'Enter Confirm Email Address',
    'input_attribute' => [
        'type' => 'email',
        'value' => old('confirm_email'),
        'required' => 'required'

    ],
    'classes' => '',
])

@if($user->new_email != Null)
  <div class="alert alert-warning alert-dismissible mb-2" role="alert">
    <button type="button" class="close d-none" data-dismiss="alert" aria-label="Close">
      <span aria-hidden="true">×</span>
    </button>
    <p class="mb-0">
      Your email verification is pending for <u>{!! $user->new_email !!}</u>
    </p>
  </div>
@endif
<button type="submit" class="btn btn-primary" id="change-email-btn">Change Email</button>
</form>

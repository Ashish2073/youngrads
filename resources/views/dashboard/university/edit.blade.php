<div class="row justify-content-center">
    <div class="col-md-6">





        @include('dashboard.inc.message')
        <form id="university-edit-form" action="{{ route('admin.university.update', $university->id) }}" method="post">
            @csrf
            @method('PUT')
            @include('dashboard.common.fields.text', [
                'label_name' => 'University',
                'id' => 'university',
                'name' => 'university',
                'placeholder' => 'Enter University Name',
                'input_attribute' => [
                    'type' => 'text',
                    'value' => old('university', $university->name),
                ],
                'classes' => '',
            ])

            <div class="form-group">
                <button type="submit" id="submit-btn" class="btn btn-primary">Update</button>
            </div>
        </form>
        <div class="form-group delete " style="margin-top:1%">
            @if ($university->campus->count() > 0)
                <p>{{ $university->name }} is related to another record(s).</p>
                @php session()->put('used_university',[$university->id]); @endphp
                <a href="{{ url('admin/campus') }}">
                    <p> click Here to Show Uses</p><a>
                    @else
                        <form id="delete-form" method="POST"
                            action="{{ route('admin.university.destroy', $university->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit" id="submit-btn-delete" class="btn btn-danger">Delete</button>
                        </form>
            @endif
        </div>
    </div>
</div>
</div>

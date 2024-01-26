@extends('layouts/contentLayoutMaster')

@section('title', $campus->name)
@section('vendor-style')
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
@endsection

@section('content')
    <section class="full-editor">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">About Us</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="editor" id="about-us">
                                {!! $campus->about_us !!}
                            </div>
                            <p class="text-danger ml-2 mb-2 error-msg"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Features</h4>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body">
                            <div class="editor" id="feature">
                                {!! $campus->feature !!}
                            </div>
                            <p class="text-danger ml-2 mb-2 error-msg"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <button class="btn btn-primary float-right" id="save">Save</button>
            </div>
        </div>
    </section>

@endsection

@section('vendor-script')
    <!-- vendor files -->
    <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/extensions/jquery.steps.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
    <!-- Page js files -->
    {{-- <script src="{{ asset(mix('js/scripts/editors/editor-quill.js')) }}"></script> --}}
    <script>
        $(document).ready(function() {

            let aboutUs = new Quill('#about-us', {
                modules: {
                    'formula': true,
                    'syntax': true,
                    'toolbar': [
                        [{
                            'font': []
                        }, {
                            'size': []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'script': 'super'
                        }, {
                            'script': 'sub'
                        }],
                        [{
                            'header': '1'
                        }, {
                            'header': '2'
                        }, 'blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }, {
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        ['direction', {
                            'align': []
                        }],
                        ['clean']
                    ],
                },
                theme: 'snow'
            });
            let feature = new Quill('#feature', {
                modules: {
                    'formula': true,
                    'syntax': true,
                    'toolbar': [
                        [{
                            'font': []
                        }, {
                            'size': []
                        }],
                        ['bold', 'italic', 'underline', 'strike'],
                        [{
                            'color': []
                        }, {
                            'background': []
                        }],
                        [{
                            'script': 'super'
                        }, {
                            'script': 'sub'
                        }],
                        [{
                            'header': '1'
                        }, {
                            'header': '2'
                        }, 'blockquote', 'code-block'],
                        [{
                            'list': 'ordered'
                        }, {
                            'list': 'bullet'
                        }, {
                            'indent': '-1'
                        }, {
                            'indent': '+1'
                        }],
                        ['direction', {
                            'align': []
                        }],
                        ['clean']
                    ],
                },
                theme: 'snow'
            });

            $('#save').on('click', function() {

                //  aboutUs = aboutUs.root.innerHTML;
                //  feature = feature.root.innerHTML;
                validateEditor(aboutUs);
                validateEditor(feature);


                if (validateEditor(aboutUs) && validateEditor(feature)) {

                    $.ajax({
                        url: "{{ route('admin.save-details') }}",
                        type: 'POST',
                        data: {
                            _token: "{{ csrf_token() }}",
                            id: "{{ $id }}",
                            aboutUs: aboutUs.root.innerHTML,
                            feature: feature.root.innerHTML
                        },
                        beforeSend: function() {
                            submitLoader("#save");
                        },
                        success: (data) => {
                            setAlert(data);
                            submitReset("#save", "Save")
                        }

                    });
                }

            })

        });

        function validateEditor(el) {
            html = el.root.innerHTML;
            if (html.length > 11) {
                el.container.parentNode.children[2].innerText = " ";
                return true;
            } else {
                el.container.parentNode.children[2].innerText = "Please add content";
                return false;
            }
        }
    </script>
@endsection

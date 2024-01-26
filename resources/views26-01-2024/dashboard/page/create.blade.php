@extends('layouts/contentLayoutMaster')

@section('title', 'Create Page')


@section('page-style')
    <!-- vendor css files -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.css" rel="stylesheet">
    <!-- vendor css files -->
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/katex.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/monokai-sublime.min.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.snow.css')) }}">
    <link rel="stylesheet" href="{{ asset(mix('vendors/css/editors/quill/quill.bubble.css')) }}">
    <style>
        .note-editor {
            background: white !important;
        }
    </style>
@endsection

@section('content')

    <div class="card">

        <div class="card-content">
            <div class="card-body">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <form class="form form-vertical" id="page-create-form" action="{{ route('admin.page.store') }}"
                              method="post">
                            @csrf

                            @include('dashboard.common.fields.text', [
                                'label_name' => 'Title',
                                'id' => 'title',
                                'name' => 'title',
                                'placeholder' => 'Enter Page Title',
                                'input_attribute' => [
                                    'type' => 'text',
                                    'value' => old('title'),
                                ],
                                'classes' => '',
                            ])

                            @include('dashboard.common.fields.text', [
                                'label_name' => 'URL',
                                'id' => 'url',
                                'name' => 'url',
                                'placeholder' => 'Enter Page url',
                                'input_attribute' => [
                                    'type' => 'text',
                                    'value' => old('url'),
                                ],
                                'classes' => '',
                            ])

                            <button type="button" class="btn btn-sm btn-primary d-none" id="add-row">Add Meta Row
                            </button>
                            <div class="main-row d-none">
                                <div class="meta-row">
                                    @include('dashboard.common.fields.text', [
                                        'label_name' => 'Meta Key',
                                        'id' => 'meta_key',
                                        'name' => 'meta_key[]',
                                        'placeholder' => 'Enter Meta Key',
                                        'input_attribute' => [
                                            'type' => 'text',
                                        ],
                                        'classes' => '',
                                    ])


                                    @include('dashboard.common.fields.text', [
                                                    'label_name' => 'Meta Value',
                                                    'id' => 'meta_value',
                                                    'name' => 'meta_value[]',
                                                    'placeholder' => 'Enter Meta Value',
                                                    'input_attribute' => [
                                                        'type' => 'text',
                                                        // 'value' => old('meta_value'),
                                                    ],
                                                    'classes' => '',
                                    ])
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="content">Content:</label>
                                <div id="page-editer">{!! old('content') !!}</div>
                                <p class="text-danger"></p>
                                <textarea name="content" id="content" cols="30" rows="10" class="d-none"></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" id="submit-btn" class="btn btn-primary add-content">Add</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>



@endsection

@section('vendor-script')
    <!-- summernote script-->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote.min.js"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/katex.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/highlight.min.js')) }}"></script>
    <script src="{{ asset(mix('vendors/js/editors/quill/quill.min.js')) }}"></script>
@endsection

@section('page-script')

    <script>
        $(document).ready(function () {

          let pageEditor = new Quill('#page-editer', {
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


            validateForm($('#page-create-form'), {
                rules: {
                    title: {
                        required: true,
                    },
                    url: {
                        required: true,
                    },
                    // content: {
                    //     required: true,
                    // },
                    meta_key: {
                        required: true
                    },
                    meta_value: {
                        required: true
                    }
                },
                messages: {}
            });

            $('.add-content').on('click', function(){
                if(validateEditor(pageEditor)){
                  $("#content").val(pageEditor.root.innerHTML)
                }
            });

            function validateEditor(el){
              html = el.root.innerHTML;
               if(html.length > 11){
                  el.container.parentNode.children[3].innerText = " ";
                return true;
               }else{
                 el.container.parentNode.children[3].innerText = "Please add content";
                  return false;
               }
            }

            $("#create-form").on("submit", function (e) {

                $("#content").val(pageEditor.root.innerHTML);
                e.preventDefault();
                return false;
            })
            //add and remove meta row

            let html = `<div class="meta-row">`
            html += `<button class="close">×</button>`
            html += `<div class="form-group">`
            html += `<label for="meta_key">Meta Key</label>`
            html += `<input name="meta_key[]" id="meta_key" class=" form-control " placeholder="Enter Meta Key" type="text" value="">`
            html += ` </div>`
            html += `<div class="form-group">`
            html += `<label for="meta_value">Meta Value</label>`
            html += `<input name="meta_value[]" id="meta_value" class=" form-control " placeholder="Enter Meta  Value" type="text" value="">`
            html += `</div>`
            html += `</div>`

            $(document).on('click', '#add-row', () => {
                $('.main-row').append(html);
            });

            $(document).on("click", ".close", function () {
                $(this).parent('div').remove();
            })

        });

    </script>
@endsection

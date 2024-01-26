@inject('attachment', 'App\Http\Controllers\ApplicationMessageController')
<ul class="nav nav-tabs nav-fill" id="myTab" role="tablist">
    <li class="nav-item">
        <a class="nav-link active" id="home-tab-fill" data-toggle="tab" href="#chat" role="tab"
            aria-controls="home-fill" aria-selected="true">Chat</a>
    </li>
    <li class="nav-item">
        <a class="nav-link" id="profile-tab-fill" data-toggle="tab" href="#attachment" role="tab"
            aria-controls="profile-fill" aria-selected="false">Shared Attachment</a>
    </li>
</ul>
<div class="tab-content pt-1">
    <div class="tab-pane active" id="chat" role="tabpanel" aria-labelledby="home-tab-fill">
        <div class="chat-app-form px-0">
            <form id="message-form" method="POST" action="{{ route('applicaton-store', $id) }}"
                enctype="multipart/form-data">
                @csrf
                <div class="chat-app-input d-flex">
                    <input type="text" name="message"
                        class="form-control message mr-1  @error('message') {{ errCls() }} @enderror"
                        id="iconLeft4-1" placeholder="Type your message">
                    <button class="btn btn-icon btn-outline-primary attachment mr-1" type="button">
                        <i class="fa fa-paperclip " aria-hidden="true"></i>
                    </button>

                    <button type="submit" class="btn btn-icon btn-primary send" id="submit-btn"><i
                            class="fa fa-paper-plane-o"></i> </button>
                </div>
                <p class="text-left  mt-50" id="attachment-name"></p>
                <div class="form-group  mt-1 row pl-2 col-6 d-none">

                    <div class="custom-file">

                        <input type="file"
                            class="custom-file-input message-file-input @error('document') {{ errCls() }} @enderror"
                            name="document">
                        <label class="custom-file-label" for="inputGroupFile01">Choose file</label>

                    </div>
                </div>
                <div class="error-message">
                    @error('message')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>
                <input type="hidden" name=""><input type="hidden" name="auth_id" value="{{ $auth }}">
                <input type="hidden" name=""><input type="hidden" name="gaurd" value="{{ $gaurd }}">
                @error('document')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
            </form>
        </div>
        <div class="user-chats">
            <table id="chat-table" class="table" style="width:100%">
                <thead class="d-none">
                    <th>html</th>
                    <th>test</th>
                </thead>
                <tbody class="chats">

                </tbody>
            </table>
        </div>
    </div>
    <div class="tab-pane" id="attachment" role="tabpanel" aria-labelledby="profile-tab-fill">

        @include('application_message.attachment', ['attachments' => $attachment::sharedAttachment($id)])

    </div>
</div>

<script>
    var messageTable;

    function initMessageScript(id) {

        $(".attachment").unbind("click");
        $(".attachment").click(function() {
            $(".message-file-input").click();
        });

        $(".message-file-input").unbind("change");
        $(".message-file-input").change(function(e) {
            $('#attachment-name').text(e.target.files[0].name);
            $("#attachment-name").html("<span class='badge badge-primary badge-pill'>" + e.target.files[0]
                .name +
                "<button type='button' class='btn btn-icon p-25 text-white clear-attachment'><i class='fa fa-times'></i></button></span>"
            );
            let fileInput = $(this);
            $(".clear-attachment").unbind("click");
            $(".clear-attachment").click(function() {
                fileInput.val('');
                $("#attachment-name").html("");
            });
        });

        messgeTable = $("#chat-table").DataTable({
            "processing": true,
            "serverSide": true,
            ajax: {
                url: "{{ url('application/message/all') }}" + "/" + id,
                data: function(d) {

                }
            },
            dom: "tp",
            "order": [
                [1, "desc"]
            ],
            columns: [

                {
                    data: 'html'
                },
                {
                    data: 'time',
                    'visible': false
                }

            ],
            responsive: false,
            "language": {
                "emptyTable": "No messages yet!"
            },
            drawCallback: function(setting, data) {
                let setTime;
                clearTimeout(setTime);
                setTime = setTimeout(() => {
                    // messgeTable.draw('page');
                }, 10000);

            },

            aLengthMenu: [
                [10, 15, 20],
                [10, 15, 20]
            ],

            bInfo: false,
            pageLength: 7,
            initComplete: function(settings, json) {
                $(".dt-buttons .btn").removeClass("btn-secondary");
                $(".table-img").each(function() {
                    $(this).parent().addClass('product-img');
                });

                // setInterval(()=>{
                //   messgeTable.ajax.reload(null,false);
                //   },5000);
            }
        });


        validateForm($('#message-form'), {
            rules: {
                message: {
                    required: true,
                },
                document: {
                    extension: "png|jpg|docx|doc|pdf"
                }
            },
            errorPlacement: function(error, element) {
                if (element.hasClass('select')) {
                    element = element.next();
                }
                element = $(".error-message");
                error.insertAfter(element);
            },
            messages: {

            }
        });

        submitForm($('#message-form'), {
            beforeSubmit: function() {
                $("#submit-btn").attr('disabled', 'disabled');
                $("#submit-btn").html("<i class='fa fa-spin fa-spinner'></i>");
            },
            success: function(data) {
                messgeTable.draw();
                $('#message-form')[0].reset();
            },
            error: function(data) {
                toast("error", "Something went wrong.", "Error");
            },
            complete: function() {
                $("#submit-btn").html("<i class='fa fa-paper-plane-o'></i>");
                $("#submit-btn").removeAttr('disabled');
                $("#attachment-name").html("");
            }
        });
    }
</script>

{{-- Vendor Scripts --}}
<script src="{{ asset(mix('vendors/js/vendors.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/ui/prism.min.js')) }}"></script>
@yield('vendor-script')
{{-- Theme Scripts --}}
<script src="{{ asset(mix('js/core/app-menu.js')) }}"></script>
<script src="{{ asset(mix('js/core/app.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/components.js')) }}"></script>
@if ($configData['blankPage'] == false)
    <script src="{{ asset(mix('js/scripts/customizer.js')) }}"></script>
    <script src="{{ asset(mix('js/scripts/footer.js')) }}"></script>
@endif
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/jquery.validate.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.2/additional-methods.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/pdfmake.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/vfs_fonts.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/datatables.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/datatables.buttons.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/buttons.html5.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/buttons.print.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/buttons.bootstrap.min.js') }}"></script>
<script src="{{ asset('vendors/js/tables/datatable/datatables.bootstrap4.min.js') }}"></script>
<script>
    toastr.options = {
        "closeButton": false,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
    };
    $(".has-sub").each(function() {
        let sub = $(this).find('ul');
        if (sub.children().length == 0) {
            $(this).remove();
        }
    });
    $(document).ready(function() {
        $.fn.selectpicker.Constructor.DEFAULTS.style = 'border-light bg-white';
    });
</script>
<script src="{{ asset('js/scripts/jquery.form.js') }}"></script>
<script src="{{ asset('js/scripts/main.js') }}"></script>
<script src="{{ asset('vendors/js/forms/select/select2.full.min.js') }}"></script>
<script src="{{ asset('js/scripts/select_ajax.js') }}"></script>
<script>
    window.setTimeout(function() {
        $(".alert").fadeTo(500, 0).slideUp(500, function() {
            $(this).remove();
        });
    }, 2000);



    $('#view-profile').on('click', function() {
        var id = "{{ Auth::id() }}";
        var url = `{{ url('student/${id}/viewprofile') }}`;
        //student/{id}/viewprofile
        $('#apply-model').modal('show');
        $('.apply-title').html('View Profile');
        $(".dynamic-apply").html("Loading..");
        getContent({

            "url": url,
            success: function(data) {
                $('#apply-model').find('.modal-dialog').addClass('modal-lg');
                $(".dynamic-apply").html(data);
            }
        });

    });

    $("body").on("change", "select", function() {
        try {
            $(this).valid();
        } catch (e) {

        }
    });
    $("body").on("change", "#start-date, #end-date, #exam-date, #working_from, #working_upto", function() {
        try {
            $(this).valid();
        } catch {}
    })
    try {
        $.fn.dataTable.ext.errMode = 'none';
    } catch (e) {}
</script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
{{-- page script --}}
@yield('page-script')
{{-- page script --}}
@yield('foot_script')

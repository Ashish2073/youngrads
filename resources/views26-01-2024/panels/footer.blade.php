<!-- BEGIN: Footer-->
@if($configData["mainLayoutType"] == 'horizontal' && isset($configData["mainLayoutType"]))
    <footer
            class="footer {{ $configData['footerType'] }} {{($configData['footerType']=== 'footer-hidden') ? 'd-none':''}} footer-light navbar-shadow">
        @else
            <footer
                    class="footer {{ $configData['footerType'] }} {{($configData['footerType']=== 'footer-hidden') ? 'd-none':''}} footer-light">
                @endif
                <p class="clearfix blue-grey lighten-2 mb-0"><span
                            class="float-md-left d-block d-md-inline-block mt-25">COPYRIGHT &copy; {{ date('Y') }}<a
                                class="text-bold-800 grey darken-2" href="{{ url('/') }}"
                                target="_blank">{{ config('app.name') }},</a>All rights Reserved</span><span
                            class="float-md-right d-none d-md-block">Hand-crafted & Made with<i
                                class="feather icon-heart pink"></i></span>
                    <button class="btn btn-primary btn-icon scroll-top" type="button"><i
                                class="feather icon-arrow-up"></i></button>
                </p>
            </footer>
            <!-- END: Footer-->

            <!--apply model-->
            <div class="modal fade" id="apply-model">
              <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title apply-title"></h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                  </div>
                  <div class="modal-body dynamic-apply">

                  </div>
                </div>
              </div>
            </div>

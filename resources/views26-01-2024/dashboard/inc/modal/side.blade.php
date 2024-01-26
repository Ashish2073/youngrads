<style>
    .modal {
        /* z-index: 9999999!important; */
    }
    .modal .modal-dialog-aside {
        /* width: 500px; */
        width: 75%;
        max-width: 100%;
        height: 100%;
        margin: 0;
        transform: translate(0);
        transition: transform .2s;
        
    }

    .modal .modal-dialog-aside .modal-content {
        height: inherit;
        border: 0;
        border-radius: 0;
    }

    .modal .modal-dialog-aside .modal-content .modal-body {
        overflow-y: auto
    }

    .modal.fixed-left .modal-dialog-aside {
        margin-left: auto;
        transform: translateX(100%);
    }

    .modal.fixed-right .modal-dialog-aside {
        margin-right: auto;
        transform: translateX(-100%);
    }

    .modal.show .modal-dialog-aside {
        transform: translateX(0);
    }

    .modal-dialog-aside .modal-header .close {
        margin: 0rem 0rem 0rem auto;
    }
</style>
<!-- Modal -->
<div class="modal fixed-left fade pr-0" id="dynamic-modal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="myModalLabel2">
    <div class="modal-dialog modal-dialog-aside" role="document">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title dynamic-title" id="myModalLabel2"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body dynamic-body">

            </div>
        </div><!-- modal-content -->
    </div><!-- modal-dialog -->
</div><!-- modal -->

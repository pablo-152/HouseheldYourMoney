    <div class="footer-wrapper">
            <div class="footer-section f-section-1">
                <p class="">Copyright ©2025 <a target="_blank" href="">Pablo Ruiz</a>, Reservados todos los derechos.</p>
            </div>
            <div class="footer-section f-section-2">
                <p class="">Codificado con <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-heart"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"></path></svg></p>
            </div>
        </div>
    </div>
</div>
<div id="Modal_Zoom" class="modal animated zoomInUp custo-zoomInUp" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">

        </div>
    </div>
</div>
<script src="<?=base_url() ?>template/assets/js/libs/jquery-3.1.1.min.js"></script>
<script src="<?=base_url() ?>template/bootstrap/js/popper.min.js"></script>
<script src="<?=base_url() ?>template/bootstrap/js/bootstrap.min.js"></script>
<script src="<?=base_url() ?>template/plugins/perfect-scrollbar/perfect-scrollbar.min.js"></script>
<script src="<?=base_url() ?>template/assets/js/app.js"></script>

<script>
    $(document).ready(function() {
        App.init();
        $("#Modal_Zoom").on("show.bs.modal", function(e) {
            var link = $(e.relatedTarget);
            $(this).find(".modal-content").load(link.attr("modal_zoom"));
        });
    });
</script>

<script src="<?=base_url() ?>template/plugins/select2/custom-select2.js"></script>
<script src="<?=base_url() ?>template/assets/js/custom.js"></script>
<script src="<?=base_url() ?>template/plugins/dropify/dropify.min.js"></script>
<script src="<?=base_url() ?>template/plugins/blockui/jquery.blockUI.min.js"></script>
<script src="<?=base_url() ?>template/assets/js/users/account-settings.js"></script>
<script src="<?=base_url() ?>template/plugins/highlight/highlight.pack.js"></script>

<!-- BEGIN PAGE LEVEL SCRIPTS -->
<script src="<?=base_url() ?>template/assets/js/scrollspyNav.js"></script>
<script src="<?=base_url() ?>template/plugins/table/datatable/datatables.js"></script>
<script src="<?=base_url() ?>template/plugins/select2/select2.min.js"></script>
<!-- END PAGE LEVEL SCRIPTS -->

</body>
</html>  

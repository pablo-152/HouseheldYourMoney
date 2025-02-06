<?php $this->load->view('View_HYM/Otros/header-modulos'); ?>
<?php $this->load->view('View_HYM/Otros/nav-modulos'); ?>
<body>
    <div class="layout-px-spacing">
        <div class="row layout-top-spacing">
            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-form">
                        <div class="form-group row col-xl-12">
                            <div class="col-xl-6">
                                <div class="form-group">
                                    <h4>Navegación</h4>
                                </div>
                            </div>
                            <div class="col-xl-6 text-right">
                                <div class="form-group">
                                    <button class="btn btn-dark mb-4 mr-2 btn-lg" data-toggle="modal" data-target="#Modal_Zoom" modal_zoom="<?= site_url('Login/Modal_Navegacion') ?>">Nuevo</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-xl-12 col-lg-12 col-sm-12 layout-spacing">
                <div class="widget-content widget-content-area br-6">
                    <div class="table-form">
                        <div class="form-group row mr-3">
                            <label for="min" class="col-sm-5 col-form-label col-form-label-sm">Minimum age:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control form-control-sm" name="min" id="min" placeholder="">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="max" class="col-sm-5 col-form-label col-form-label-sm">Maximum age:</label>
                            <div class="col-sm-7">
                                <input type="text" class="form-control form-control-sm" name="max" id="max" placeholder="">
                            </div>
                        </div>
                    </div>
                    <div id="list_navegacion"></div>
                </div>
            </div>
        </div>
    </div>

<?php $this->load->view('View_HYM/Otros/footer-modulos'); ?>
<script>
    $(document).ready(function() {
        Lista_Navegacion();
    });

    function Lista_Navegacion(){
        var url="<?php echo site_url(); ?>Login/Lista_Navegacion";
        $.ajax({
            type:"POST",
            url:url,
            success:function (resp) {
                $('#list_navegacion').html(resp);
            }
        });
    }
</script>
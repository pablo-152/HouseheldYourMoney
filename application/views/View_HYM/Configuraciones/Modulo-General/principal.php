<?php $Mid = $this->session->userdata('Mid');
$MSid = $this->session->userdata('MSid');
$MSSid = $this->session->userdata('MSSid');
//echo $Mid." ".$MSid."".$MSSid;?>
<div class="layout-px-spacing" >
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
                </div>
                <div id="list_navegacion"></div>
            </div>
        </div>
    </div>
</div>
<script> 
    
    $(document).ready(function() {
        Lista_Navegacion();
    });

    
</script>
<form id="formulario_insert_navegacion" method="POST" enctype="multipart/form-data" class="formulario">
    <div class="modal-header">
        <h5 class="modal-title">Nueva Navegación</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <svg aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-x">
                <line x1="18" y1="6" x2="6" y2="18"></line>
                <line x1="6" y1="6" x2="18" y2="18"></line>
            </svg>
        </button>
    </div>
    <div class="modal-body">
        <div class="col-md-11 mx-auto">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="phone">Padre</label>
                        <select class="form-control" id="id_padre_navegacion" name="id_padre_navegacion">
                            <option value="0">--Nuevo Modulo--</option>
                            <?php foreach ($list_navegacion as $list) { ?>
                                <option value="<?= $list['id_navegacion']; ?>"><?= $list['nombre_padre_titulo']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="website1">Titulo</label>
                        <input type="text" class="form-control mb-4" id="titulo_navegacion" name="titulo_navegacion" placeholder="Escribe un Titulo">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Link</label>
                        <input type="text" class="form-control mb-4" id="link_navegacion" name="link_navegacion" placeholder="Escribe a donde redireccionar">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="website1">Descripción</label>
                        <input type="text" class="form-control mb-4" id="descripcion_navegacion" name="descripcion_navegacion" placeholder="Escribe una Descripción">
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="form-group">
                        <label for="svg">Codig - SVG</label>
                        <textarea class="form-control" id="svg_navegacion" name="svg_navegacion" placeholder="Ingresar SVG" rows="6"></textarea>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="form-group">
                        <label>SVG</label>
                        <div id="preview"></div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <label for="phone">Niveles</label>
                        <select class="form-control tagging" id="id_nivel[]" name="id_nivel[]" multiple="multiple">
                            <?php foreach ($list_niveles as $list) { ?>
                                <option value="<?= $list['id_nivel']; ?>"><?= $list['nom_nivel']; ?></option>
                            <?php } ?>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer md-button">
        <button class="btn" data-dismiss="modal"><i class="flaticon-cancel-12"></i> Cancelar</button>
        <button type="button" class="btn btn-primary" onclick="Insertar_Navegacion();">Guardar</button>
    </div>
</form>
<style>
    #preview {
        width: 200px;
        height: 150px;
        border: 1px solid #ccc;
        display: flex;
        align-items: center;
        justify-content: center;
    }
</style>
<script>
    $(".tagging").select2({});
    document.getElementById('svg_navegacion').addEventListener('input', function() {
        let svgCode = this.value.trim();
        document.getElementById('preview').innerHTML = svgCode;
    });

    function Insertar_Navegacion() {
        var dataString = new FormData(document.getElementById('formulario_insert_navegacion'));
        var url = "<?php echo site_url(); ?>Login/Insertar_Navegacion";

        var svg_navegacion = document.getElementById('svg_navegacion').value;

        if (Valida_Insertar_Navegacion()) {
            $.ajax({
                url: url,
                data: dataString,
                type: "POST",
                processData: false,
                contentType: false,
                success: function(data) {
                    $('#Modal_Zoom').modal('hide');
                    Lista_Navegacion();
                }
            });
        }
    }

    function Valida_Insertar_Navegacion() {
        if ($('#titulo_navegacion').val() == '') {
            Swal(
                'Ups!',
                'Debe ingresar una Nota.',
                'warning'
            ).then(function() {});
            return false;
        }

        if ($('#descripcion_navegacion').val() == '') {
            Swal(
                'Ups!',
                'Debe ingresar una Nota.',
                'warning'
            ).then(function() {});
            return false;
        }
        return true;
    }
</script>
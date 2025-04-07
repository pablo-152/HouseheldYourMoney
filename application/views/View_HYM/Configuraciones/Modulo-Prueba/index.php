<?php 
$Mid = $this->session->userdata('Mid');
$MSid = $this->session->userdata('MSid');
$MSSid = $this->session->userdata('MSSid');
$SoloModulo = str_replace('Div', '', $Mid);
$SoloSubModulo = str_replace('Div', '', $MSid);
?>
<script>
    $(document).ready(function() {
        Principal(); // Llama a la función solo cuando este archivo está cargado
        activarMenu("<?php echo $Mid; ?>","<?php echo $MSid; ?>","<?php echo $MSSid; ?>");
    });

    function Principal() {
        //alert('Función Principal ejecutada');
        var url = "<?php echo site_url(); ?>" + "/Login/Prueba_Modulo"; 
        
        $.ajax({
            url: url,
            type: 'POST',
            success: function(resp) {
                $('#modulos').html(resp); // Carga el contenido de la respuesta dentro del div #modulos
            },
            error: function(xhr, status, error) {
                console.error("Error:", error); // Si hay un error, lo mostramos en la consola
            }
        });
    }

    function Lista_Navegacion(){
        var url="<?php echo site_url(); ?>Login/Lista_Navegacion";
        $.ajax({
            type:"POST",
            url:url,
            success:function (resp) {
                $('#list_navegacion').html(resp);
                //Lista_Nav();//para recargar el nav
            }
        });
    }

    function Lista_Nav(){
        var url="<?php echo site_url(); ?>Login/Lista_Nav";
        $.ajax({
            type:"POST",
            url:url,
            success:function (resp) {
                $('#sidebar').html(resp);
            }
        });
    }
</script>
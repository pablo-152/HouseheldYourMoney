<script>
    function obtenerDatosGoogle(token) {
        fetch('http://localhost/HouseheldYourMoney/login/google.php', { // Asegúrate de que la ruta sea correcta
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ token: token })
        })
        .then(response => response.json())  
        .then(data => {
            console.log("Respuesta de google.php:", data); // Muestra la respuesta en consola

            if (data.success) {
                console.log("Usuario autenticado:", data);
                Ingresar_desde_Google(data.nombre, data.email, data.foto);
            } else {
                console.error('Error en la autenticación:', data.error);
            }
        })
        .catch(error => console.error('Error en fetch:', error));
    }

    // Llama a esta función cuando obtengas el token de Google
    function onSignIn(googleUser) {
        var token = googleUser.getAuthResponse().id_token;
        obtenerDatosGoogle(token);
    }


    function Ingresar_desde_Google(nombre, email,foto){
        //alert("ingresar");
        var nombre = nombre;
        var email = email;
        var foto = foto;
        var urladministrador = "<?php echo site_url(); ?>" + "/login/Modulo_Principal";
        var url = "<?php echo site_url(); ?>" + "/login/Ingresar_desde_Google";

          $.ajax({
            url: url,
            data: {'nombre':nombre,'email':email,'foto':foto},
            type: 'POST',
            success: function(resp){
              if(resp==="error"){
                swal({
                    title: 'Los datos ingresados de usuario y/o contraseña son incorrectos',
                    animation: false,
                    customClass: 'animated tada',
                    padding: '2em'
                })
              }else{
                location.href = urladministrador;
              }
            }
          });
    }


    function Ingresar(){
        /*$(document)
        .ajaxStart(function() {
            $.blockUI({
                message: '<svg> ... </svg>',
                fadeIn: 800,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        })
        .ajaxStop(function() {
            $.blockUI({
                message: '<svg> ... </svg>',
                fadeIn: 800,
                timeout: 100,
                overlayCSS: {
                    backgroundColor: '#1b2024',
                    opacity: 0.8,
                    zIndex: 1200,
                    cursor: 'wait'
                },
                css: {
                    border: 0,
                    color: '#fff',
                    zIndex: 1201,
                    padding: 0,
                    backgroundColor: 'transparent'
                }
            });
        });*/
        
        var usuario = document.getElementById("usuario").value;
        var clave = document.getElementById("password").value;

        var urladministrador = "<?php echo site_url(); ?>" + "/login/Modulo_Principal";
        var url = "<?php echo site_url(); ?>" + "/login/ingresar";

        if(Valida_Datos()){
          $.ajax({
            url: url,
            data: {'usuario':usuario,'clave':clave},
            type: 'POST',
            success: function(resp){
              if(resp==="error"){
                swal({
                    title: 'Los datos ingresados de usuario y/o contraseña son incorrectos',
                    animation: false,
                    customClass: 'animated tada',
                    padding: '2em'
                })
              }else{
                location.href = urladministrador;
              }
            }
          });
        }
    }

    function Valida_Datos(){
        if($('#usuario').val() == '') {
            swal({
                title: 'Debe ingresar usuario',
                animation: false,
                customClass: 'animated tada',
                padding: '2em'
            })
            return false;
        }
        if($('#clave').val() == '') {
            swal({
                title: 'Debe ingresar contraseña',
                animation: false,
                customClass: 'animated tada',
                padding: '2em'
            })
            return false;
        }
        return true;
    }

    function handleCredentialResponse(response) {
        // Decodificar el token JWT recibido de Google
        console.log("ID Token: " + response.credential);

        // Puedes enviarlo a tu backend para verificarlo y autenticar al usuario
        //fetch("https://lyfproyectos.com/HouseheldYourMoney/application/views/login/google.php", {
            fetch("http://localhost/HouseheldYourMoney/application/views/login/google.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/x-www-form-urlencoded"
            },
            body: "token=" + response.credential
        }).then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log("Nombre:", data.nombre);
                    console.log("Correo:", data.email);
                    console.log("Foto de perfil:", data.foto);
                    Ingresar_desde_Google(data.nombre, data.email, data.foto);
                }
        });
    }

    window.onload = function () {
        google.accounts.id.initialize({
            client_id: "687851700073-kr1husplkvfn92tcbh0o7l7db025islb.apps.googleusercontent.com", // 🔹 Reemplázalo con el Client ID de Google
            callback: handleCredentialResponse
        });

        google.accounts.id.renderButton(
        document.getElementById("google-login"), {
            theme: "outline",
            size: "large"
        }
        );

        google.accounts.id.prompt(); // Opcional: muestra la ventana emergente de inicio de sesión
    };

</script>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use PhpOffice\PhpSpreadsheet\Helper\Sample;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\RichText\RichText;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Style\Protection;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet;
use \PhpOffice\PhpSpreadsheet\Cell\Coordinate;


require 'PHPMailer/Exception.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
class Login extends CI_Controller {

	public function __construct() {
		parent::__construct();
		$this->load->library(array('session'));
		//$this->load->library('Password');
	    $this->load->helper(array('url'));
		//$this->load->model('N_model');
		//$this->load->model('Model_Ifv');
	}

	public function index(){
		//$this->load->view('login/login');
		$this->load->view('View_HYM/Configuraciones/Modulo-General/index');
	}
	
	public function ingresar(){
		$usuario = $_POST['Usuario'];
		$password = $_POST['Password'];
		$sesion_ifv = $this->N_model->login($usuario);
		
		if (count($sesion_ifv) > 0) {
			
			$_SESSION['usuario'] = $sesion_ifv;
			//echo($password);
			if (password_verify($password, $_SESSION['usuario'][0]['password'])) {
				echo $_SESSION['usuario'][0]['id_colaborador'];
			}
			else{
				echo "error";
			}
		}else {
			echo "error";
		}
	}

	public function Ingresar_Horario($documento){
		include "mcript.php";
		$dato['documento'] = $desencriptar($documento);
		//$dato['list_especialidad']= $this->N_model->list_especialidad();
		$dato['get_alumno'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		$dato['get_horario'] = $this->Model_Ifv->consulta_grupo_horario($dato);
		$dato['list_turno'] = $this->Model_Ifv->get_list_todo_turno();
		/*$dato['turno']="";
		if(count($dato['get_horario'])>0){
			if($dato['get_horario'][0]['id_turno']!=""){
				$array = explode(",",$dato['get_horario'][0]['id_turno']);
				$cantidad = count($array);
				$i = 0;
				$cadena = "";
				while($i < $cantidad){
					$busqueda = in_array($array[$i], array_column($dato['list_turno'], 'id_turno'));
					if($busqueda != false){
						$posicion=array_search($array[$i],array_column($dato['list_turno'],'id_turno'));
						$cadena = $cadena.$dato['list_turno'][$posicion]['nom_turno'].",";
					}
					$i++;
				}
				$dato['turno'] = substr($cadena,0,-1);
			}
		}*/
		$dato['get_config'] = $this->Model_Ifv->get_config('horario_ifv');
		$this->load->view('login/horarios',$dato);
	}

	public function Ingresar_Uniforme($documento){
		include "mcript.php";
		$dato['documento'] = $desencriptar($documento);
		//$dato['list_especialidad']= $this->N_model->list_especialidad();
		$dato['get_alumno'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		$dato['get_horario'] = $this->Model_Ifv->consulta_grupo_horario($dato);
		$dato['list_turno'] = $this->Model_Ifv->get_list_todo_turno();
		/*$dato['turno']="";
		if(count($dato['get_horario'])>0){
			if($dato['get_horario'][0]['id_turno']!=""){
				$array = explode(",",$dato['get_horario'][0]['id_turno']);
				$cantidad = count($array);
				$i = 0;
				$cadena = "";
				while($i < $cantidad){
					$busqueda = in_array($array[$i], array_column($dato['list_turno'], 'id_turno'));
					if($busqueda != false){
						$posicion=array_search($array[$i],array_column($dato['list_turno'],'id_turno'));
						$cadena = $cadena.$dato['list_turno'][$posicion]['nom_turno'].",";
					}
					$i++;
				}
				$dato['turno'] = substr($cadena,0,-1);
			}
		}*/
		$dato['get_config'] = $this->Model_Ifv->get_config('horario_ifv');
		$this->load->view('login/uniformes',$dato);
	}

	public function Ingresar_Contactenos(){
		$dato['list_motivo']= $this->N_model->get_list_motivo_contactenos('3');
		$this->load->view('login/contactenos',$dato);
	}

	public function Insert_Contactenos(){
		if ($_POST['g-recaptcha-response'] == '') {
			echo "2Captcha inválido";
		} else {
			$obj = new stdClass();
			$obj->secret = "6LcesQ4lAAAAAIz_7wBYzSVcXdNSbisE91lAfVeN";
			$obj->response = $_POST['g-recaptcha-response'];
			$obj->remoteip = $_SERVER['REMOTE_ADDR'];
			$url = 'https://www.google.com/recaptcha/api/siteverify';
			
			$options = array(
			'http' => array(
			'header' => "Content-type: application/x-www-form-urlencoded\r\n",
			'method' => 'POST',
			'content' => http_build_query($obj)
			)
			);
			$context = stream_context_create($options);
			$result = file_get_contents($url, false, $context);
			
			$validar = json_decode($result);
			
			/* FIN DE CAPTCHA */
			
			if ($validar->success) {
				$dato['id_motivo']= $this->input->post("id_motivo"); 
				$dato['nombre']= $this->input->post("nombre"); 
				$dato['email']= $this->input->post("email"); 
				$dato['celular']= $this->input->post("celular"); 
				$dato['mensaje']= $this->input->post("mensaje"); 
				
				$dato['get_motivo']= $this->N_model->get_id_motivo_contactenos($dato['id_motivo']);
				$dato['de']="contactenos@ifv.edu.pe";
				$dato['para']="";
				$hoy = getdate();
				$dato['dia'] = $hoy['mday']."/".$hoy['mon']."/".$hoy['year'];
				$dato['hora'] = $hoy['hours'].":".$hoy['minutes'];
				if(count($dato['get_motivo'])>0){
					//$dato['de']=$dato['list_motivo'][0]['de'];
					$dato['para']=$dato['get_motivo'][0]['para'];

					if($dato['id_motivo']=="1"){
						$motivo="Alumno";
					}else{
						$motivo="Profesores";
					}
					$mail = new PHPMailer(true);
					try {
						/*$mail->SMTPDebug = 0;
						$mail->isSMTP();
						$mail->Host       = 'smtp.gmail.com';
						$mail->SMTPAuth   = true;
						$mail->Username   = 'admision@ifv.edu.pe';
						$mail->Password   = 'ldej fhvy sqth tmnp';
						$mail->SMTPSecure = 'SSL';         
						$mail->Port       = 587;*/

						$mail->SMTPDebug = 0;                      // Enable verbose debug output
                        $mail->isSMTP();                                            // Send using SMTP
                        $mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
                        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
                        $mail->Username   = 'admision@ifv.edu.pe';                     // usuario de acceso
                        $mail->Password   = 'lxruwiqijszzomar';                                // SMTP password
                        $mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
                        $mail->Port = 587;


						$mail->setFrom($dato['de'],'Ifv Online');
						// $mail->addAddress($dato['para']); para un solo destino
						$emails = array($dato['para'], $dato['email']);
						for($i = 0; $i < count($emails); $i++) {
							$mail->AddAddress($emails[$i]);
							echo $emails[$i];
						}

						

						// Establecer el formato de correo electrónico en HTML
						$mail->isHTML(true);
						//calidad@ifv.edu.pe
						$mail->Subject = $dato['get_motivo'][0]['titulo']."-".$dato['nombre'];
						$mailContent = $this->load->view('login/contactenos_mail', $dato, TRUE);
						$mail->Body= $mailContent;
						//$mail->attach('/img/firma.jpeg');
						$mail->CharSet = 'UTF-8';
						$mail->send();

						//$this->Model_Cochera->update_recupera_contrasenia($dato);
					
					} catch (Exception $e) {
					
						echo "2Hubo un error al enviar el correo: {$mail->ErrorInfo}";
					
					}
				}
				$this->Model_Ifv->insert_contactenos($dato);
				echo "1Registro Exitoso";
			} else {
				echo "2Captcha inválido";
			}
		}

		
	}

	public function logout(){
     	$this->session->sess_destroy();
     	redirect('');
     }

     public function tippoacceso($usuario){
   
		 header('Content-Type: application/json');
        $data = $this->n_model->gettipoacceso($usuario);
        echo json_encode($data);

		//echo $data;
     }

	 public function Cambiar_Password(){
		$dato['password']= password_hash($this->input->post("password"), PASSWORD_DEFAULT);
		$dato['password_desencriptado']= $this->input->post("password");
		$dato['id_colaborador'] = $this->input->post("id_colaborador");
		$this->Model_Ifv->update_contra($dato);
		echo "1";
    }

	public function Recuperar_Password(){
        $this->load->view('login/recuperar_contra'); 
    }

	public function Busca_Grupo(){
		$dato['id_especialidad']= $this->input->post("id_especialidad");
		$dato['list_grupo'] = $this->Model_Ifv->busca_grupo_xespecialidad($dato);
		$this->load->view('login/cmb_grupo',$dato);
	}

	public function Modal(){
		//var_dump("1234");
        $this->load->view('login/modal');
    }

	public function Modal2(){
		//var_dump("1234");
        $this->load->view('login/modal2');
    }

	public function Modal_Consulta_Horario(){
		//var_dump("1234");
        $this->load->view('login/modal_consulta_horario');
    }

	public function Modal_Consulta_Uniforme(){
		//var_dump("1234");
        $this->load->view('login/modal_consulta_uniforme');
    }

	public function Modal_Perfil(){
		//var_dump("1234");
        $this->load->view('login/modal_perfil');
    }

	public function Modal_Carrito(){
		//var_dump("1234");
        $this->load->view('login/modal_carrito');
    }

	public function Consulta_Horario(){
		$dato['documento']= $this->input->post("documento"); 
		$dato['get_alumno'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		if(count($dato['get_alumno'])>0){
			$dato['get_horario2'] = $this->Model_Ifv->consulta_grupo_horario2($dato);
			if(count($dato['get_horario2']) == 4){
				$this->Model_Ifv->insert_registro_ingreso_cyp($dato);
				echo "1¡DNI Válido!";
			}else{
				echo "3Tus Horarios todavia no se encuetran disponibles.";	
			}
		}else{
			echo "2Intenta de nuevo o contáctanos para ayudarte.";	
		}
		
	}

	public function Consulta_Uniforme(){
		$dato['documento']= $this->input->post("documento"); 
		$dato['get_horario'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		if(count($dato['get_horario'])>0){
			echo "1¡DNI Válido!";
		}else{
			echo "2Intenta de nuevo o contáctanos para ayudarte.";	
		}
	}

	public function Encriptar_Documento(){
		include "mcript.php";
		$dato['documento']= $this->input->post("documento"); 
		$doc_encriptado = $encriptar($dato['documento']);
		echo $doc_encriptado;
	}

	public function Modal3(){
        $this->load->view('login/modal3');
    }
	public function Modal4(){
        $this->load->view('login/modal4');
    }
	
	public function Busca_Modulo(){
		$dato['id_especialidad'] = $this->input->post("id_especialidad");
		$dato['list_modulo'] = $this->Model_Ifv->valida_modulo_disponible($dato['id_especialidad']);
		$this->load->view('login/cmb_modulo',$dato);
	}

	public function Busca_Turno(){
		$dato['id_especialidad']= $this->input->post("id_especialidad");
		$dato['id_grupo'] = $this->input->post("grupo");
		$dato['id_modulo']= $this->input->post("id_modulo");
		$dato['list_turno'] = $this->Model_Ifv->get_list_turno_xid($dato);
		$dato['get_img'] = $this->Model_Ifv->get_list_grupo($dato['id_grupo']);
		$dato['get_config'] = $this->Model_Ifv->get_config('horario_ifv');
		$this->load->view('login/cmb_turno',$dato);
	}

	public function Descargar_Horario($documento,$tipo) {
		
			$dato['documento'] = ($documento);
			$dato['get_alumno'] = $this->Model_Ifv->consulta_alumno_fv($dato);
			$dato['get_horario'] = $this->Model_Ifv->consulta_grupo_horario($dato);
			$dato['get_config'] = $this->Model_Ifv->get_config('horario_ifv');
			if($tipo==4){
				if($dato['get_horario'][0]['horario_EFSRT']!=""){
					$image=$dato['get_config'][0]['url_config'].$dato['get_horario'][0]['horario_EFSRT'];
					$name     = basename($dato['get_horario'][0]['horario_EFSRT']);
					$name2     = $dato['get_horario'][0]['horario_EFSRT'];
					$ext      = pathinfo($dato['get_horario'][0]['horario_EFSRT'], PATHINFO_EXTENSION);
				}else{
					$image="template/assets/img/1410px x 410.jpg";
					$name     = basename("template/assets/img/1410px x 410.jpg");
					$ext      = pathinfo("template/assets/img/1410px x 410.jpg", PATHINFO_EXTENSION);
				}
			}elseif($tipo==3){
				if($dato['get_horario'][0]['horario_pdf']!=""){
					$image=$dato['get_config'][0]['url_config'].$dato['get_horario'][0]['horario_pdf'];
					$name     = basename($dato['get_horario'][0]['horario_pdf']);
					$name2    = $dato['get_horario'][0]['horario_pdf'];
					$ext      = pathinfo($dato['get_horario'][0]['horario_pdf'], PATHINFO_EXTENSION);
				}else{
					$image="template/assets/img/1410px x 410.jpg";
					$name     = basename("template/assets/img/1410px x 410.jpg");
					$ext      = pathinfo("template/assets/img/1410px x 410.jpg", PATHINFO_EXTENSION);
				}
			}/*else{
				if($dato['get_horario'][0]['horariocelular']!=""){
					$image=$dato['get_config'][0]['url_config'].$dato['get_horario'][0]['horariocelular'];
					$name     = basename($dato['get_horario'][0]['horariocelular']);
					$ext      = pathinfo($dato['get_horario'][0]['horariocelular'], PATHINFO_EXTENSION);
				}else{
					$image="template/assets/img/390px x 575px.png";
					$name     = basename("template/assets/img/390px x 575px.png");
					$ext      = pathinfo("template/assets/img/390px x 575px.png", PATHINFO_EXTENSION);
				}
				
			}*/
			//var_dump($dato['get_config'][0]['url_config'].$dato['get_horario'][0]['horario_EFSRT']);
			//force_download($image,NULL);
			//echo($dato['get_config'][0]['url_config'].$dato['get_horario'][0]['horario_EFSRT']);
			///echo($dato['get_alumno'][0]['Grupo'].",".$dato['get_alumno'][0]['id_especialidad']);
			force_download($name, file_get_contents($image));
    }

	public function Dni_Validacion() {
		$dato['dni']= $this->input->post("dni");
		if($dato['dni']=='123'){
			echo "1";
		}else{
			echo "2";
		}
	}
	
	public function Modal_Consulta_Celular(){
        $this->load->view('login/modal-celular');
    }

	public function Modal_Encomendar(){
        $this->load->view('login/modal-encomendar');
    }

	public function Modal_Efectuar_Pedido(){
        $this->load->view('login/modal-efectuar-pedido');
    }

	public function Modal_Dni_Valido_Uniforme(){
        $this->load->view('login/modal-dni-valido-uniforme');
    }

	public function Modal_Dni_inValido_Uniforme(){
        $this->load->view('login/modal-dni-invalido-uniforme');
    }

	public function Modal_Dni_Valido(){
        $this->load->view('login/modal-dni-valido');
    }

	public function Modal_Dni_inValido(){
        $this->load->view('login/modal-dni-invalido');
    }

	public function Modal_Dni_inValido_Doc_Subidos(){
        $this->load->view('login/modal-dni-invalido-documentos');
    }

	public function Modal_Envio_Documento(){
		$dato['list_documento'] = $this->Model_Ifv->get_list_documento_conf_ifv();
        $this->load->view('login/modal_envio_documento',$dato);
    }

	public function Consulta_Dni_Envio_Documento(){
		include "mcript.php";
		$dato['documento']= $this->input->post("num_doc_envio_documento"); 
		$dato['id_documento']= $this->input->post("id_documento"); 
		$dato['get_horario'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		if(count($dato['get_horario'])>0){
			$doc_encriptado = $encriptar($dato['documento']);
			$id_doc_encriptado = $encriptar($dato['id_documento']);
			echo "1".$doc_encriptado."__".$id_doc_encriptado;
		}else{
			echo "2Intenta de nuevo o contáctanos para ayudarte.";	
		}
	}

	public function Modal_Dni_inValido_Envio_Documento(){
        $this->load->view('login/modal-dni-invalido_envio_documento');
    }

	public function Envio_Documentos($documento){
		include "mcript.php";
		$dato['encriptado']=$documento;
		$cadena=explode('__',$documento);
		$dato['documento'] = $desencriptar($cadena[0]);
		$dato['id_documento'] = $desencriptar($cadena[1]);
		$dato['get_alumno'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		$dato['get_documento'] = $this->Model_Ifv->get_list_documento_conf_ifv($dato['id_documento']);
		$this->load->view('login/envio_documentos',$dato);
	}

	public function Insert_Enviar_Documento(){
		include "mcript.php";
		$dato['encriptado']= $this->input->post("encriptado"); 
		$cadena=explode('__',$dato['encriptado']);
		$dato['documento']= $desencriptar($cadena[0]); 
		$dato['id_documento']= $desencriptar($cadena[1]); 
		$dato['get_documento'] = $this->Model_Ifv->get_list_documento_conf_ifv($dato['id_documento']);
		$dato['get_alumno'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		$dato['id_empresa'] = 6;
		$dato['cod_documento']=$dato['get_documento'][0]['codigo'];
		$dato['nom_documento']=$dato['get_documento'][0]['nombre'];
		$dato['Codigo']=$dato['get_alumno'][0]['Codigo'];
		$dato['Apellido_Paterno']=$dato['get_alumno'][0]['Apellido_Paterno'];
		$dato['Apellido_Materno']=$dato['get_alumno'][0]['Apellido_Materno'];
		$dato['Email']=$dato['get_alumno'][0]['Email'];
		$dato['Nombre']=$dato['get_alumno'][0]['Nombre'];
		$dato['Especialidad']=$dato['get_alumno'][0]['Especialidad'];
		$dato['valida']=$this->Model_Ifv->valida_documentos_cargados($dato);
		if(count($dato['valida'])>0){
			echo "2Ya se envió anteriormente el documento.";
		}else{
			$this->Model_Ifv->insert_documentos_cargados($dato);
			if($dato['Email']!=""){
				$mail = new PHPMailer(true);
				try {
					$mail->SMTPDebug = 0;                      // Enable verbose debug output
					$mail->isSMTP();                                            // Send using SMTP
					$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
					$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
					$mail->Username   = 'noreplay@ifv.edu.pe';                     // usuario de acceso
					$mail->Password   = 'ifvc2022';                                // SMTP password
					$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
					$mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
					$mail->setFrom('noreplay@ifv.edu.pe', "IFV Online");
					$mail->addAddress($dato['Email']);
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = "Documentos Recibidos - IFV ";

					$mail->Body = '<FONT SIZE=4>Hola<br>
					Tu inscripción ha sido registrada y tus documentos recepcionados.<br>
					En breve recibirás la validación a través de notificaciones o correo electrónico.<br>
					Manténgase atento(a).<br>
					Gracias</FONT SIZE>';
					$mail->CharSet = 'UTF-8';
					$mail->send();
				} catch (Exception $e) {
					echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
				}
				echo "1enviado";
			}
		}
		/*$dato['get_horario'] = $this->Model_Ifv->consulta_alumno_fv($dato);
		if(count($dato['get_horario'])>0){
			$doc_encriptado = $encriptar($dato['documento']);
			$id_doc_encriptado = $encriptar($dato['id_documento']);
			echo "1".$doc_encriptado."__".$id_doc_encriptado;
		}else{
			echo "2Intenta de nuevo o contáctanos para ayudarte.";	
		}*/
	}

	public function Modal_Fut(){
        $this->load->view('login/modal_consulta_fut');
    }

	public function Consulta_Cod_Fut(){
		include "mcript.php";
		$dato['documento']= $this->input->post("documento_fut"); 
		$dato['get_venta'] = $this->Model_Ifv->consulta_venta_fut($dato);

		if(count($dato['get_venta'])>0){
			if($dato['get_venta'][0]['estado_recibido']==1){
				if($dato['get_venta'][0]['fecha_hoy'] <= $dato['get_venta'][0]['fecha_fin']){
					$dato['get_cod'] = $this->Model_Ifv->consulta_detalle_cod_producto_venta_fut($dato);
					if(count($dato['get_cod'])>0){
						echo "1".$encriptar($dato['documento']);
					}else{
						echo "2Intenta de nuevo o contáctanos para ayudarte.";
					}
				}else{
					echo "2Ya vencio los 2 días hábiles que tenias para poder enviar el FUT.";
				}
			}elseif($dato['get_venta'][0]['estado_recibido']==69){
				$dato['get_cod'] = $this->Model_Ifv->consulta_detalle_cod_producto_venta_fut($dato);
					if(count($dato['get_cod'])>0){
						echo "1".$encriptar($dato['documento']);
					}else{
						echo "2Intenta de nuevo o contáctanos para ayudarte.";
					}
			}else{
				echo "2Este FUT ya fue utilizado.";
			}
		}else{
			echo "2Intenta de nuevo o contáctanos para ayudarte.";	
		}
	}

	public function Ingresar_Fut($documento){
		include "mcript.php";
		$dato['documento_form']=$documento;
		$dato['documento']= $desencriptar($documento); 
		
		$dato['get_venta'] = $this->Model_Ifv->consulta_venta_fut($dato);
		$dato['Id']=$dato['get_venta'][0]['id_alumno'];
		$dato['get_alumno'] = $this->Model_Ifv->get_alumno_ifv($dato);
		$dato['get_producto'] = $this->Model_Ifv->consulta_detalle_cod_producto_venta_fut($dato);
		$dato['cod_mes']=date('m');
		$dato['get_mes'] = $this->Model_Ifv->get_mes_xcod($dato);
		$this->load->view('login/fut',$dato);
	}

	public function Confirmar_Registro_Fut(){
        $this->load->view('login/modal_confirmar_reg_fut');
    }

	public function Insert_Registro_Fut(){
		//var_dump("hola");
		include "mcript.php";
		$documento=$this->input->post("documento_form");
		
		$dato['documento']= $desencriptar($documento); 
		$dato['texto_fut']= $this->input->post("texto_fut");
		$dato['asunto']= $this->input->post("asunto"); 
		$dato['get_venta'] = $this->Model_Ifv->consulta_venta_fut($dato);
		$dato['Id']=$dato['get_venta'][0]['id_alumno'];
		$dato['Id_venta']=$dato['get_venta'][0]['id_venta'];
		$dato['get_alumno'] = $this->Model_Ifv->get_alumno_ifv($dato);
		$dato['get_producto'] = $this->Model_Ifv->consulta_detalle_cod_producto_venta_fut($dato);
		$dato['cod_mes']=date('m');
		$dato['get_mes'] = $this->Model_Ifv->get_mes_xcod($dato);

		$dato['id_empresa'] = 6;
		$dato['nom_producto']=$dato['get_producto'][0]['nom_sistema'];
		$dato['nom_producto']=$dato['get_producto'][0]['nom_sistema'];
		$dato['id_producto']=$dato['get_producto'][0]['id_producto'];
		//$dato['cod_documento']=$dato['get_documento'][0]['codigo'];
		//$dato['nom_documento']=$dato['get_documento'][0]['nombre'];
		$dato['Dni']=$dato['get_alumno'][0]['Dni'];
		$dato['Codigo']=$dato['get_alumno'][0]['Codigo'];
		$dato['Apellido_Paterno']=$dato['get_alumno'][0]['Apellido_Paterno'];
		$dato['Apellido_Materno']=$dato['get_alumno'][0]['Apellido_Materno'];
		$dato['Email']=$dato['get_alumno'][0]['Email'];
		$dato['Nombre']=$dato['get_alumno'][0]['Nombre'];
		$dato['Especialidad']=$dato['get_alumno'][0]['Especialidad'];

		$anio=date('Y');
        $query_id = $this->Model_Ifv->ultimo_cod_fut($anio);
        $totalRows_t = count($query_id);

        $aniof=substr($anio, 2,2);
        if($totalRows_t<9)
        {
            $codigo=$aniof."FUT-0000".($totalRows_t+1);
        }

        if($totalRows_t>8 && $totalRows_t<99)
        {
            $codigo=$aniof."FUT-000".($totalRows_t+1);
        }

        if($totalRows_t>98 && $totalRows_t<999)
        {
            $codigo=$aniof."FUT-00".($totalRows_t+1);
        }

		if($totalRows_t>998 && $totalRows_t<9999)
        {
            $codigo=$aniof."FUT-00".($totalRows_t+1);
        }

        if($totalRows_t>9998 && $totalRows_t<99999)
        {
            $codigo=$aniof."FUT-0".($totalRows_t+1);
        }

		if($totalRows_t>99998)
        {
            $codigo=$aniof."FUT-".($totalRows_t+1);
        }

        $dato['cod_fut']=$codigo;

		$dato['valida']=$this->Model_Ifv->valida_registro_fut_ifv($dato);

		if(count($dato['valida'])==0){
			$dato['get_alumno'] = $this->Model_Ifv->get_alumno_ifv($dato);
			$this->Model_Ifv->insert_registro_fut_ifv($dato);
		}
		$this->Model_Ifv->update_venta($dato);
		if($dato['Email']!=""){
			$mail = new PHPMailer(true);
			try {
				$mail->SMTPDebug = 0;                      // Enable verbose debug output
				$mail->isSMTP();                                            // Send using SMTP
				$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = 'noreplay@ifv.edu.pe';                     // usuario de acceso
				$mail->Password   = 'ifvc2022';                                // SMTP password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;     // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above
				$mail->setFrom('noreplay@ifv.edu.pe', 'Instituto Federico Villarreal'); //desde donde se envia                       
				/*
				$mail->SMTPDebug = 0;                      // Enable verbose debug output
				$mail->isSMTP();                                            // Send using SMTP
				$mail->Host       = 'smtp.gmail.com';                    // Set the SMTP server to send through
				$mail->SMTPAuth   = true;                                   // Enable SMTP authentication
				$mail->Username   = 'admision@ifv.edu.pe';                     // usuario de acceso
				$mail->Password   = 'lxruwiqijszzomar';                                // SMTP password
				$mail->SMTPSecure = 'tls';         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
				$mail->Port       = 587;
				$mail->setFrom('admision@ifv.edu.pe', "IFV Online");*/

				$mail->addAddress($dato['Email']);
				//$mail->addAddress('ruizandiap.idat@gmail.com');
				$mail->isHTML(true);                                  // Set email format to HTML
				$mail->Subject = "Documentos Recibidos - IFV ";

				$mail->Body = '<FONT SIZE=4>Hola:<br>
				Su FUT ha sido recepcionado satisfactoriamente y está siendo procesado.<br>
				Estaremos enviando la respuesta  por este mismo medio.<br>
				Manténgase en contacto.</FONT SIZE>';
				$mail->CharSet = 'UTF-8';
				$mail->send();
			} catch (Exception $e) {
				echo "Hubo un error al enviar el correo: {$mail->ErrorInfo}";
			}
				echo "1enviado";
		}
	}

	public function Insert_Registro_Fut_Detalle(){
		include "mcript.php";
		$documento=$this->input->post("documento_form");
		
		$dato['documento']= $desencriptar($documento); 
		 
		$dato['texto_fut']= $this->input->post("texto_fut"); 
		$dato['get_venta'] = $this->Model_Ifv->consulta_venta_fut($dato);
		$dato['Id']=$dato['get_venta'][0]['id_alumno'];
		$dato['Id_venta']=$dato['get_venta'][0]['id_venta'];
		$dato['img_comuimg']= $this->input->post("img_comuimg"); 
		$dato['id_envio_fut']= $this->Model_Ifv->busca_id_fut_ifv($dato);

		$this->Model_Ifv->insert_registro_fut_ifv_detalle($dato);
	}

	public function Modulo_Principal(){
		if ($this->session->userdata('usuario')) {
        	$this->load->view('modulo/index');
		} else {
            redirect('/login');
        }
    }

	public function Principal(){
		$dato['id_colaborador']= $this->input->post("id_colaborador");
		$dato['list_grupos'] = $this->Model_Ifv->list_grupos_x_colaborador($dato);
        $this->load->view('modulo/principal',$dato);
    }

	public function Evaluaciones_Pendientes(){
		//$dato['tipo']= $tipo; 
        $this->load->view('modulo/evaluaciones_pendiente');
    }

	public function Lista_Evaluaciones_Pendientes(){
        $this->load->view('modulo/lista_evaluaciones_pendiente');
    }
	
	public function Alumnos(){
        $this->load->view('modulo/alumnos');
    }

	public function Registros_Documentos(){
        $this->load->view('modulo/registros_documentos');
    }

	public function Mis_Grupos(){
		$dato['tipo']= $this->input->post("tipo"); 
		$dato['id_colaborador']= $this->input->post("id_colaborador");
		$dato['list_grupos'] = $this->Model_Ifv->list_grupos_x_colaborador($dato);
        $this->load->view('modulo/grupos',$dato);
    }

	public function Mis_Unidades_Didacticas(){
		$dato['tipo']= $this->input->post("tipo"); 
		$dato['id_colaborador']= $this->input->post("id_colaborador");
		$dato['id_grupo']= $this->input->post("id_grupo");
		$dato['get_id'] = $this->Model_Ifv->get_id_evaluacion($dato,1);
		$dato['list_unidaddes_didacticas'] = $this->Model_Ifv->list_unidades_didacticas_x_colaborador($dato);
        $this->load->view('modulo/unidades_didacticas',$dato);
    }

	public function Logros_Unidades_Didacticas(){
		$dato['tipo']= $this->input->post("tipo"); 
		$dato['id_colaborador']= $this->input->post("id_colaborador");
		$dato['id_unidad']= $this->input->post("id_unidad");
		$dato['id_grupo']= $this->input->post("id_grupo");
		$dato['get_id'] = $this->Model_Ifv->get_id_evaluacion($dato,2);
		$dato['list_logros'] = $this->Model_Ifv->list_logros_x_colaborador($dato);
        $this->load->view('modulo/logros_unidades_didacticas',$dato);
    }


	public function Evaluaciones_Logros_Unidades_Didacticas(){
		$dato['tipo']= $this->input->post("tipo"); 
		$dato['id_unidad']= $this->input->post("id_unidad");
		$dato['id_grupo']= $this->input->post("id_grupo");
		$dato['id_evaluacion_gudce']= $this->input->post("id_evaluacion_gudce");
		$dato['id_colaborador']= $this->input->post("id_colaborador");
		$dato['get_id'] = $this->Model_Ifv->get_id_evaluacion($dato,3);
		$dato['list_eval'] = $this->Model_Ifv->list_eval_x_logros($dato);
		$this->load->view('modulo/evaluaciones_logro_unidad_didactica',$dato);
	}

	public function Lista_Descripcion_Grupo(){
		$dato['tipo']= $this->input->post("tipo"); 
		$dato['id_unidad']= $this->input->post("id_unidad");
		$dato['id_grupo']= $this->input->post("id_grupo");
		$dato['id_evaluacion_gudce']= $this->input->post("id_evaluacion_gudce");
		$dato['id_colaborador']= $this->input->post("id_colaborador");
		$dato['numero_eval']= $this->input->post("numero_eval");
		$dato['get_id'] = $this->Model_Ifv->get_id_evaluacion($dato,4);
		$dato['id']=1;
		$dato['count_alumnos'] = count($this->Model_Ifv->list_alumnos_x_grupos($dato));
		$this->load->view('modulo/lista_evaluaciones_pendiente',$dato);
	}
	
	public function Lista_Alumnos() {
		$dato['id']= $this->input->post("id");
		$dato['id_grupo']= $this->input->post("id_grupo");
		$dato['color']= $this->input->post("color");
		$dato['id_unidad'] = $this->input->post('id_unidad');
        $dato['numero_eval'] = $this->input->post('numero_eval');
        $dato['id_evaluacion_gudce'] = $this->input->post('id_evaluacion_gudce');
		$dato['list_alumnos'] = $this->Model_Ifv->list_alumnos_x_grupos($dato);
		$dato['get_ruta'] = $this->Model_Ifv->get_ruta(7);
		//var_dump($dato['color']);
		if($dato['id']==3){
			$this->load->view('modulo/agregar_notas_alumnos',$dato);
		}else{
			$this->load->view('modulo/lista_alumno',$dato);
		}
		
	}

	public function GuardarNotas() {
		$id_grupo = $this->input->post('id_grupo');
		$alumnos = $this->input->post('alumnos');
		$dato['id']=1;
		$dato['id_unidad'] = $this->input->post('id_unidad');
        $dato['numero_eval'] = $this->input->post('numero_eval');
        $dato['id_evaluacion_gudce'] = $this->input->post('id_evaluacion_gudce');
		$dato['id_grupo'] = $this->input->post('id_grupo');

		$this->Model_Ifv->agregar_notas($id_grupo, $alumnos);
		$count_alumnos = count($this->Model_Ifv->list_alumnos_x_grupos($dato));
		echo ($count_alumnos);
	}

	private function es_dispositivo_movil() {
        $agentes_moviles = array(
            'Android', 'iPhone', 'iPad', 'iPod', 'Windows Phone', 'webOS', 'BlackBerry', 'Opera Mini', 'IEMobile'
        );

        foreach ($agentes_moviles as $agente) {
            if (stripos($_SERVER['HTTP_USER_AGENT'], $agente) !== false) {
                return true; 
            }
        }

        return false;
    }

	public function Mis_Alumnos() {
		$dato['id_colaborador']= $this->input->post("id_colaborador"); 
		$dato['tipo']= $this->input->post("tipo"); 
		$dato['id_grupo']= $this->input->post("id_grupo");
		$dato['get_id'] = $this->Model_Ifv->get_id_grupo($dato);
		$dato['get_ruta'] = $this->Model_Ifv->get_ruta(7);
		$dato['list_mis_alumnos'] = $this->Model_Ifv->list_mis_alumnos($dato);

		if ($this->es_dispositivo_movil()) {
			$this->load->view('modulo/lista_mis_alumnos_mobile',$dato);
		} else {
			$this->load->view('modulo/lista_mis_alumnos',$dato);
		}
	}
		

	public function Excel_Listar_Alumnos($id_grupo){
		$dato['id_grupo'] = $id_grupo;
        $list_alumnos_grupo = $this->Model_Ifv->list_mis_alumnos($dato);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $sheet->getStyle("B2:J2")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
        $sheet->getStyle("B2:J2")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);

        $spreadsheet->getActiveSheet()->setTitle('Alumnos (Lista)');

        $sheet->setAutoFilter('B2:J2');

        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('C')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(18);
        $sheet->getColumnDimension('E')->setWidth(18);
        $sheet->getColumnDimension('F')->setWidth(20);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(15);
		$sheet->getColumnDimension('J')->setWidth(15);

        $sheet->getStyle('B2:J2')->getFont()->setBold(true);
        $sheet->freezePane('A3');

        $spreadsheet->getActiveSheet()->getStyle("B2:J2")->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setARGB('C8C8C8');

        $styleThinBlackBorderOutline = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $sheet->getStyle("B2:J2")->applyFromArray($styleThinBlackBorderOutline);

        $sheet->setCellValue("B2", 'Ape. Paterno');
        $sheet->setCellValue("C2", 'Ape. Materno');
        $sheet->setCellValue("D2", 'Nombres');
        $sheet->setCellValue("E2", 'DNI');
        $sheet->setCellValue("F2", 'Código');
        $sheet->setCellValue("G2", 'Correo');
        $sheet->setCellValue("H2", 'Especialidad');
        $sheet->setCellValue("I2", 'Modulo');
		$sheet->setCellValue("J2", 'Turno');

        $contador = 2;

        foreach ($list_alumnos_grupo as $list) {
            $contador++;

            $sheet->getStyle("B{$contador}:J{$contador}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
            $sheet->getStyle("E{$contador}")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle("B{$contador}:J{$contador}")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
            $sheet->getStyle("B{$contador}:J{$contador}")->applyFromArray($styleThinBlackBorderOutline);

            $sheet->setCellValue("B{$contador}", $list['apellido_paterno']);
            $sheet->setCellValue("C{$contador}", $list['apellido_materno']);
            $sheet->setCellValue("D{$contador}", $list['nombres']);
            $sheet->setCellValue("E{$contador}", $list['dni']);
            $sheet->setCellValue("F{$contador}", $list['codigo']);
            $sheet->setCellValue("G{$contador}", $list['correo']);
            $sheet->setCellValue("H{$contador}", $list['nom_especialidad']);
            $sheet->setCellValue("I{$contador}", $list['modulo']);
			$sheet->setCellValue("J{$contador}", $list['nom_turno']);
        }

        $writer = new Xlsx($spreadsheet);
        $filename = 'Alumnos (Lista)';
        if (ob_get_contents()) ob_end_clean();
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $filename . '.xlsx"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

	public function Pdf_Lista_Alumnos($grupo,$id_colaborador){
        if ($this->session->userdata('usuario')) {
			$dato['id_grupo'] = $grupo;
			$dato['id_colaborador']= $id_colaborador;
			$dato['get_ruta'] = $this->Model_Ifv->get_ruta(7);
            $dato['list_mis_alumnos'] = $this->Model_Ifv->list_mis_alumnos($dato);
			$dato['get_id'] = $this->Model_Ifv->get_id_grupo_pdf($dato);
            $mpdf = new \Mpdf\Mpdf([
                "format" => "A4",
                'default_font' => 'gothic',
            ]);
            $html = $this->load->view('modulo/lista_mis_alumnos_pdf', $dato, true);
            $mpdf->WriteHTML($html);
            $mpdf->Output();
        } else {
            redirect('/login');
        }
    }

	public function Guardar_Nota_Alumno_No_Asiste() {
		$dato['id_grupo'] = $this->input->post('id_grupo');
		$dato['id_alumno'] = $this->input->post('id_alumno');
		$dato['id']=1;
		$dato['id_unidad'] = $this->input->post('id_unidad');
        $dato['numero_eval'] = $this->input->post('numero_eval');
        $dato['id_evaluacion_gudce'] = $this->input->post('id_evaluacion_gudce');

		$this->Model_Ifv->agregar_nota_no_asiste($dato);
		$count_alumnos = count($this->Model_Ifv->list_alumnos_x_grupos($dato));
		echo ($count_alumnos);
	}

	public function Excel_GenerarReporte() {
		$spreadsheet = new Spreadsheet();
		
		$sheet = $spreadsheet->getActiveSheet();
		function columnToLetter($col) {
			$letter = '';
			while ($col > 0) {
				$col--;
				$letter = chr($col % 26 + 65) . $letter;
				$col = floor($col / 26);
			}
			return $letter;
		}

		$startColumn = 3; 
		$countfilas = 3;
		for ($i = 0; $i < 3; $i++) {
			$columnsUsed = 0;
			$sheet->mergeCellsByColumnAndRow($startColumn, 2, $startColumn + $countfilas, 2);
			$sheet->setCellValueByColumnAndRow($startColumn, 2, "INDIC. LOGRO " . ($i + 1));
			for ($j = 0; $j < $countfilas; $j++) {
				$sheet->setCellValueByColumnAndRow($startColumn + $columnsUsed, 3, "Valor " . $j);
				$columnLetter = Coordinate::stringFromColumnIndex($startColumn + $columnsUsed);
        		$sheet->getColumnDimension($columnLetter)->setWidth(4);
				$columnsUsed++;
			}
			$sheet->setCellValueByColumnAndRow($startColumn + $columnsUsed, 3, "Promedio" . ($i + 1));
			$columnLetter = Coordinate::stringFromColumnIndex($startColumn + $columnsUsed);
    		$sheet->getColumnDimension($columnLetter)->setWidth(6.57);
			$columnsUsed++;
			$startColumn += $columnsUsed;
		}
		$sheet->mergeCellsByColumnAndRow($startColumn, 2, $startColumn, 3);
		$sheet->setCellValueByColumnAndRow($startColumn, 2, "Parcial");
		$columnLetter = Coordinate::stringFromColumnIndex($startColumn);
		$sheet->getColumnDimension($columnLetter)->setWidth(4);
		$sheet->getStyle($columnLetter . "2:" . $columnLetter . "3")->getAlignment()->setTextRotation(90);
		$startColumn++;
		$countfilas = 3;
		for ($i = 0; $i < 3; $i++) {
			$columnsUsed = 0;
			$sheet->mergeCellsByColumnAndRow($startColumn, 2, $startColumn + $countfilas, 2);
			$sheet->setCellValueByColumnAndRow($startColumn, 2, "INDIC. LOGRO " . ($i + 1));
			for ($j = 0; $j < $countfilas; $j++) {
				$sheet->setCellValueByColumnAndRow($startColumn + $columnsUsed, 3, "Valor " . $j);
				$columnLetter = Coordinate::stringFromColumnIndex($startColumn + $columnsUsed);
        		$sheet->getColumnDimension($columnLetter)->setWidth(4);
				$columnsUsed++;
			}
			$sheet->setCellValueByColumnAndRow($startColumn + $columnsUsed, 3, "Promedio" . ($i + 1));
			$columnLetter = Coordinate::stringFromColumnIndex($startColumn + $columnsUsed);
    		$sheet->getColumnDimension($columnLetter)->setWidth(6.57);
			$columnsUsed++;
			$startColumn += $columnsUsed;
		}
	
		$sheet->mergeCellsByColumnAndRow($startColumn, 2, $startColumn, 3);
		$sheet->setCellValueByColumnAndRow($startColumn, 2, "Exámen Final");
		$columnLetter = Coordinate::stringFromColumnIndex($startColumn);
		$sheet->getColumnDimension($columnLetter)->setWidth(4);
		$sheet->getStyle($columnLetter . "2:" . $columnLetter . "3")->getAlignment()->setTextRotation(90);

		$sheet->mergeCellsByColumnAndRow($startColumn + 1, 2, $startColumn + 1, 3);
		$sheet->setCellValueByColumnAndRow($startColumn + 1, 2, "Nota Final");
		$columnLetter = Coordinate::stringFromColumnIndex($startColumn + 1);
		$sheet->getColumnDimension($columnLetter)->setWidth(4);
		$sheet->getStyle($columnLetter . "2:" . $columnLetter . "3")->getAlignment()->setTextRotation(90);
		
		$tituloRango = "A1:".$columnLetter."1";
		$encabezadoRango = "A2:".$columnLetter."3";
		$rangoBordes = "A1:".$columnLetter."3";
	
		$sheet->mergeCells($tituloRango);
		$sheet->setCellValue("A1", "Título Principal");
		$sheet->getStyle("A1")->getFont()->setBold(true)->setSize(16);
		$sheet->getStyle("A1")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("A1")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
	
		$sheet->mergeCells("A2:A3");
		$sheet->mergeCells("B2:B3");
		$sheet->setCellValue("A2", "N°");
		$sheet->setCellValue("B2", "APELLIDOS Y NOMBRES");
		$sheet->getStyle("A2:A3")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle("A2:A3")->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getStyle("C3:AC3")->getAlignment()->setTextRotation(90);

		$sheet->getColumnDimension('A')->setWidth(2.29);
		$sheet->getColumnDimension('B')->setWidth(33.29);
		$sheet->getStyle($rangoBordes)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);
		$sheet->getStyle($encabezadoRango)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
		$sheet->getStyle($encabezadoRango)->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);
		$sheet->getRowDimension(3)->setRowHeight(100.5);

		$writer = new Xlsx($spreadsheet);
		$filename = 'Excel_Custom_Template.xlsx';
		if (ob_get_contents()) ob_end_clean();
		header('Content-Type: application/vnd.ms-excel');
		header('Content-Disposition: attachment;filename="' . $filename . '"');
		header('Cache-Control: max-age=0');
		$writer->save('php://output');
	}
	
	

	
}

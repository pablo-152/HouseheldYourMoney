<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
class Login extends CI_Controller
{

	public function __construct()
	{
		parent::__construct();
		$this->load->library(array('session'));
		//$this->load->library('Password');
		$this->load->helper(array('url'));
		$this->load->model('N_model');
		//$this->load->model('Model_Ifv');
	}

	public function index()
	{
		//$navegacion = $this->N_model->get_navegacion();
		//$dato['navegacion'] = $navegacion;
		//$this->load->view('View_HYM/Configuraciones/Modulo-General/index',$dato);
		$this->load->view('login/login'/*,$dato*/);
	}

	public function ingresar()
	{
		$usuario = $_POST['usuario'];
		$clave = $_POST['clave'];
		$sesion_ifv = $this->N_model->login($usuario);

		if (count($sesion_ifv) > 0) {

			$_SESSION['usuario'] = $sesion_ifv;
			//echo($password);
			if (password_verify($clave, $_SESSION['usuario'][0]['password'])) {
				echo $_SESSION['usuario'][0]['id_colaborador'];
			} else {
				echo "error";
			}
		} else {
			echo "error";
		}
	}

	public function Ingresar_desde_Google()
	{
		$dato['nombre'] = $this->input->post("nombre");
		$dato['email'] = $this->input->post("email");
		$dato['foto'] = $this->input->post("foto");

		$get_id_usuario = $this->N_model->get_id_usuario_x_google($dato);

		if (count($get_id_usuario) == 0) {
			$this->N_model->insert_usuario_x_google($dato);
		}
		//var_dump($get_id_usuario);
		$_SESSION['usuario'] = $get_id_usuario;
		echo $_SESSION['usuario'][0]['id_nivel'];
	}

	public function Navegacion()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}

		$dato['Mid'] = $this->input->post('Mid');
		$dato['MSid'] = $this->input->post('MSid');
		$dato['MSSid'] = $this->input->post('MSSid');
		$dato['url'] = $this->input->post('url');

		$this->setSessionData($dato['Mid'], $dato['MSid'], $dato['MSSid'], $dato['url']);
		$dato['navegacion'] = $this->N_model->get_navegacion();
		$this->load->view('View_HYM/Configuraciones/Modulo-General/index', $dato);
	}

	public function Navegacion_Modulo()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}


		$this->load->view('View_HYM/Configuraciones/Modulo-General/principal');
	}



	public function Lista_Navegacion()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		//esta parte para sidebar
		$dato['navegacion_modulo'] = $this->N_model->get_navegacion_modulo();
		$this->load->view('View_HYM/Configuraciones/Modulo-General/lista', $dato);
	}

	public function logout()
	{
		$this->session->sess_destroy();
		redirect('/Login');
	}

	public function Modulo_Principal()
	{
		$navegacion = $this->N_model->get_navegacion();
		$dato['navegacion'] = $navegacion;
		$this->load->view('View_HYM/index', $dato);
	}

	public function Perfil()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		$navegacion = $this->N_model->get_navegacion();
		$dato['navegacion'] = $navegacion;
		$dato['extra_css'] = base_url() . 'template/assets/css/users/user-profile.css';
		$this->load->view('View_HYM/Perfil/usuario_perfil', $dato);
	}

	public function Modulo_Editar_Colaborador()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		$navegacion = $this->N_model->get_navegacion();
		$dato['navegacion'] = $navegacion;
		$dato['extra_css'] = base_url() . 'template/assets/css/users/user-profile.css';
		$this->load->view('View_HYM/Perfil/editar', $dato);
	}

	public function Modal_Navegacion()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		$dato['list_navegacion'] = $this->N_model->get_list_navegacion_x_nivel(['Modulo', 'SubModulo']);
		$dato['list_niveles'] = $this->N_model->get_list_nivel('2');
		$this->load->view('View_HYM/Configuraciones/Modulo-General/registrar', $dato);
	}

	public function Insertar_Navegacion()
	{
		$id_padre = $this->input->post('id_padre_navegacion');
		$id_niveles = $this->input->post('id_nivel');
		$id_nivel_navegacion = (!empty($id_niveles)) ? implode(',', $id_niveles) : NULL;

		// Determinar el tipo de navegación (Módulo, Submódulo, Subsubmódulo)
		if ($id_padre == '0' || $id_padre == NULL) {
			$tipo_navegacion = 'Modulo';
			$id_padre = NULL;
		} else {
			// Consultamos el tipo del padre en la base de datos
			$padre = $this->N_model->get_navegacion_del_id($id_padre);

			if ($padre) {
				if ($padre->tipo_navegacion == 'Modulo') {
					$tipo_navegacion = 'SubModulo';
				} elseif ($padre->tipo_navegacion == 'SubModulo') {
					$tipo_navegacion = 'SubSubModulo';
				} else {
					$tipo_navegacion = 'Modulo'; // Por seguridad, lo consideramos módulo
				}
			} else {
				$tipo_navegacion = 'Modulo'; // Si no se encuentra el padre, lo tomamos como módulo
			}

			// Establecer link_navegacion = NULL en los registros con el mismo id_padre
			if ($id_padre !== NULL) {
				$this->db->where('id_padre_navegacion', $id_padre);
				$this->db->update('navegacion', ['link_navegacion' => NULL]);
			}
		}

		$data = array(
			'id_padre_navegacion' => ($id_padre == '0') ? NULL : $id_padre,
			'tipo_navegacion' => $tipo_navegacion,
			'orden_navegacion' => 1,
			'svg_navegacion' => $this->input->post('svg_navegacion'),
			'link_navegacion' => $this->input->post('link_navegacion'),
			'titulo_navegacion' => $this->input->post('titulo_navegacion'),
			'descripcion_navegacion' => $this->input->post('descripcion_navegacion'),
			'id_nivel_navegacion' => $id_nivel_navegacion,
			'estado' => '2',
			'user_reg' => 1,
			'fec_reg' => date('Y-m-d H:i:s')
		);

		$this->N_model->insert_navegacion($data);
	}

	public function Lista_Nav()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		$data['navegacion'] = $this->N_model->get_navegacion();
		$this->load->view('View_HYM/Otros/nav-modulos', $data);
	}

	public function Modal_Editar_Navegacion()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		$dato['list_navegacion'] = $this->N_model->get_list_navegacion_x_nivel(['Modulo', 'SubModulo']);
		$dato['list_niveles'] = $this->N_model->get_list_nivel('2');
		$this->load->view('View_HYM/Configuraciones/Modulo-General/registrar', $dato);
	}

	public function setSessionData($Mid, $MSid, $MSSid, $url)
    {
        // Primero, elimina cualquier dato anterior de la sesión
        $this->session->unset_userdata('Mid');
        $this->session->unset_userdata('MSid');
        $this->session->unset_userdata('MSSid');
		$this->session->unset_userdata('url');
        
        // Ahora, guarda los nuevos valores
        $this->session->set_userdata('Mid', $Mid);
        $this->session->set_userdata('MSid', $MSid);
        $this->session->set_userdata('MSSid', $MSSid);
		$this->session->set_userdata('url', $url);
    }

	public function Prueba()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}

		$dato['Mid'] = $this->input->post('Mid');
		$dato['MSid'] = $this->input->post('MSid');
		$dato['MSSid'] = $this->input->post('MSSid');
		$dato['url'] = $this->input->post('url');

		$this->setSessionData($dato['Mid'], $dato['MSid'], $dato['MSSid'], $dato['url']);
		$dato['navegacion'] = $this->N_model->get_navegacion();
		$this->load->view('View_HYM/Configuraciones/Modulo-Prueba/index', $dato);
	}

	public function Prueba_Modulo()
	{
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}


		$this->load->view('View_HYM/Configuraciones/Modulo-Prueba/principal');
	}
	
}

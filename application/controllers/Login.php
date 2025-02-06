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
		$this->load->model('N_model');
		//$this->load->model('Model_Ifv');
	}

	public function index() {
		//$navegacion = $this->N_model->get_navegacion();
        //$data['navegacion'] = $navegacion;
		//$this->load->view('View_HYM/Configuraciones/Modulo-General/index',$data);
		$this->load->view('login/login'/*,$data*/);
	}
	
	public function ingresar(){
		$usuario = $_POST['usuario'];
		$clave = $_POST['clave'];
		$sesion_ifv = $this->N_model->login($usuario);
		
		if (count($sesion_ifv) > 0) {
			
			$_SESSION['usuario'] = $sesion_ifv;
			//echo($password);
			if (password_verify($clave, $_SESSION['usuario'][0]['password'])) {
				echo $_SESSION['usuario'][0]['id_colaborador'];
			}
			else{
				echo "error";
			}
		}else {
			echo "error";
		}
	}

	public function Ingresar_desde_Google(){
		$dato['nombre']= $this->input->post("nombre"); 
		$dato['email']= $this->input->post("email"); 
		$dato['foto']= $this->input->post("foto"); 

		$get_id_usuario = $this->N_model->get_id_usuario_x_google($dato);

		if(count($get_id_usuario) == 0){
			$this->N_model->insert_usuario_x_google($dato);
		}
		//var_dump($get_id_usuario);
		$_SESSION['usuario'] = $get_id_usuario; 
		echo $_SESSION['usuario'][0]['id_nivel'];
	}

	public function Navegacion(){
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		$navegacion = $this->N_model->get_navegacion();
        $data['navegacion'] = $navegacion;
		$this->load->view('View_HYM/Configuraciones/Modulo-General/index',$data);
	}

	public function Lista_Navegacion(){
		if (!$this->session->userdata('usuario')) {
			redirect('/login');
		}
		
		$data['navegacion_modulo'] = $this->N_model->get_navegacion_modulo();
		$this->load->view('View_HYM/Configuraciones/Modulo-General/lista',$data);
	}

	public function logout(){
     	$this->session->sess_destroy();
     	redirect('');
     }

	public function Modulo_Principal(){
		$navegacion = $this->N_model->get_navegacion();
        $data['navegacion'] = $navegacion;
        $this->load->view('View_HYM/index',$data);
    }

	public function Perfil(){
		$navegacion = $this->N_model->get_navegacion();
        $data['navegacion'] = $navegacion;
		$this->load->view('View_HYM/Perfil/usuario_perfil',$data);
	}

}

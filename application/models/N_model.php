<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
  class n_model extends CI_Model{
    function __construct(){
      parent::__construct();
      $this->load->database();
      $this->load->library('session');  
    }

    public function get_navegacion() {
      $this->db->select('*');
      $this->db->order_by('orden_navegacion', 'ASC');
      $query = $this->db->get('navegacion');
      //echo $this->db->last_query();
      return $query->result(); 
    }

  // Obtener submódulos de un módulo
  public function get_submodulos()
{
    $query = $this->db->get('navegacion'); // O la consulta que estés haciendo
    if ($query->num_rows() > 0) {
        return $query->result(); // Devolver los resultados como un array de objetos
    }
    return null; // En caso de no haber resultados
}

  // Obtener subsubmódulos de un submódulo
  public function get_subsubmodulos($id_padre) {
      $this->db->select('*');
      $this->db->where('id_padre_navegacion', $id_padre);  // Obtener subsubmódulos
      $query = $this->db->get('navegacion');
      return $query->result();
  }


    public function encriptar($dato){
        $sql = "call usp_retorna_cadena_pwd_ED('".$dato['password']."','E')";
        //echo($sql);
        $query = $this->db->query($sql)->result_Array();
        mysqli_next_result( $this->db->conn_id );
        return $query;
    }

    public function login2($dato){
      $sql = "call usp_valida_acceso_usuario('".$dato['usuario']."','".$dato['encriptado'][0]['c_nuev_cade']."','".$dato['cliente']."')";
      //echo($sql);
      $query = $this->db->query($sql)->result_Array();
      mysqli_next_result( $this->db->conn_id );
      return $query;
  }

    public function login($usuario){
      $sql = "SELECT * FROM colaborador WHERE correo_corporativo = '".$usuario."' ";
      //echo($sql);
      $query = $this->db->query($sql)->result_array();
      return $query;
    }

    public function gettipoacceso($usuario){

      $sql="select us.CODUSER,t.Tipo_acceso, t.DescAcceso
                  from Usuario_Sistema us 
                  inner join tipoacceso t on t.codi_sistema=us.Codi_Sistema and t.Tipo_acceso=us.Tipo_Acceso
                  where us.Codi_Sistema='0030' and us.CODUSER='".$usuario."'";

        $query = $this->db->query($sql)->result_array();
      if(count($query) > 0){

      }
      return $query;
    }
    function insert_ingreso_sistema($usuario){
      // $id_usuario= $_SESSION['usuario'];
       $ip=$_SERVER['REMOTE_ADDR'];
       $navegador=$_SERVER['HTTP_USER_AGENT'];
       $sql="insert into ingreso_sistema (id_usuario, ip,navegador, fec_ingreso ) 
       values ('".$usuario."', '".$ip."','".$navegador."', NOW())";
       $this->db->query($sql);
    }

    function cmb_cliente_sistema(){
      $sql = "SELECT * FROM uvw_clientes_sistema;";
      $query = $this->db->query($sql)->result_Array();
      return $query;
  }

  function get_list_motivo_contactenos($tipo){
    $sql = "SELECT m.* FROM motivo_contactenos m 
            where m.estado=2 and m.tipo='$tipo'";
    $query = $this->db->query($sql)->result_Array();
    return $query;
  }

  function get_id_motivo_contactenos($id_motivo){
    $sql = "SELECT m.* FROM motivo_contactenos m where m.id_motivo='$id_motivo'";
    $query = $this->db->query($sql)->result_Array();
    return $query;
  }

  function list_especialidad($id_especialidad=null){
    if(isset($id_especialidad) && $id_especialidad>0){
        $sql = "SELECT *,CASE WHEN licenciamiento=1 THEN 'L14' WHEN licenciamiento=2 THEN 'L20' ELSE '' 
                END AS nom_licenciamiento 
                FROM especialidad 
                WHERE id_especialidad=$id_especialidad";
    }else{
        $sql = "SELECT e.*,CASE WHEN e.licenciamiento=1 THEN 'L14' WHEN e.licenciamiento=2 THEN 'L20' ELSE '' 
                END AS nom_licenciamiento,st.nom_status,st.color
                FROM especialidad e 
                LEFT JOIN status st ON st.id_status=e.estado
                WHERE e.estado=2
                ORDER BY e.abreviatura ASC";
    }
    $query = $this->db->query($sql)->result_Array();
    return $query;
  }
      
  }
?>

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Observaciones_model extends CI_Model {
	
	function __construct(){
		parent::__construct();
	}

	function otra_observacion($data){
		$idb = $this->obtener_ultimo_id("vyp_observacion_solicitud","id_observacion_solicitud");
		$fecha = date("Y-m-d H:i:s");

		if($this->db->insert('vyp_observacion_solicitud', array('id_observacion_solicitud' => $idb, 'id_mision' => $data['id_mision'], 'observacion' => $data['observacion'], 'fecha_hora' => $fecha, 'corregido' => false, 'nr_observador' => $data['nr_observador'], 'id_tipo_observador' => $data['id_tipo_observador'], 'tipo_observador' => $data['tipo_observador']))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function pagar_solicitud($data){		

		if($this->db->query("UPDATE vyp_mision_oficial SET fecha_pago = '".$data['fecha_pago']."', pagado_en = '".$data['tipo_pago']."', no_cheque = '".$data['num_cheque']."', estado = '8' WHERE id_mision_oficial = '".$data['id_mision']."'") && $this->db->query("UPDATE vyp_pago_emergencia SET estado = '1' WHERE id_pago_emergencia = '".$data['id_pago']."'")){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function cambiar_estado_solicitud($data){
		$fecha = date("Y-m-d H:i:s");
		$this->db->where("id_mision_oficial",$data["id_mision"]);

		if($data['estado'] == "2" || $data['estado'] == "4" || $data['estado'] == "6"){
			if($this->db->update('vyp_mision_oficial', array('estado' => $data['estado'], 'ultima_observacion' => $fecha))){
				return "exito";
			}else{
				return "fracaso";
			}
		}else{
			if($this->db->update('vyp_mision_oficial', array('estado' => $data['estado']))){
				return "exito";
			}else{
				return "fracaso";
			}
		}
	}

	function eliminar_observacion($data){
		if($this->db->delete("vyp_observacion_solicitud",array('id_observacion_solicitud' => $data['id_observacion']))){
			return "exito";
		}else{
			return "fracaso";
		}
	}

	function verificar_observaciones($data){
		$query = $this->db->query("SELECT * FROM vyp_observacion_solicitud WHERE id_mision = '".$data."' AND corregido = 0");
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}

	function obtener_ultimo_id($tabla,$nombreid){
		$this->db->order_by($nombreid, "asc");
		$query = $this->db->get($tabla);
		$ultimoid = 0;
		if($query->num_rows() > 0){
			foreach ($query->result() as $fila) {
				$ultimoid = $fila->$nombreid; 
			}
			$ultimoid++;
		}else{
			$ultimoid = 1;
		}
		return $ultimoid;
	}

}
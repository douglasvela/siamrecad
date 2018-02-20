<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_reportes extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('bancos_model');
	}

	public function index(){
		$this->load->view('templates/header');
		$this->load->view('informes/menu_reportes');
		$this->load->view('templates/footer');

	}
	public function reporte_ejemplo(){
		$this->load->library('mpdf');
		/*Constructor variables
			Modo: c
			Formato: A4 - default
			Tamaño de Fuente: 12
			Fuente: Arial
			Magen Izq: 32
			Margen Derecho: 25
			Margen arriba: 47
			Margen abajo: 47
			Margen cabecera: 10
			Margen Pie: 10
			Orientacion: P / L
		*/
		$this->mpdf=new mPDF('c','A4','12','Arial',10,10,35,17,3,9,'L'); 
		$cabecera = '<table><tr>
 		<td>
		    <img src="application/controllers/informes/escudo.jpg" width="85px" height="80px">
		</td>
		<td width="490px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> UNIDAD FINANCIERA INSTITUCIONAL <br> FONDO CIRCULANTE DEL MONTO FIJO <br> REPORTE VIATICOS POR DEPARTAMENTO</center><h6></td>
		<td>
		    <img src="application/controllers/informes/logomtps.jpeg"  width="125px" height="85px">
		   
		</td>
	 	</tr></table>';

	 	$pie = '{PAGENO} de {nbpg} páginas';


		$this->mpdf->SetHTMLHeader($cabecera);
		//$this->mpdf->SetHTMLFooter('{PAGENO} of {nbpg} pages');
		$this->mpdf->setFooter($pie);

		$cuerpo = '
			
		';

		$this->mpdf->WriteHTML($cuerpo);

		$this->mpdf->Output();
	}
	public function reporte_viatico_pendiente_empleado($id){
		$this->load->library('mpdf');
		$this->load->model('Reportes_viaticos_model');
		/*Constructor variables
			Modo: c
			Formato: A4 - default
			Tamaño de Fuente: 12
			Fuente: Arial
			Magen Izq: 32
			Margen Derecho: 25
			Margen arriba: 47
			Margen abajo: 47
			Margen cabecera: 10
			Margen Pie: 10
			Orientacion: P / L
		*/
		$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9); 

		$cabecera = '<table><tr>
 		<td>
		    <img src="application/controllers/informes/escudo.jpg" width="85px" height="80px">
		</td>
		<td width="950px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> UNIDAD FINANCIERA INSTITUCIONAL <br> FONDO CIRCULANTE DEL MONTO FIJO <br> REPORTE VIATICOS PENDIENTE POR EMPLEADO</center><h6></td>
		<td>
		    <img src="application/controllers/informes/logomtps.jpeg"  width="125px" height="85px">
		   
		</td>
	 	</tr></table>';

	 	$pie = '{PAGENO} de {nbpg} páginas';


		$this->mpdf->SetHTMLHeader($cabecera);
		//$this->mpdf->SetHTMLFooter('{PAGENO} of {nbpg} pages');
		$this->mpdf->setFooter($pie);
		 $data = array('nr'=>$id);
		$empleado_NR_viatico = $this->Reportes_viaticos_model->obtenerNREmpleadoViatico($data);
		foreach ($empleado_NR_viatico->result() as $key) {	
		}
		$ids = array('nr' =>  $id);
		$viatico = $this->Reportes_viaticos_model->obtenerListaviatico_pendiente($ids);

		
		$cuerpo = '
		<h6>NR: '.$id.'	Empleado: '.($key->nombre_completo).'</h6>
			<table  class="" border="1" style="width:100%">
				
				<thead >

					<tr>
						<th align="center" rowspan="2">Fecha Solicitud</th>
						<th align="center" rowspan="2">Fecha Inicio Mision</th>
						<th align="center" rowspan="2">Fecha Fin Mision</th>
						<th align="center" rowspan="2">Actividad</th>
						<th align="center" rowspan="2">Detalle Actividad</th>
						<th align="center" colspan="3">Detalle Montos</th>
						<th align="center" rowspan="2">Estado</th>
						 
					</tr>
					<tr>
						<th align="center">Viaticos</th>
						<th align="center">Pasajes</th>
						<th align="center">Alojamiento</th>
					</tr>
				</thead>
				<tbody>
					
					';
				if($viatico->num_rows()>0){
				foreach ($viatico->result() as $viaticos) {
				
					$estado = $this->Reportes_viaticos_model->obtenerDetalleEstado($viaticos->estado);
					foreach ($estado->result() as $estado_detalle) {}
					$actividad = $this->Reportes_viaticos_model->obtenerDetalleActividad($viaticos->id_actividad_realizada);
					foreach ($actividad->result() as $actividad_detalle) {}
					$totales = $this->Reportes_viaticos_model->obtenerTotalMontos($viaticos->id_mision_oficial);
					foreach ($totales->result() as $totales_detalle) {}
						
					$cuerpo .= '
						<tr>
							<td>'.date('d-m-Y',strtotime($viaticos->fecha_solicitud)).'</td>
							<td>'.date('d-m-Y',strtotime($viaticos->fecha_mision_inicio)).'</td>
							<td>'.date('d-m-Y',strtotime($viaticos->fecha_mision_fin)).'</td>
							<td>'.($actividad_detalle->nombre_vyp_actividades).'</td>
							<td>'.utf8_decode($viaticos->detalle_actividad).'</td>
							<td>$'.number_format($totales_detalle->viatico,2,".",",").'</td>
							<td>$'.number_format($totales_detalle->pasaje,2,".",",").'</td>
							<td>$'.number_format($totales_detalle->alojamiento,2,".",",").'</td>
							<td>'.ucwords($estado_detalle->nombre_estado).'</td>
						</tr>
						';
					
					}
				}else{
				$cuerpo .= '
						<tr><td colspan="9"><center>No hay registros</center></td></tr>
					';
				}
				$cuerpo .= '
					
				</tbody>
			</table>

        ';         // LOAD a stylesheet         
        $stylesheet = file_get_contents(base_url().'assets/plugins/bootstrap/css/bootstrap.min.css');
		$this->mpdf->AddPage('L','','','','',10,10,35,17,3,9);
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/text         

		$this->mpdf->WriteHTML($cuerpo);

		$this->mpdf->Output();
	}

	public function reporte_viatico_pagado_empleado($id,$min,$max){
		$this->load->library('mpdf');
		$this->load->model('Reportes_viaticos_model');
		/*Constructor variables
			Modo: c
			Formato: A4 - default
			Tamaño de Fuente: 12
			Fuente: Arial
			Magen Izq: 32
			Margen Derecho: 25
			Margen arriba: 47
			Margen abajo: 47
			Margen cabecera: 10
			Margen Pie: 10
			Orientacion: P / L
		*/
		$this->mpdf=new mPDF('c','A4','10','Arial',10,10,35,17,3,9); 

		$cabecera = '<table><tr>
 		<td>
		    <img src="application/controllers/informes/escudo.jpg" width="85px" height="80px">
		</td>
		<td width="950px"><h6><center>MINISTERIO DE TRABAJO Y PREVISION SOCIAL <br> UNIDAD FINANCIERA INSTITUCIONAL <br> FONDO CIRCULANTE DEL MONTO FIJO <br> REPORTE VIATICOS PAGADOS POR EMPLEADO</center><h6></td>
		<td>
		    <img src="application/controllers/informes/logomtps.jpeg"  width="125px" height="85px">
		   
		</td>
	 	</tr></table>';

	 	$pie = '{PAGENO} de {nbpg} páginas';


		$this->mpdf->SetHTMLHeader($cabecera);
		//$this->mpdf->SetHTMLFooter('{PAGENO} of {nbpg} pages');
		$this->mpdf->setFooter($pie);
		 $data = array('nr'=>$id);
		$empleado_NR_viatico = $this->Reportes_viaticos_model->obtenerNREmpleadoViatico($data);
		foreach ($empleado_NR_viatico->result() as $key) {	
		}
		$ids = array(
			'nr' =>  $key->nr,
			'fmin' => $min,
			'fmax' => $max
		);
		$viatico = $this->Reportes_viaticos_model->obtenerListaviaticoPagado($ids);

		
		$cuerpo = '
		<h6>NR: '.$id.'	Empleado: '.($key->nombre_completo).'</h6>
			<table  class="" border="1" style="width:100%">
				
				<thead >

					<tr>
						<th align="center" rowspan="2">Fecha Solicitud</th>
						<th align="center" rowspan="2">Fecha Inicio Mision</th>
						<th align="center" rowspan="2">Fecha Fin Mision</th>
						<th align="center" rowspan="2">Actividad</th>
						<th align="center" rowspan="2">Detalle Actividad</th>
						<th align="center" colspan="3">Detalle Montos</th>
						<th align="center" rowspan="2">Estado</th>
						 
					</tr>
					<tr>
						<th align="center">Viaticos</th>
						<th align="center">Pasajes</th>
						<th align="center">Alojamiento</th>
					</tr>
				</thead>
				<tbody>
					
					';
				if($viatico->num_rows()>0){
				foreach ($viatico->result() as $viaticos) {
				
					$estado = $this->Reportes_viaticos_model->obtenerDetalleEstado($viaticos->estado);
					foreach ($estado->result() as $estado_detalle) {}
					$actividad = $this->Reportes_viaticos_model->obtenerDetalleActividad($viaticos->id_actividad_realizada);
					foreach ($actividad->result() as $actividad_detalle) {}
					$totales = $this->Reportes_viaticos_model->obtenerTotalMontos($viaticos->id_mision_oficial);
					foreach ($totales->result() as $totales_detalle) {}
						
					$cuerpo .= '
						<tr>
							<td>'.date('d-m-Y',strtotime($viaticos->fecha_solicitud)).'</td>
							<td>'.date('d-m-Y',strtotime($viaticos->fecha_mision_inicio)).'</td>
							<td>'.date('d-m-Y',strtotime($viaticos->fecha_mision_fin)).'</td>
							<td>'.($actividad_detalle->nombre_vyp_actividades).'</td>
							<td >'.utf8_decode($viaticos->detalle_actividad).'</td>
							<td>$'.number_format($totales_detalle->viatico,2,".",",").'</td>
							<td>$'.number_format($totales_detalle->pasaje,2,".",",").'</td>
							<td>$'.number_format($totales_detalle->alojamiento,2,".",",").'</td>
							<td>'.ucwords($estado_detalle->nombre_estado).'</td>
						</tr>
						';
					
					}
				}else{
					$cuerpo .= '
						<tr><td colspan="9"><center>No hay registros</center></td></tr>
					';
				}
				$cuerpo .= '
					
				</tbody>
			</table>

        ';         // LOAD a stylesheet         
        $stylesheet = file_get_contents(base_url().'assets/plugins/bootstrap/css/bootstrap.min.css');
		$this->mpdf->AddPage('L','','','','',10,10,35,17,3,9);
		$this->mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this iscss/style only and no body/html/text         
		$this->mpdf->WriteHTML($cuerpo);

		$this->mpdf->Output();
	}

	public function reporte_monto_viatico_mayor_a_menor($anio,$dir){
		$this->load->library('pdf');

		$this->load->model('Reportes_viaticos_model');
		$this->pdf = new Pdf('P','mm','Letter');
		$this->pdf->SetTituloPagina('MINISTERIO DE TRABAJO Y PREVISION SOCIAL','UNIDAD FINANCIERA INSTITUCIONAL','FONDO CIRCULANTE DEL MONTO FIJO');
		$this->pdf->SetTituloTabla1("NR");
		$this->pdf->SetTituloTabla2("NOMBRE EMPLEADO");
		$this->pdf->SetTituloTabla3("PASAJES");
		$this->pdf->SetTituloTabla4("VIÁTICOS");
		$this->pdf->SetTituloTabla5("TOTAL");
		$this->pdf->SetTitle(utf8_decode('VIÁTICOS DE MAYOR A MENOR'));
		$this->pdf->SetAutoPageBreak(true, 15);
	 $this->pdf->SetMargins(9,3,6);
	 $this->pdf->SetCuadros("monto_viatico_mayor_a_menor");
		$this->pdf->AddPage();

		$datos = array('dir'=>$dir);
		$datoSeccion = $this->Reportes_viaticos_model->obtenerNombreSeccion($datos);
		foreach ($datoSeccion->result() as $datoSeccionNombre) {}
		$this->pdf->Text(9,24,utf8_decode("VIÁTICOS POR EMPLEADO DE MAYOR A MENOR MONTO") ,0,'C', 0);
		$this->pdf->Text(9,28,utf8_decode("AÑO: ").$anio."          ".utf8_decode("SECCIÓN: ").utf8_decode($datoSeccionNombre->nombre_seccion) ,0,'C', 0);


		 $this->pdf->SetAligns(array('L','J','R','R','R'));
		$this->pdf->SetWidths(array(20,89,24,24,24));

		$data  =array(
			'anio'=> $anio,
			'dir' => $dir
		);
		$viatico = $this->Reportes_viaticos_model->obtenerViaticoMayoraMenor($data);

		if($viatico->num_rows()>0){
				foreach ($viatico->result() as $viaticos) {
						$this->pdf->Row(
						array($viaticos->nr_empleado,utf8_decode(ucfirst($viaticos->nombre_completo)),"$ ".number_format($viaticos->pasajes,2,".",","),"$ ".number_format($viaticos->viaticos,2,".",","),"$ ".number_format($viaticos->total,2,".",",")),
						array('0','0','0','0','0'),
						array(array('Arial','','9'),array('','',''),array('','',''),array('','',''),array('','','')),
						array(false,false,false,false,false,false),
						array(array('0','0','0'),array('0','0','0'),array('0','0','0'),array('0','0','0'),array('0','0','0')),
						array(array('255','211','0'),array('33','92','19'),array('192','10','2'),array('192','10','2'),array('192','10','2')));

						$this->pdf->Ln(1);

				}
			}else{
				$this->pdf->Text($this->pdf->GetX()+25,$this->pdf->GetY()+10,"Sin Registros",0,'C', 0);
			}

	 $this->pdf->Output(); //Salida al navegador
	}

	public function mostrarCombo($id){
		$nuevo['id_seccion']=$id;
		$this->load->view('informes/comboSecciones',$nuevo);
	}
	public function mostrarCombo2($id){
		$nuevo['id_seccion']=$id;
		$this->load->view('informes/comboSecciones2',$nuevo);
	}
	public function mostrarCombo3($id){
		$nuevo['id_seccion']=$id;
		$this->load->view('informes/comboSecciones3',$nuevo);
	}
	public function mostrarCombo4($id){
		$nuevo['id_seccion']=$id;
		$this->load->view('informes/comboSecciones4',$nuevo);
	}

	public function reporte_viaticos_por_periodo($anio,$primer_mes,$segundo_mes,$tercer_mes,$cuarto_mes,$quinto_mes,$sexto_mes){
		$sumaPasajes=0;$sumaViaticos=0;$sumaTotal=0;
		$this->load->library('pdf');

		$this->load->model('Reportes_viaticos_model');
		$this->pdf = new Pdf('P','mm','Letter');
		$this->pdf->SetTituloPagina('MINISTERIO DE TRABAJO Y PREVISION SOCIAL','UNIDAD FINANCIERA INSTITUCIONAL','FONDO CIRCULANTE DEL MONTO FIJO');

		$this->pdf->SetTituloTabla1("MES");
		$this->pdf->SetTituloTabla2("CONCEPTO DE GASTO");
		$this->pdf->SetTituloTabla3("PASAJES");
		$this->pdf->SetTituloTabla4("VIÁTICOS");
		$this->pdf->SetTituloTabla5("TOTAL");
		$this->pdf->SetTitle(utf8_decode('VIATICOS POR PERIODO'));
		$this->pdf->SetAutoPageBreak(true, 15);
	 $this->pdf->SetMargins(12,3,6);
	 $this->pdf->SetCuadros("monto_por_periodo");
		$this->pdf->AddPage();

		$this->pdf->Text(12,26,utf8_decode("PAGO DE VIÁTICOS POR COMISIÓN INTERNA Y PASAJES AL INTERIOR CORRESPONDIENTE AL: ").$anio ,0,'C', 0);
		//$this->pdf->Text(12,28,utf8_decode("AÑO: ").$anio ,0,'C', 0);
		 $this->pdf->SetAligns(array('C','C','R','R','R'));
		$this->pdf->SetWidths(array(31,80,22,22,22));

		$data  =array(
			'anio'=> $anio,
			'primer_mes'=>$primer_mes,
			'segundo_mes'=>$segundo_mes,
			'tercer_mes'=>$tercer_mes,
			'cuarto_mes'=>$cuarto_mes,
			'quinto_mes'=>$quinto_mes,
			'sexto_mes'=>$sexto_mes
		);
		$viatico = $this->Reportes_viaticos_model->obtenerViaticosPorPeriodo($data);

		if($viatico->num_rows()>0){
				foreach ($viatico->result() as $viaticos) {
					if($viaticos->mes=="1")$mes="Enero";
					else if($viaticos->mes=="2")$mes="Febrero";
					else if($viaticos->mes=="3")$mes="Marzo";
					else if($viaticos->mes=="4")$mes="Abril";
					else if($viaticos->mes=="5")$mes="Mayo";
					else if($viaticos->mes=="6")$mes="Junio";
					else if($viaticos->mes=="7")$mes="Julio";
					else if($viaticos->mes=="8")$mes="Agosto";
					else if($viaticos->mes=="9")$mes="Septiembre";
					else if($viaticos->mes=="10")$mes="Octubre";
					else if($viaticos->mes=="11")$mes="Noviembre";
					else if($viaticos->mes=="12")$mes="Diciembre";

					$sumaPasajes = $sumaPasajes + $viaticos->pasajes;
					$sumaViaticos = $sumaViaticos + $viaticos->viaticos;
					$sumaTotal = $sumaTotal+ $viaticos->total;
						$this->pdf->Row(
						array(strtoupper($mes),utf8_decode("VIÁTICOS POR COMISIÓN INTERNA Y PASAJES AL INTERIOR"),"$ ".number_format($viaticos->pasajes,2,".",","),"$ ".number_format($viaticos->viaticos,2,".",","),"$ ".number_format($viaticos->total,2,".",",")),
						array('0','0','0','0','0'),
						array(array('Arial','','9'),array('','',''),array('','',''),array('','',''),array('','','')),
						array(false,false,false,false,false,false),
						array(array('0','0','0'),array('0','0','0'),array('0','0','0'),array('0','0','0'),array('0','0','0')),
						array(array('255','211','0'),array('33','92','19'),array('192','10','2'),array('192','10','2'),array('192','10','2')));
						$this->pdf->Ln(1);
				}
				$this->pdf->Text(12,$this->pdf->GetY(),"_____________________________________________________________________________________________________",0,'C', 0);
				$this->pdf->SetAligns(array('C','C','R','R','R'));
	 		$this->pdf->SetWidths(array(111,22,22,22));$this->pdf->Ln(1);
				$this->pdf->Row(
				array(utf8_decode("TOTALES"),"$ ".number_format($sumaPasajes,2,".",","),"$ ".number_format($sumaViaticos,2,".",","),"$ ".number_format($sumaTotal,2,".",",")),
				array('LR','0','0','0'),
				array(array('Arial','B','9'),array('Arial','B','9'),array('Arial','B','9'),array('Arial','B','9'),),
				array(true,false,false,false,false,false),
				array(array('0','0','0'),array('0','0','0'),array('0','0','0'),array('0','0','0')),
				array(array('255','255','255'),array('33','92','19'),array('192','10','2'),array('192','10','2')));
				$this->pdf->SetFont('Arial','',9);
				$this->pdf->Text(12,$this->pdf->GetY(),"_____________________________________________________________________________________________________",0,'C', 0);
			}else{
				$this->pdf->Text($this->pdf->GetX()+35,$this->pdf->GetY()+10,"Sin Registros",0,'C', 0);
			}

	 $this->pdf->Output(); //Salida al navegador
	}
}
?>

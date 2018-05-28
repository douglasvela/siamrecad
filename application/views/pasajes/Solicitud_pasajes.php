
<?php
    $mantenimiento = false;
    if($mantenimiento){
        header("Location: ".site_url()."/mantenimiento");
        exit();
    }

    $user = $this->session->userdata('usuario_viatico');

    $nr = $this->db->query("SELECT * FROM org_usuario WHERE usuario = '".$user."' LIMIT 1");
    $nr_usuario = "";
    if($nr->num_rows() > 0){
        foreach ($nr->result() as $fila) { 
            $nr_usuario = $fila->nr; 
        }
    }
?>
<!DOCTYPE html>
<html>
<head>
	<script type="text/javascript">
		function objetoAjax(){
	        var xmlhttp = false;
	        try {
	            xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
	        } catch (e) {
	            try { xmlhttp = new ActiveXObject("Microsoft.XMLHTTP"); } catch (E) { xmlhttp = false; }
	        }
	        if (!xmlhttp && typeof XMLHttpRequest!='undefined') { xmlhttp = new XMLHttpRequest(); }
	        return xmlhttp;
	    }
	    function iniciar(){

	    }
	    function tabla_pasaje_unidad(){ 
	    	var fechas = $("#fecha1").val();
	    	var nr = $("#nr").val();
	        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttpB=new XMLHttpRequest();
	        }else{// code for IE6, IE5
	            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
	        }
	        xmlhttpB.onreadystatechange=function(){
	            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
	                document.getElementById("cnt_pasaje").innerHTML=xmlhttpB.responseText;
	                 $('[data-toggle="tooltip"]').tooltip();
	              
	                $('#myTable').DataTable();
	            }
	        }
	        xmlhttpB.open("GET","<?php echo site_url(); ?>/pasajes/pasaje/tabla_pasaje_unidad?nr="+nr+"&fecha1="+fechas, true);
	        xmlhttpB.send(); 
	   
		}
	function combo_oficina_departamento(tipo){
        
        var newName = 'Otro nombre',
        xhr = new XMLHttpRequest();

        xhr.open('GET', "<?php echo site_url(); ?>/pasajes/Lista_pasaje/combo_oficinas_departamentos1?");
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText !== newName) {
                document.getElementById("combo_departamento").innerHTML = xhr.responseText;
                // document.getElementById("combo_departamento1").innerHTML = xhr.responseText;
                //$(".select2").select2();
                
                    $('#departamento').val('').trigger('change.select2');
                 
                    combo_municipio();
                
            }else if (xhr.status !== 200) {
                swal({ title: "Ups! ocurrió un Error", text: "Al parecer no todos los objetos se cargaron correctamente por favor recarga la página e intentalo nuevamente", type: "error", showConfirmButton: true });
            }
        };
        xhr.send(encodeURI('name=' + newName));
    }

    function combo_municipio(tipo){     
        var id_departamento = $("#departamento").val();
        var newName = 'John Smith',

        xhr_m = new XMLHttpRequest();
        xhr_m.open('GET', "<?php echo site_url(); ?>/pasajes/Lista_pasaje/combo_municipios1?id_departamento="+id_departamento);
        xhr_m.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr_m.onload = function() {
            if (xhr_m.status === 200 && xhr_m.responseText !== newName) {
                document.getElementById("combo_municipio").innerHTML = xhr_m.responseText;
                // document.getElementById("combo_municipio1").innerHTML = xhr_m.responseText;
                $(".select2").select2();
                
                    $("#municipio").parent().show(0);
                  
                
            }
            else if (xhr_m.status !== 200) {
                swal({ title: "Ups! ocurrió un Error", text: "Al parecer no todos los objetos se cargaron correctamente por favor recarga la página e intentalo nuevamente", type: "error", showConfirmButton: true });
            }
        };
        xhr_m.send(encodeURI('name=' + newName));
    }
    function cambiar_nuevo(){

        $("#band").val("save");

        $("#ttl_form1").addClass("bg-success");
        $("#ttl_form1").removeClass("bg-info");

       // $("#btnadd").show(0);
       // $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_solicitud").show(0);
        
        $("#ttl_form1").children("h4").html("<span class='mdi mdi-plus'></span> Nueva Solicitud de Pasaje");
 		
    }
    function cerrar_mantenimiento1(){
    	$("#cnt_tabla").show(0);
        $("#cnt_solicitud").hide(0);
    }
    
    function mostrarform_detallado(){

    	combo_oficina_departamento();
    	$("#cnt_form").show(0);
        $("#cnt_solicitud").hide(0);
        $("#ttl_form2").children("h4").html("<span class='mdi mdi-plus'></span> Detalle de la solicitud");
        tabla_pasajes_detallado();
    }
    function cerrar_mantenimiento2(){
    	$("#cnt_form").hide(0);
        $("#cnt_solicitud").show(0);
    }
    	function tabla_pasajes_detallado(){ 
	    	 var nr_empleado = $("#nr_empleado").val();
	    	 var id_mision = 1;
	        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
	            xmlhttpPD=new XMLHttpRequest();
	        }else{// code for IE6, IE5
	            xmlhttpPD=new ActiveXObject("Microsoft.XMLHTTPB");
	        }
	        xmlhttpPD.onreadystatechange=function(){
	            if (xmlhttpPD.readyState==4 && xmlhttpPD.status==200){
	                document.getElementById("cnt_pasaje_detalle").innerHTML=xmlhttpPD.responseText;
	                 $('[data-toggle="tooltip"]').tooltip();
	              
	                $('#myTable_detallado').DataTable();
	            }
	        }
	        xmlhttpPD.open("GET","<?php echo site_url(); ?>/pasajes/pasaje/tabla_pasajes_detallado?nr_empleado="+nr_empleado+"&id_mision="+id_mision, true);
	        xmlhttpPD.send(); 
	   
		}
		function mantto_solicitud(){
			var formData = new FormData();
			if($("#nr_empleado").val()=="0" || !$("#fecha_solicitud").val()){
				swal({ title: "¡Ups! Error", text: "Campos requeridos", type: "error", showConfirmButton: true });
       			return;
			}
	        formData.append("nr_empleado", $("#nr_empleado").val());

	        var nombre=$('select[name="nr_empleado"] option:selected').text();
	        var nombre_ = nombre.split("-");
	        var nombre_completo = nombre_[0];
	        formData.append("nombre_completo", nombre_completo);
	        formData.append("fecha_solicitud", $("#fecha_solicitud").val());
	        formData.append("band", $("#band_solicitud").val());

	        var f_parts = $("#fecha_solicitud").val().split("-");
	        formData.append("mes_pasaje", f_parts[1]);
	        formData.append("anio_pasaje", f_parts[2]);


	        $.ajax({
	            url: "<?php echo site_url(); ?>/pasajes/Pasaje/gestionar_pasaje",
	            type: "post",
	            dataType: "html",
	            data: formData,
	            cache: false,
	            contentType: false,
	            processData: false
	        })
	        .done(function(res){

	            if(res == "exito"){
	                if($("#band").val() == "save"){
	                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
	                    mostrarform_detallado();
	                }else if($("#band").val() == "edit"){
	                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
	                }else{
	                    swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
	                }
	            }else if (res=="duplicado"){
	                swal({ title: "¡Ups! Error", text: "Ruta ya esta registrada.", type: "error", showConfirmButton: true });
	            }else{
	                alert(res)
	                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
	            }
	        });
		}
	</script>
</head>
<body>

	<div class="page-wrapper">
	    <div class="container-fluid">
	        <button id="notificacion" style="display: none;" class="tst1 btn btn-success2">Info Message</button>

	        <div class="row page-titles">
	            <div class="align-self-center" align="center">
	                <h3 class="text-themecolor m-b-0 m-t-0">Solicitud de Pasajes</h3>
	            </div>
	        </div>
	         <div class="row ">
	            <div class="col-lg-12" id="cnt_tabla" style="display: block;">
	                <div class="card">
	                    <div class="card-header bg-success2" id="">
	                        <h4 class="card-title m-b-0 text-white">Listado de Solicitudes de Pasajes</h4>
	                    </div>
	                    <div class="card-body b-t">
	                    	 <div class="pull-right">
	                            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2"><span class="mdi mdi-plus"></span> Nuevo registro</button>
	                        </div>
	                    	<div class="row ">
								<div class="form-group col-lg-6">
	                                <h5 style="display:none">Solicitante: <span class="text-danger">*</span></h5>
	                                <select id="nr" name="nr" class="select2" style="width: 100%" required onchange="tabla_pasaje_unidad()">
	                                <option value=''>[Elija el empleado]</option>
	                                <?php
	                                    $dataEmpleado2 = $this->db->query("SELECT e.id_empleado, e.nr as nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
	                                        if($dataEmpleado2->num_rows() > 0){
	                                            foreach ($dataEmpleado2->result() as $fila2) {
	                                            	if($nr_usuario == $fila2->nr){
	                                 ?>
	<option class="m-l-50" selected value="<?php echo $fila2->nr; ?>" ><?php echo preg_replace ('/[ ]+/', ' ',$fila2->nombre_completo.' - '.$fila2->nr) ?></option>
	                                <?php
	                                				}else{
	                                ?>
	<option class="m-l-50" value="<?php echo $fila2->nr; ?>"><?php echo preg_replace ('/[ ]+/', ' ',$fila2->nombre_completo.' - '.$fila2->nr) ?></option>
	                                <?php

	                               
	                            }
	                                		}		
	                                         	
	                                    }
	                                    //$u_rec_id = $this->session->userdata('rec_id');
	                                ?>
	                                </select>
	                            </div>
	                            <div class="form-group col-lg-3">
	                            	<h5 style="display:none">Fecha: <span class="text-danger">*</span></h5>
	                            	<input type="month"  class="form-control" id="fecha1" name="fecha1"  onchange="tabla_pasaje_unidad();">
	                            </div>
	                            
	                        </div>
	                        
	                        <div id="cnt_pasaje">Seleccione un solicitante y una fecha</div>
	                    </div>
	                    
	                </div>
	            </div>
	        </div>


	        <div class="row justify-content-center">

	            <div class="col-lg-12" id="cnt_solicitud" style="display: none;">
	                <div class="card">
	                    <div class="card-header bg-success2" id="ttl_form1">
	                         
	                        <h4 class="card-title m-b-0 text-white"></h4>
	                    </div>
	                    <div class="card-body b-t">
	                    	<div class="row ">
	                    		<div class="form-group col-lg-6">
	                    			<input type="text" id="band_solicitud" name="band_solicitud" value="save">
		                               <label for="" class="font-weight-bold">Solicitante: <span class="text-danger">*</span></label>
		                                <select id="nr_empleado" name="nr_empleado" class="select2" style="width: 100%" required >
		                                <option value='0'>[Elija el empleado]</option>
		                                <?php
		                                    $dataEmpleado2 = $this->db->query("SELECT e.id_empleado, e.nr as nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo FROM sir_empleado AS e WHERE e.id_estado = '00001' ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
		                                        if($dataEmpleado2->num_rows() > 0){
		                                            foreach ($dataEmpleado2->result() as $fila2) {
		                                            	if($nr_usuario == $fila2->nr){
		                                 ?>
		<option class="m-l-50" selected value="<?php echo $fila2->nr; ?>" ><?php echo preg_replace ('/[ ]+/', ' ',$fila2->nombre_completo.' - '.$fila2->nr) ?></option>
		                                <?php
		                                				}else{
		                                ?>
		<option class="m-l-50" value="<?php echo $fila2->nr; ?>"><?php echo preg_replace ('/[ ]+/', ' ',$fila2->nombre_completo.' - '.$fila2->nr) ?></option>
		                                <?php

		                               
		                            }
		                                		}		
		                                         	
		                                    }
		                                    //$u_rec_id = $this->session->userdata('rec_id');
		                                ?>
		                                </select>
		                             
	                    		</div>
	                    		<div class="form-group col-lg-3">
	                    			<label for="" class="font-weight-bold">Fecha Solicitud: <span class="text-danger">*</span></label>
	                    			 
	                    			<input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_solicitud" name="fecha_solicitud" placeholder="dd/mm/yyyy" onchange="">
                                    <div class="help-block"></div>
	                    		</div>
	                    		
	                    	</div>
	                    	  <div class="pull-left">
                               <button type="button" onclick="cerrar_mantenimiento1();" class="btn waves-effect waves-light"><span class="mdi mdi-keyboard-return"></span> Volver</button>
                            </div>
	                    	<div class="pull-right">
	                            <button type="button" onclick="mantto_solicitud();" class="btn waves-effect waves-light btn-success"><span class="mdi mdi-plus"></span> Continuar</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>

			
			<div class="row justify-content-center">

	            <div class="col-lg-12" id="cnt_form" style="display: none;">
	                <div class="card">
	                    <div class="card-header bg-success2" id="ttl_form2">
	                         
	                        <h4 class="card-title m-b-0 text-white"></h4>
	                    </div>
	                    <div class="card-body b-t">
	                    	<div class="row ">
	                    		<div class="form-group col-lg-3">
	                    			<label for="fecha_detalle" class="font-weight-bold">Fecha: <span class="text-danger">*</span></label>
	                    			 
	                    			<input type="text" pattern="\d{1,2}-\d{1,2}-\d{4}" required="" class="form-control" id="fecha_detalle" name="fecha_detalle" placeholder="dd/mm/yyyy" onchange="">
                                    <div class="help-block"></div>
	                    		</div>
	                    		
	                    		<div class="form-group col-lg-3">
	                    			<div class="" id="combo_departamento" ></div> 
	                    		</div>
	                    		<div class="form-group col-lg-3">
	                    			<div class="" id="combo_municipio" ></div>
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="form-group col-lg-3">
	                    			<label for="empresa" class="font-weight-bold">Nombre de la Empresa: <span class="text-danger">*</span></label>
	                    			<input type="text" id="empresa" name="empresa" class="form-control" required="" data-validation-required-message="Este campo es requerido" placeholder="Escriba Nombre de la empresa"  > 
	                    		</div>
	                    		<div class="form-group col-lg-6">
	                    			<label for="direccion" class="font-weight-bold">Dirección de la Empresa: <span class="text-danger">*</span></label>
	                    			 <input type="text"  id="direccion" name="direccion" class="form-control" required="" placeholder="Escriba la dirección" minlength="3" data-validation-required-message="Este campo es requerido">
	                    		</div>
	                    		<div class="form-group col-lg-3">
	                    			<label for="expediente" class="font-weight-bold">No de Expediente: <span class="text-danger">*</span></label>
	                    			<input type="text" id="expediente" name="expediente" class="form-control" placeholder="Escriba No de expediente"  >
	                    		</div>
	                    	</div>
	                    	<div class="row">
	                    		<div class="form-group col-lg-3">
	                    			<label for="id_actividad" class="font-weight-bold">Nombre de la Actividad: <span class="text-danger">*</span></label>
	                    			<select id="id_actividad" name="id_actividad" class="select2" required=''  style="width: 100%" >
					                <option value=''>[Elija una actividad]</option>
					                <?php 
					                  $actividad = $this->db->query("SELECT * FROM vyp_actividades WHERE depende_vyp_actividades = 0 OR depende_vyp_actividades = '' OR depende_vyp_actividades IS NULL");
					                  if($actividad->num_rows() > 0){
					                    foreach ($actividad->result() as $filaa) {              
					                      echo '<option class="m-l-50" value="'.$filaa->id_vyp_actividades.'">'.$filaa->nombre_vyp_actividades.'</option>';
					                      $activida_sub = $this->db->query("SELECT * FROM vyp_actividades WHERE depende_vyp_actividades = '".$filaa->id_vyp_actividades."'");
					                      if($activida_sub->num_rows() > 0){
					                        foreach ($activida_sub->result() as $filasub) {              
					                          echo '<option class="m-l-50" value="'.$filasub->id_vyp_actividades.'"> &emsp;&#x25B6; '.$filasub->nombre_vyp_actividades.'</option>';
					                        }
					                      }
					                    }
					                  }
					                ?>
					              </select>
	                    		</div>
	                    		<div class="form-group col-lg-3">
	                    			<label for="monto" class="font-weight-bold">Monto: <span class="text-danger">*</span></label>
	                    			<input type="text" id="monto" name="monto" class="form-control" required="" data-validation-required-message="Este campo es requerido" placeholder="Digite el monto de pasaje" >
	                    		</div>
	                    	</div>
	                    	<div id="cnt_pasaje_detalle"></div>
	                    	<div class="pull-left">
                               <button type="button" onclick="cerrar_mantenimiento2();" class="btn waves-effect waves-light"><span class="mdi mdi-keyboard-return"></span> Volver</button>
                            </div>
                            <div class="pull-right">
	                            <button type="button" onclick="" class="btn waves-effect waves-light btn-success"><span class="mdi mdi-plus"></span> Actualizar solicitud</button>
	                        </div>
	                    </div>
	                </div>
	            </div>
	        </div>








	    </div>
	</div>


</body>
</html>
 
<script>
	$(function(){
		$('#fecha_solicitud').datepicker({
	        format: 'dd-mm-yyyy',
	        autoclose: true,
	        todayHighlight: true,
	        daysOfWeekDisabled: [0,6]
        });
	});
</script>
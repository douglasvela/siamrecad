<?php
// Características del navegador
$ua=$this->config->item("navegator");
$navegatorless = false;
if(floatval($ua['version']) < $this->config->item("last_version")){
    $navegatorless = true;
}

$rango_consulta = obtener_rango($segmentos='2', $permiso='1');
$rango_registro = obtener_rango($segmentos='2', $permiso='2');

$nr_usuario = $this->session->userdata('nr_usuario_viatico'); 

?>
<script type="text/javascript">
    function cambiar_editar(id,descripcion,hora_inicio,hora_fin,monto,tipo,id_categoria,id_viatico_restric,estado,bandera){
        $("#idhorario").val(id);
        $("#descripcion").val(descripcion);
        $("#hora_inicio").val(hora_inicio);
        $("#hora_fin").val(hora_fin);
        $("#monto").val(monto);
        $("#estado").val(estado);
        $("#id_tipo").val(tipo);

        $( "#cnt_combo_viatico_hora" ).load("<?php echo site_url(); ?>/configuraciones/horarios/combo_viatico_hora?id_tipo="+tipo, function() {
            $("#id_categoria").val(id_categoria);
             $("#id_viatico_restriccion").val(id_viatico_restric);
        });


        if(bandera == "edit"){
            $("#ttl_form").removeClass("bg-success");
            $("#ttl_form").addClass("bg-info");
            $("#btnadd").hide(0);
            $("#btnedit").show(0);
            $("#cnt_tabla").hide(0);
            $("#cnt_form").show(0);
            $("#ttl_form").children("h4").html("<span class='fa fa-wrench'></span> Editar solicitud de viático");
        }else{
            eliminar_horario(estado, tipo);
        }
    }

    function cambiar_nuevo(){
        $("#idhorario").val("");
        $("#descripcion").val("");
        $("#hora_inicio").val("");
        $("#hora_fin").val("");
        $("#monto").val("");
        $("#estado").val("1");
        $("#id_tipo").val("1");
        $("#band").val("save");

        $("#ttl_form").addClass("bg-success");
        $("#ttl_form").removeClass("bg-info");

        $("#btnadd").show(0);
        $("#btnedit").hide(0);

        $("#cnt_tabla").hide(0);
        $("#cnt_form").show(0);
        informacion_empleado();

        $("#ttl_form").children("h4").html("<span class='mdi mdi-plus'></span> Nueva solicitud de viático");
    }

    function cerrar_mantenimiento(){
        $("#cnt_tabla").show(0);
        $("#cnt_form").hide(0);
    }

    function editar_horario(){
        $("#band").val("edit");
        $("#submit").click();
    }

    function eliminar_horario(estado, t){
        $("#band").val("delete");
        if(t == 1){
            var tipo = "el viático seleccionado";
        }else{
            var tipo = "la restricción seleccionada";
        }
        if(estado == 1){
            var text = "Desea desactivar "+tipo;
            var title = "¿Dar de baja?";
        }else{
            var text = "Desea activar "+tipo;
            var title = "¿Activar?";
        }
        
        swal({   
            title: title,   
            text: text,   
            type: "warning",   
            showCancelButton: true,   
            confirmButtonColor: "#fc4b6c",   
            confirmButtonText: "Sí, continuar",
            closeOnConfirm: false 
        }, function(){
            if(estado == 1){
                $.when( $("#estado").val("0") ).then( $("#submit").click() );
            }else{
                $.when( $("#estado").val("1") ).then( $("#submit").click() );
            }
        });
    }

    function iniciar(){
        <?php if(tiene_permiso($segmentos=2,$permiso=1)){ ?>
        tablahorarios();        
        <?php }else{ ?>
            $("#cnt_tabla").html("Usted no tiene permiso para este formulario.");     
        <?php } ?>
    }

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

    function tablahorarios(){
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttpB=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttpB=new ActiveXObject("Microsoft.XMLHTTPB");
        }
        xmlhttpB.onreadystatechange=function(){
            if (xmlhttpB.readyState==4 && xmlhttpB.status==200){
                document.getElementById("cnt_tabla").innerHTML=xmlhttpB.responseText;
                $('[data-toggle="tooltip"]').tooltip();
                $('#myTable').DataTable();
            }
        }
        xmlhttpB.open("GET","<?php echo site_url(); ?>/configuraciones/horarios/tabla_horarios/",true);
        xmlhttpB.send();
    }


    function informacion_empleado(){
        var nr_usuario = $("#nr").val();
        if(window.XMLHttpRequest){// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp_municipio=new XMLHttpRequest();
        }else{// code for IE6, IE5
            xmlhttp_municipio=new ActiveXObject("Microsoft.XMLHTTPB");
        }

        xmlhttp_municipio.onreadystatechange=function(){
            if (xmlhttp_municipio.readyState==4 && xmlhttp_municipio.status==200){
                  document.getElementById("cnt_informacion_empleado").innerHTML=xmlhttp_municipio.responseText;
                  tabla_empresas_visitadas();
            }
        }
        xmlhttp_municipio.open("GET","<?php echo site_url(); ?>/viaticos/solicitud_admin/informacion_empleado?nr_usuario="+nr_usuario,true);
        xmlhttp_municipio.send();
    }

    function tabla_empresas_visitadas(callback){
        var id_mision = $("#id_mision").val();
        var nr = $("#nr").val();    
        var newName = 'John Smith',
        xhr = new XMLHttpRequest();
        xhr.open('GET', "<?php echo site_url(); ?>/viaticos/solicitud_admin/tabla_empresas_visitadas?id_mision="+id_mision+"&nr="+nr);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onload = function() {
            if (xhr.status === 200 && xhr.responseText !== newName) {
                document.getElementById("cnt_tabla_empresas_visitadas").innerHTML = xhr.responseText;
            }else if (xhr.status !== 200) {
                swal({ title: "Ups! ocurrió un Error", text: "Al parecer la tabla de empresas visitadas no se cargó correctamente por favor recarga la página e intentalo nuevamente", type: "error", showConfirmButton: true });
            }
        };
        xhr.send(encodeURI('name=' + newName));
    }

</script>

<!-- ============================================================== -->
<!-- Inicio de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->
<div class="page-wrapper">
    <div class="container-fluid">
        <!-- ============================================================== -->
        <!-- TITULO de la página de sección -->
        <!-- ============================================================== -->
        <div class="row page-titles">
            <div class="align-self-center" align="center">
                <h3 class="text-themecolor m-b-0 m-t-0">Gestión de solicitudes de viáticos y pasajes recibidas en físico</h3>
            </div>
        </div>
        <!-- ============================================================== -->
        <!-- Fin TITULO de la página de sección -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Inicio del CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
        <div class="row" <?php if($navegatorless){ echo "style='margin-right: 80px;'"; } ?>>
            <!-- ============================================================== -->
            <!-- Inicio del FORMULARIO de gestión -->
            <!-- ============================================================== -->
            <div class="col-lg-1"></div>
            <div class="col-lg-10" id="cnt_form" style="display: none;">
                <div class="card">
                    <div class="card-header bg-success2" id="ttl_form">
                        <div class="card-actions text-white">
                            <a style="font-size: 16px;" onclick="cerrar_mantenimiento();"><i class="mdi mdi-window-close"></i></a>
                        </div>
                        <h4 class="card-title m-b-0 text-white">Listado de solicitudes de viáticos</h4>
                    </div>
                    <div class="card-body b-t">
                        
                        <?php echo form_open('', array('id' => 'formajax', 'style' => 'margin-top: 0px;', 'class' => 'm-t-40', 'autocomplete' => 'off')); ?>
                            <input type="hidden" id="band" name="band" value="save">
                            <input type="hidden" id="id_solicitud_viatico" name="id_solicitud_viatico" value="">
                            <input type="hidden" id="estado" name="estado" value="1">
                            <div class="row">
                                <div class="form-group col-lg-6 <?php if($navegatorless){ echo "pull-left"; } ?>"> 
			                        <h5>Persona solicitante: <span class="text-danger">*</span></h5>                           
			                        <select id="nr" name="nr" class="select2" style="width: 100%" required="" onchange="informacion_empleado();">
			                            <option value="">[Elija el empleado]</option>
			                            <?php
			                                $otro_empleado = $this->db->query("SELECT e.id_empleado, e.nr, UPPER(CONCAT_WS(' ', e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada)) AS nombre_completo, ei.id_empleado_informacion_laboral FROM sir_empleado AS e JOIN sir_empleado_informacion_laboral AS ei ON e.id_empleado = ei.id_empleado AND ei.id_empleado_informacion_laboral = (SELECT MAX(i2.id_empleado_informacion_laboral) FROM sir_empleado_informacion_laboral AS i2 WHERE e.id_empleado = i2.id_empleado) AND e.id_estado = '00001' GROUP BY e.id_empleado ORDER BY e.primer_nombre, e.segundo_nombre, e.tercer_nombre, e.primer_apellido, e.segundo_apellido, e.apellido_casada");
			                                if($otro_empleado->num_rows() > 0){
			                                    foreach ($otro_empleado->result() as $fila) {  
			                                    if($nr_usuario == $fila->nr){
			                                    	echo '<option class="m-l-50" value="'.$fila->nr.'" selected>'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
			                                    }else{
			                                    	echo '<option class="m-l-50" value="'.$fila->nr.'">'.preg_replace ('/[ ]+/', ' ', $fila->nombre_completo.' - '.$fila->nr).'</option>';
			                                    }         
			                                    }
			                                }
			                            ?>
			                        </select>
			                        <div class="help-block"></div>
			                    </div>
                            </div>
                            <div class="row">
                                <div class="col-lg-12" id="cnt_informacion_empleado"></div>
                            </div>

                            <div class="row" id="cnt_tabla_empresas_visitadas">
                                
                            </div>


                                <button id="submit" type="submit" style="display: none;"></button>
                                <div align="right" id="btnadd">
                                    <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                    <button type="submit" class="btn waves-effect waves-light btn-success2"><i class="mdi mdi-plus"></i> Guardar</button>
                                </div>
                                <div align="right" id="btnedit" style="display: none;">
                                    <button type="reset" class="btn waves-effect waves-light btn-success"><i class="mdi mdi-recycle"></i> Limpiar</button>
                                    <button type="button" onclick="editar_horario()" class="btn waves-effect waves-light btn-info"><i class="mdi mdi-pencil"></i> Editar</button>
                                </div>

                            </div>

                        <?php echo form_close(); ?>
                    </div>
                </div>

            <div class="col-lg-1"></div>
            <!-- ============================================================== -->
            <!-- Fin del FORMULARIO de gestión -->
            <!-- ============================================================== -->
            <!-- ============================================================== -->
            <!-- Inicio de la TABLA -->
            <!-- ============================================================== -->
            <div class="col-lg-12" id="cnt_tabla">
                     
            </div>
            
            </div>
            
            <!-- ============================================================== -->
            <!-- Fin de la TABLA -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- Fin CUERPO DE LA SECCIÓN -->
        <!-- ============================================================== -->
    </div> 
</div>
<!-- ============================================================== -->
<!-- Fin de DIV de inicio (ENVOLTURA) -->
<!-- ============================================================== -->

<script>

$(function(){     
    $("#formajax").on("submit", function(e){
        e.preventDefault();
        var f = $(this);
        var formData = new FormData(document.getElementById("formajax"));
        formData.append("dato", "valor");
        
        $.ajax({
            url: "<?php echo site_url(); ?>/configuraciones/horarios/gestionar_horarios",
            type: "post",
            dataType: "html",
            data: formData,
            cache: false,
            contentType: false,
            processData: false
        })
        .done(function(res){
            if(res == "exito"){
                cerrar_mantenimiento();
                if($("#band").val() == "save"){
                    swal({ title: "¡Registro exitoso!", type: "success", showConfirmButton: true });
                }else if($("#band").val() == "edit"){
                    swal({ title: "¡Modificación exitosa!", type: "success", showConfirmButton: true });
                }else{
                    swal({ title: "¡Borrado exitoso!", type: "success", showConfirmButton: true });
                }
                tablahorarios(<?php echo $this->uri->segment(4);?>);
            }else{
                swal({ title: "¡Ups! Error", text: "Intentalo nuevamente.", type: "error", showConfirmButton: true });
            }
        });
            
    });
});

</script>
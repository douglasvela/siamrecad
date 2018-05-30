<style type="text/css" media="screen">
  table {
  font-size: 80%;
}
</style>      
<div class="table-responsive">
    <table id="myTable_detallado" class="table table-hover product-overview" style="margin-bottom: 0px;">
        <thead class="bg-inverse text-white">
            <tr>
                <th>Fecha Misión</th>
                <th>Departamento</th>
                <th>Municipio</th>
                <th>No Expediente</th>
                <th>Empresa</th>
                <th>Direccion</th>
                <th>Monto</th>
                <th>Actividad</th>
                <th>(*)</th>
            </tr>
        </thead>
        <tbody>
        <?php
        $id_mision=$_GET['id_mision'];
        $nr_empleado=$_GET['nr_empleado'];
        /*list($otrafecha)= explode ("-",$fecha_mes); 
        $mes = $otrafecha[1];
        list($anio, $mes)= explode ("-",$fecha_mes);  
*/
      $cuenta = $this->db->query("SELECT * FROM vyp_pasajes AS p WHERE p.nr = '".$nr_empleado."' AND p.id_mision='".$id_mision."' ORDER BY p.id_solicitud_pasaje desc");
            if($cuenta->num_rows() > 0){
                foreach ($cuenta->result() as $fila) {
                  echo "<tr>";
                            echo "<td>".$fila->fecha_mision."</td>";
                            echo "<td>".$fila->id_departamento."</td>";
                            echo "<td>".$fila->id_municipio."</td>";
                            echo "<td>".$fila->no_expediente."</td>";
                            echo "<td>".$fila->empresa_visitada."</td>";
                            echo "<td>".$fila->direccion_empresa."</td>";
                            echo "<td>".$fila->monto_pasaje."</td>";
                            echo "<td>".$fila->id_actividad_realizada."</td>";
                    echo "<td>";
                   $array = array($fila->id_solicitud_pasaje,$fila->fecha_mision,$fila->id_departamento,$fila->id_municipio,$fila->no_expediente,$fila->empresa_visitada,$fila->direccion_empresa,$fila->monto_pasaje,$fila->id_actividad_realizada);
                    array_push($array, "edit");
                    echo generar_boton($array,"cambiar_detallado_editar","btn-info","fa fa-wrench","Editar");
                    unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                    echo generar_boton(array($fila->id_solicitud_pasaje),"eliminar_detallado_pasaje","btn-danger","fa fa-close","Eliminar");
                    echo "</td>";
                  echo "</tr>";
                }
            }else{
                echo "<tr>";
                    echo "<td colspan='9'>No hay registros de pasajes</td>";
                echo "</tr>";
            }
        ?>
        </tbody>
    </table>
</div>


<script>
$(function(){
    $(document).ready(function() {
        $('#myTable_detallado').DataTable();
    });
});
</script>
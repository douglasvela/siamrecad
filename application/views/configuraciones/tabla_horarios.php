<div class="card">
    <div class="card-header">
        <h4 class="card-title m-b-0">Listado de viáticos</h4>
    </div>
    <div class="card-body b-t" style="padding-top: 7px;">
        <div class="pull-right">
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip" title="Clic para agregar un nuevo registro"><span class="mdi mdi-plus"></span> Nuevo registro</button>
        </div>
        <div class="table-responsive">
            <table id="myTable" class="table table-hover product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>#</th>
                        <th>Descripción</th>
                        <th>Monto</th>
                        <th>Inicio</th>
                        <th>Fin</th>
                        <th>(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 

                    $horarios = $this->db->get("vyp_horario_viatico");

                    if(!empty($horarios)){
                        foreach ($horarios->result() as $fila) {
                          echo "<tr>";
                            echo "<td>".$fila->id_horario_viatico."</td>";
                            echo "<td>".$fila->descripcion."</td>";
                            echo "<td>$ ".number_format($fila->monto,2)."</td>";
                            echo "<td>".date("h:i A",strtotime($fila->hora_inicio))."</td>";
                            echo "<td>".date("h:i A",strtotime($fila->hora_fin))."</td>";
                           
                           
                            echo "<td>";
                            $array = array($fila->id_horario_viatico, $fila->descripcion, date("H:i",strtotime($fila->hora_inicio)), date("H:i",strtotime($fila->hora_fin)), number_format($fila->monto,2));
                            array_push($array, "edit");
                            echo generar_boton($array,"cambiar_editar","btn-info","fa fa-wrench","Editar");
                            unset($array[endKey($array)]); //eliminar el ultimo elemento de un array
                            array_push($array, "delete");
                            echo generar_boton($array,"cambiar_editar","btn-danger","fa fa-close","Eliminar");
                            echo "</td>";
                          echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
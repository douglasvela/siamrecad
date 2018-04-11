<div class="card">
    <div class="card-header">
        <h4 class="card-title m-b-0">Listado de polizas pendientes de pago</h4>
    </div>
    <div class="card-body b-t"  style="padding-top: 7px;">
    	<div class="pull-right">
            <button type="button" onclick="cambiar_nuevo();" class="btn waves-effect waves-light btn-success2" data-toggle="tooltip" title="Clic para agregar un nuevo registro"><span class="mdi mdi-plus"></span> Nuevo registro</button>
        </div>
        <div class="table-responsive">
            <table id="tabla_pendiente_pago" class="table table-hover product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th># poliza</th>
                        <th>Mes</th>
                        <th>Año</th> 
                        <th>Monto</th>
                        <th>Estado</th>
                        <th>(*)</th>
                        <th>Pagar</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    $poliza = $this->db->query("SELECT no_poliza, mes, mes_poliza, anio, SUM(total) AS total, estado, cod_presupuestario, nombre_banco, cuenta_bancaria FROM vyp_poliza WHERE estado = '0' GROUP BY no_poliza");
                    $contadorcbx = 0;
                    if($poliza->num_rows() > 0){
                        foreach ($poliza->result() as $fila) {
                        	$contadorcbx++;
                            echo "<tr>";
                            echo "<td>".$fila->no_poliza."</td>";
                            echo "<td>".$fila->mes_poliza."</td>";
                            echo "<td>".$fila->anio."</td>";
                            echo "<td>$ ".$fila->total."</td>";

                            if($fila->estado == 0){
                                echo '<td><span class="label label-danger">Revisión presupuestaria</span></td>';
                            }else if($fila->estado == 1){
                                echo '<td><span class="label label-success">Pagada</span></td>';
                            }

                            echo "<td>";
                            echo generar_boton(array($fila->no_poliza, $fila->mes_poliza, $fila->anio),"imprimir_poliza","btn-default","fa fa-print","Imprimir");
                            echo "</td>";

                            echo "<td>";
                            ?>
                            <input type="checkbox" id="<?php echo 'checkbox'.$contadorcbx; ?>" class="filled-in">
                            <label for="<?php echo 'checkbox'.$contadorcbx; ?>"></label>
                            <input type="hidden" value="<?php echo $fila->no_poliza; ?>">
                            <input type="hidden" value="<?php echo $fila->mes_poliza; ?>">
                            <input type="hidden" value="<?php echo $fila->anio; ?>">
                            <?php
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
<script>
    $(document).ready(function() {
        $('#myTable').DataTable();
    });
</script>
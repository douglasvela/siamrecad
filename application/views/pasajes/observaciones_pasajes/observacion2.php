<?php if(tiene_permiso($segmentos=3,$permiso=1)){ ?>
        <div class="table-responsive">
            <table id="myTable2" class="table table-hover product-overview">
                <thead class="bg-info text-white">
                    <tr>
                        <th>Id</th>
                        <th>Solicitante</th>
                        <th>Mes</th> 
                        <th>Año</th>
                        <th>(*)</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
            $user = $this->session->userdata('usuario_viatico');
            if(empty($user)){
                header("Location: ".site_url()."/login");
                exit();
            }

            $pos = strpos($user, ".")+1;
            $inicialUser = strtoupper(substr($user,0,1).substr($user, $pos,1));

            $nr = $this->db->query("SELECT * FROM org_usuario WHERE usuario = '".$user."' LIMIT 1");
            $nr_usuario = ""; $nombre_usuario;
            if($nr->num_rows() > 0){
                foreach ($nr->result() as $fila) { 
                    $nr_usuario = $fila->nr; 
                    $nombre_usuario = $fila->nombre_completo;
                }
            }

          $mision = $this->db->query("SELECT id_mision_pasajes, nr, nombre_empleado, mes_pasaje, anio_pasaje, estado FROM vyp_mision_pasajes where estado = 3 AND nr_jefe_regional = '".$nr_usuario."' ORDER BY mes_pasaje");
                   if($mision->num_rows() > 0){ 
               
                foreach ($mision->result() as $fila) {
                    echo "<tr>";
                    echo "<td>".$fila->id_mision_pasajes."</td>";
                    echo "<td>".$fila->nombre_empleado."</td>";

                   switch ($fila->mes_pasaje) {
                        case 1:
                            $month="Enero";
                            break;
                        case 2:
                            $month="Febrero";
                            break;
                        case 3:
                            $month="Marzo";
                            break;
                            case 4:
                            $month="Abril";
                            break;
                            case 5:
                            $month="Mayo";
                            break;
                            case 6:
                            $month="Junio";
                            break;
                            case 7:
                            $month="Julio";
                            break;
                            case 8:
                            $month="Agosto";
                            break;
                            case 9:
                            $month="Septiembre";
                            break;
                            case 10:
                            $month="Octubre";
                            break;
                            case 11:
                            $month="Noviembre";
                            break;
                            case 12:
                            $month="Diciembre";
                            break;
                    }


                   echo "<td>".$month."</td>";
                    echo "<td>".$fila->anio_pasaje."</td>";
                   
  $fecha=$fila->anio_pasaje .'-'. $fila->mes_pasaje;
                    echo "<td>";
                    $array = array($fila->nr, $fila->id_mision_pasajes, $fila->estado, $fecha);
                    if(tiene_permiso($segmentos=3,$permiso=4)){
                        echo generar_boton($array,"cambiar_mision","btn-info","fa fa-wrench","Revisar solicitud");
                    }
                    echo "</td>";

                   echo "</tr>";
                        }
                    }
                ?>
                </tbody>
            </table>
        
        <input type="hidden" id="numObservacion2" name="numObservacion2" value="<?php echo $mision->num_rows(); ?>">
    </div>
    <?php } ?>
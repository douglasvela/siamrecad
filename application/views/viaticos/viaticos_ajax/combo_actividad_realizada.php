<div class="form-group col-lg-12"> 
                                <h5>Actividad realizada: <span class="text-danger">*</span></h5>
                                <div class="input-group">
                                    <select id="actividad" name="actividad" class="select2" style="width: 100%" required=''>
                                        <option value=''>[Elija una opción o agregue una nueva]</option>
                                    <?php 
                                        $actividad = $this->db->query("SELECT * FROM vyp_actividades WHERE depende_vyp_actividades = 0 OR depende_vyp_actividades = '' OR depende_vyp_actividades IS NULL");
                                        if($actividad->num_rows() > 0){
                                            foreach ($actividad->result() as $filaa) {              
                                               echo '<option class="m-l-50" value="'.$filaa->id_vyp_actividades.'">'.$filaa->nombre_vyp_actividades.'</option>';
                                            }
                                        }
                                    ?>
                                    </select>
                                    <button type="button" class="input-group-addon btn btn-success2" onclick="nuevaActividad();">+</button>
                                </div> 
                                </div>
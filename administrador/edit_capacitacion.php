<script src="//ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<?php
$page_title = 'Editar Capacitación';
require_once('includes/load.php');

// page_require_level(4);
?>
<?php
$e_detalle = find_by_id_capacitacion((int)$_GET['id']);
$grupos_vuln = find_all('cat_grupos_vuln');
$grupos = find_all_grupos((int)$_GET['id']);
$areas_all = find_all('area');
if (!$e_detalle) {
    $session->msg("d", "id de capacitación no encontrado.");
    redirect('capacitaciones.php');
}
$user = current_user();
$nivel = $user['user_level'];

$id_user = $user['id_user'];

$existe= ($grupos?1:0);

?>

<?php
if (isset($_POST['edit_capacitacion'])) {
    $req_fields = array('nombre_capacitacion', 'quien_solicita', 'fecha', 'hora', 'lugar',  'modalidad', 'id_area', 'capacitador');
    validate_fields($req_fields);
    if (empty($errors)) {
        $id = (int)$e_detalle['id_capacitacion'];
        $nombre   = remove_junk($db->escape($_POST['nombre_capacitacion']));
        $solicita   = remove_junk($db->escape($_POST['quien_solicita']));
        $tipo_capacitacion   = remove_junk($db->escape($_POST['tipo_capacitacion']));
        $tipo_evento   = remove_junk($db->escape($_POST['tipo_evento']));
        $fecha   = remove_junk($db->escape($_POST['fecha']));
        $hora   = remove_junk($db->escape($_POST['hora']));
        $lugar   = remove_junk(($db->escape($_POST['lugar'])));
        $asistentes_otros   = remove_junk(($db->escape($_POST['asistentes_otros'])));
        $asistentes_nobinario   = remove_junk(($db->escape($_POST['asistentes_nobinario'])));
        $asistentes_mujeres   = remove_junk(($db->escape($_POST['asistentes_mujeres'])));
        $asistentes_hombres   = remove_junk(($db->escape($_POST['asistentes_hombres'])));
        $asistentes_10   = remove_junk(($db->escape($_POST['asistentes_10'])));
        $asistentes_20   = remove_junk(($db->escape($_POST['asistentes_20'])));
        $asistentes_30   = remove_junk(($db->escape($_POST['asistentes_30'])));
        $asistentes_40   = remove_junk(($db->escape($_POST['asistentes_40'])));
        $asistentes_50   = remove_junk(($db->escape($_POST['asistentes_50'])));
        $asistentes_60   = remove_junk(($db->escape($_POST['asistentes_60'])));
        $asistentes_70   = remove_junk(($db->escape($_POST['asistentes_70'])));
        $asistentes_80   = remove_junk(($db->escape($_POST['asistentes_80'])));
        $modalidad   = remove_junk($db->escape($_POST['modalidad']));
        $depto   = remove_junk($db->escape($_POST['id_area']));
        $capacitador   = remove_junk($db->escape($_POST['capacitador']));
	$asistentes = $_POST['asistentes'];
		$id_cat_grupo_vuln = $_POST['id_cat_grupo_vuln'];
        
         $sql = "UPDATE capacitaciones SET 
		 nombre_capacitacion='{$nombre}', 
		 tipo_capacitacion='{$tipo_capacitacion}', 
		 tipo_evento='{$tipo_evento}', 
		 modalidad='{$modalidad}', 
		 quien_solicita='{$solicita}', 
		 fecha='{$fecha}', hora='{$hora}', 
		 lugar='{$lugar}', 
		 id_area='{$depto}', 
		 capacitador='{$capacitador}', 
		 asistentes_otros='{$asistentes_otros}', 
		 asistentes_nobinario='{$asistentes_nobinario}', 
		 asistentes_mujeres='{$asistentes_mujeres}', 
		 asistentes_hombres='{$asistentes_hombres}', 
		 asistentes_10='{$asistentes_10}', 
		 asistentes_20='{$asistentes_20}', 
		 asistentes_30='{$asistentes_30}', 
		 asistentes_40='{$asistentes_40}', 
		 asistentes_50='{$asistentes_50}', 
		 asistentes_60='{$asistentes_60}', 
		 asistentes_70='{$asistentes_70}', 
		 asistentes_80='{$asistentes_80}' 
		 WHERE id_capacitacion='{$db->escape($id)}'";
        $result1 = $db->query($sql);
		      
        if ($result1 && $db->affected_rows() === 1) {		
            //$session->msg('s', "Información Actualizada ");
            insertAccion($user['id_user'], '"' . $user['username'] . '" editó capacitación, Folio: ' . $folio . '.', 2);
            //redirect('capacitaciones.php', false);
        } else {
            $session->msg('d', ' Lo siento no se actualizaron los datos.');
            //redirect('edit_capacitacion.php?id=' . $id, false);
        }
		
		$query = "DELETE FROM rel_capacitacion_grupos WHERE id_capacitacion =" . $id;
        $result = $db->query($query);
        
				for ($i = 0; $i < sizeof($asistentes); $i = $i + 1) {
										if($id_cat_grupo_vuln[$i] !== '' && $id_cat_grupo_vuln[$i] > 0){
						$queryInsert4 = "INSERT INTO rel_capacitacion_grupos (id_capacitacion,id_cat_grupo_vuln,no_asistentes) VALUES('$id','$id_cat_grupo_vuln[$i]','$asistentes[$i]')";					
						$db->query($queryInsert4);
					}
				}            
        
        $session->msg('s', "Información Actualizada ");
        redirect('capacitaciones.php', false);
		
    } else {
        $session->msg("d", $errors);
        redirect('edit_capacitacion.php?id=' . (int)$e_detalle['id'], false);
    }
}
?>

<script type="text/javascript">	
		
	$(document).ready(function() {
		
		
		$("#addRow").click(function() {	
			var html = '';
				html += '<div id="inputFormRow">';
				html += '	<div class="col-md-4">';
				html += '		 <input type="number"  class="form-control" max="10000" name="asistentes[]" > ';
				html += '	</div>';
				html += '	<div class="col-md-4">';
				html += '		<select class="form-control" name="id_cat_grupo_vuln[]">';
                html += '                <option value="">Escoge una opción</option>';
                               <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                html += '                   <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>';
                               <?php endforeach; ?>
                html += '            </select>';
				html += '	</div>';
				html += '	<div class="col-md-2">';
				html += '	<button type="button" class="btn btn-outline-danger" id="removeRow" > ';
				html += '   	<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">';
				html += '			<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>';
				html += '			<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>';
				html += '		</svg>';
				html += '  	</button>';			
				html += '	</div> <br><br>';
				html += '</div> ';

				$('#newRow').append(html);
		});
		
		
		$(document).on('click', '#removeRow', function() {
				$(this).closest('#inputFormRow').remove();
			});
	
	});
	
	
</script>
<?php include_once('layouts/header.php'); ?>
<?php echo display_msg($msg); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Editar capacitación <?php echo $e_detalle['folio']; ?></span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="edit_capacitacion.php?id=<?php echo (int)$e_detalle['id_capacitacion']; ?>" enctype="multipart/form-data">
                <div class="row">
				<div class="col-md-2">
                        <div class="form-group">
                            <label for="nombre_capacitacion">Tipo de Divulgación</label>
                            <select class="form-control" name="tipo_capacitacion" required>
                                <option value="" >Escoge una opción</option>
                                <option value="Impartida" <?php if ($e_detalle['tipo_capacitacion'] === 'Impartida') echo 'selected="selected"'; ?>>Impartida</option>
                                <option value="Tomada" <?php if ($e_detalle['tipo_capacitacion'] === 'Tomada') echo 'selected="selected"'; ?>>Tomada</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="nombre_capacitacion">Nombre de la capacitación</label>
                            <input type="text" class="form-control" name="nombre_capacitacion" value="<?php echo remove_junk($e_detalle['nombre_capacitacion']); ?>">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="tipo_evento">Tipo de evento</label>
                            <select class="form-control" name="tipo_evento">
                                <option value="Capacitación" <?php if ($e_detalle['tipo_evento'] === 'Capacitación') echo 'selected="selected"'; ?>>Capacitación</option>
                                <option value="Conferencia" <?php if ($e_detalle['tipo_evento'] === 'Conferencia') echo 'selected="selected"'; ?>>Conferencia</option>
                                <option value="Curso" <?php if ($e_detalle['tipo_evento'] === 'Curso') echo 'selected="selected"'; ?>>Curso</option>
                                <option value="Divulgación" <?php if ($e_detalle['tipo_evento'] === 'Divulgación') echo 'selected="selected"'; ?>>Divulgación</option>
                                <option value="Taller" <?php if ($e_detalle['tipo_evento'] === 'Taller') echo 'selected="selected"'; ?>>Taller</option>
                                <option value="Plática" <?php if ($e_detalle['tipo_evento'] === 'Plática') echo 'selected="selected"'; ?>>Plática</option>
                                <option value="Diplomado" <?php if ($e_detalle['tipo_evento'] === 'Diplomado') echo 'selected="selected"'; ?>>Diplomado</option>
                                <option value="Foro" <?php if ($e_detalle['tipo_evento'] === 'Foro') echo 'selected="selected"'; ?>>Foro</option>
                                <option value="Conversatorios" <?php if ($e_detalle['tipo_evento'] === 'Conversatorios') echo 'selected="selected"'; ?>>Conversatorios</option>
                                <option value="Congreso" <?php if ($e_detalle['tipo_evento'] === 'Congreso') echo 'selected="selected"'; ?>>Congreso</option>
                                <option value="Feria de Divulgación" <?php if ($e_detalle['tipo_evento'] === 'Feria de Divulgación') echo 'selected="selected"'; ?>>Feria</option>
                                <option value="Marcha" <?php if ($e_detalle['tipo_evento'] === 'Marcha') echo 'selected="selected"'; ?>>Marcha</option>
                                <option value="Otro" <?php if ($e_detalle['tipo_evento'] === 'Otro') echo 'selected="selected"'; ?>>Otro</option>
                            </select>
                        </div>
                    </div>
					<div class="col-md-2">
                        <div class="form-group">
                            <label for="modalidad">Modalidad</label>
                            <select class="form-control" name="modalidad">
                                <option value="Presencial" <?php if ($e_detalle['modalidad'] === 'Presencial') echo 'selected="selected"'; ?>>Presencial</option>
                                <option value="En línea" <?php if ($e_detalle['modalidad'] === 'En línea') echo 'selected="selected"'; ?>>En línea</option>
                                <option value="Híbrido" <?php if ($e_detalle['modalidad'] === 'Híbrido') echo 'selected="selected"'; ?>>Híbrido</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="quien_solicita">¿Quién lo solicita?</label>
                            <input type="text" class="form-control" name="quien_solicita" placeholder="Nombre Completo" value="<?php echo remove_junk(($e_detalle['quien_solicita'])); ?>">
                        </div>
                    </div>
                </div>
                <div class="row">
					
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="fecha">Fecha</label><br>
                            <input type="date" class="form-control" name="fecha" value="<?php echo remove_junk($e_detalle['fecha']); ?>">
                        </div>
                    </div>

                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="hora">Hora</label><br>
                            <input type="time" class="form-control" name="hora" value="<?php echo remove_junk($e_detalle['hora']); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="lugar">Lugar</label>
                            <input type="text" class="form-control" name="lugar" value="<?php echo remove_junk($e_detalle['lugar']); ?>">
                        </div>
                    </div>
                    
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="depto_org">Departamento/Organización</label>
							<select class="form-control" name="id_area">
								<option value="">Escoge una opción</option>
									<?php foreach ($areas_all as $area) : ?>
										<option <?php if ($area['id_area'] === $e_detalle['id_area']) echo 'selected="selected"'; ?> value="<?php echo $area['id_area']; ?>"><?php echo ucwords($area['nombre_area']); ?></option>
									<?php endforeach; ?>
								</select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="capacitador">Capacitador</label>
                            <input type="text" class="form-control" name="capacitador" placeholder="Nombre Completo" value="<?php echo remove_junk(($e_detalle['capacitador'])); ?>">
                        </div>
                    </div>
                    <!-- 
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="curriculum">Curriculum</label>
                            <input type="file" accept="application/pdf" class="form-control" name="curriculum" id="curriculum" value="uploads/curriculums/<?php echo $e_detalle['curriculum']; ?>">
                            <label style="font-size:12px; color:#E3054F;">Archivo Actual: <?php echo remove_junk($e_detalle['curriculum']); ?><?php ?></label>
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="constancia">Constancia</label>
                            <input type="file" accept="application/pdf" class="form-control" name="constancia">
                        </div>
                    </div> -->
                </div>
				
				<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Asistentes
                </h3>
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Hombres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_hombres" value="<?php echo remove_junk(($e_detalle['asistentes_hombres'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Mujeres</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_mujeres" value="<?php echo remove_junk(($e_detalle['asistentes_mujeres'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">No Binarios</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_nobinario" value="<?php echo remove_junk(($e_detalle['asistentes_nobinario'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Otros</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_otros" value="<?php echo remove_junk(($e_detalle['asistentes_otros'])); ?>" >
                        </div>
                    </div>
				</div>
				
				<div class="row">				 
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 0 a 11 años</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_10" value="<?php echo remove_junk(($e_detalle['asistentes_10'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 12 a 17 años</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_20" value="<?php echo remove_junk(($e_detalle['asistentes_20'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 18 a 30 años</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_30" value="<?php echo remove_junk(($e_detalle['asistentes_30'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 31 a 40 años</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_40" value="<?php echo remove_junk(($e_detalle['asistentes_40'])); ?>" >
                        </div>
                    </div>
					
					<div class="row">				 
					 <div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 41 a 50 años</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_50" value="<?php echo remove_junk(($e_detalle['asistentes_50'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">De 51 a 60 años</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_60" value="<?php echo remove_junk(($e_detalle['asistentes_60'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">60 o más</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_70" value="<?php echo remove_junk(($e_detalle['asistentes_70'])); ?>" >
                        </div>
                    </div>
					<div class="col-md-3">
                        <div class="form-group">
                            <label for="no_asistentes">Sin Dato</label>
                            <input type="number"  class="form-control" max="10000" name="asistentes_80" value="<?php echo remove_junk(($e_detalle['asistentes_80'])); ?>" >
                        </div>
                    </div>
				</div>
			<div class="row">
				 <h3 style="font-weight:bold;">
                    <span class="material-symbols-outlined">checklist</span>
                    Grupos Vulnerables
                </h3>
				<div id="inputFormRow">
					<div class="col-md-4">
						<div class="form-group">
								<label for="no_informe">No. Asistentes</label>	
									<?php if($existe==0){?>
										<input type="number"  class="form-control" max="10000" name="asistentes[]" >						
									<?php }?>
						</div>
					</div>
			
					<div class="col-md-4">
						<div class="form-group">
							<label for="adjunto">Grupo Vulnerable</label>
							<?php if($existe==0){?>
										<select class="form-control" name="id_cat_grupo_vuln[]">
                                <option value="">Escoge una opción</option>
                                <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
                                    <option value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>						
									<?php }?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
						<button type="button" class="btn btn-success" id="addRow" name="addRow" >
							<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-clipboard2-plus-fill" viewBox="0 0 16 16">
							  <path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
							  <path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8.5 6.5V8H10a.5.5 0 0 1 0 1H8.5v1.5a.5.5 0 0 1-1 0V9H6a.5.5 0 0 1 0-1h1.5V6.5a.5.5 0 0 1 1 0Z"></path>
							</svg>
						</button>
							
						</div>
					</div>	
				</div>
			</div>
		
			<div class="row" id="newRow">
			<?php 
				
			foreach ($grupos as $grupo_cap) :
				
			?>
				<div id="inputFormRow">
						<div class="col-md-4">
							 <input type="number"  class="form-control" max="100000" name="asistentes[]" value="<?php echo remove_junk(($grupo_cap['no_asistentes'])); ?>"> 
						</div>
						<div class="col-md-4">
							<select class="form-control" name="id_cat_grupo_vuln[]">
									<option value="">Escoge una opción</option>
								   <?php foreach ($grupos_vuln as $grupo_vuln) : ?>
									 <option  <?php if ($grupo_cap['id_cat_grupo_vuln'] === $grupo_vuln['id_cat_grupo_vuln']) echo 'selected="selected"'; ?> value="<?php echo $grupo_vuln['id_cat_grupo_vuln']; ?>"><?php echo ucwords($grupo_vuln['descripcion']); ?></option>
								   <?php endforeach; ?>
							   </select>
						</div>
						<div class="col-md-2">
						<button type="button" class="btn btn-outline-danger" id="removeRow" > 
						<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-clipboard2-x-fill" viewBox="0 0 16 16">
								<path d="M10 .5a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5.5.5 0 0 1-.5.5.5.5 0 0 0-.5.5V2a.5.5 0 0 0 .5.5h5A.5.5 0 0 0 11 2v-.5a.5.5 0 0 0-.5-.5.5.5 0 0 1-.5-.5Z"></path>
								<path d="M4.085 1H3.5A1.5 1.5 0 0 0 2 2.5v12A1.5 1.5 0 0 0 3.5 16h9a1.5 1.5 0 0 0 1.5-1.5v-12A1.5 1.5 0 0 0 12.5 1h-.585c.055.156.085.325.085.5V2a1.5 1.5 0 0 1-1.5 1.5h-5A1.5 1.5 0 0 1 4 2v-.5c0-.175.03-.344.085-.5ZM8 8.293l1.146-1.147a.5.5 0 1 1 .708.708L8.707 9l1.147 1.146a.5.5 0 0 1-.708.708L8 9.707l-1.146 1.147a.5.5 0 0 1-.708-.708L7.293 9 6.146 7.854a.5.5 0 1 1 .708-.708L8 8.293Z"></path>
							</svg>
						</button>	</div> <br><br>		
						</div>
						<?php endforeach; 
						
							?>
							
				</div>
			</div>
			
                <div class="form-group clearfix">
                    <a href="capacitaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                        Regresar
                    </a>
                    <button type="submit" name="edit_capacitacion" class="btn btn-primary" value="subir">Guardar</button>
                </div>
            </form>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
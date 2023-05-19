<?php
$page_title = 'Seguimiento de Queja';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_queja((int) $_GET['id']);
// echo $e_detalle['id_queja_date'];
if (!$e_detalle) {
    $session->msg("d", "ID de queja no encontrado.");
    redirect('quejas.php');
}
$user = current_user();
$nivel = $user['user_level'];

$cat_medios_pres = find_all_medio_pres();
$cat_autoridades = find_all_aut_res();
$cat_quejosos = find_all_quejosos();
$cat_agraviados = find_all('cat_agraviados');
$users = find_all('users');
$asigna_a = find_all_area_userQ();
$area = find_all_areas_quejas();
$cat_estatus_queja = find_all_estatus_procesal();
$cat_municipios = find_all_cat_municipios();
$cat_tipo_resolucion = find_all('cat_tipo_res');

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 3) {
    redirect('home.php');
}
if ($nivel == 4) {
    redirect('home.php');
}
if ($nivel == 5) {
    page_require_level(5);
}
if ($nivel == 6) {
    redirect('home.php');
}
if ($nivel == 7) {
    page_require_level(7);
}
?>
<?php
if (isset($_POST['seguimiento_queja'])) {
    if (empty($errors)) {
        $id = (int) $e_detalle['id_queja_date'];
        $incompetencia = remove_junk($db->escape($_POST['incompetencia']));
        $causa_incomp = remove_junk($db->escape($_POST['causa_incomp']));
        $fecha_acuerdo_incomp = remove_junk($db->escape($_POST['fecha_acuerdo_incomp']));
        $a_quien_se_traslada = remove_junk($db->escape($_POST['a_quien_se_traslada']));
        $desechamiento = remove_junk($db->escape($_POST['desechamiento']));
        $razon_desecha = remove_junk($db->escape($_POST['razon_desecha']));
        $id_tipo_resolucion = remove_junk($db->escape($_POST['id_tipo_resolucion']));
        $num_recomendacion = remove_junk($db->escape($_POST['num_recomendacion']));
        $servidor_publico = remove_junk($db->escape($_POST['servidor_publico']));
        $observaciones_recomendacion = remove_junk($db->escape($_POST['observaciones_recomendacion']));
        $fecha_recomendacion = remove_junk($db->escape($_POST['fecha_recomendacion']));
        $descripcion_sin_materia = remove_junk($db->escape($_POST['descripcion_sin_materia']));
        $fecha_desistimiento = remove_junk($db->escape($_POST['fecha_desistimiento']));
        // $archivo_sin_materia = remove_junk($db->escape($_POST['archivo_sin_materia']));
        date_default_timezone_set('America/Mexico_City');
        $fecha_actualizacion = date('Y-m-d H:i:s');


        $folio_editar = $e_detalle['folio_queja'];
        $resultado = str_replace("/", "-", $folio_editar);


        //En tramite
        if (($id_tipo_resolucion == 1)) {
            $sql = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='0',causa_incomp='',fecha_acuerdo_incomp=NULL,
                    a_quien_se_traslada='',desechamiento=0,razon_desecha='',num_recomendacion='',servidor_publico='',
                    fecha_recomendacion=NULL,observaciones_recomendacion='',adjunto_recomendacion='',adjunto_rec_publico='',
                    id_tipo_resolucion='$id_tipo_resolucion',descripcion_sin_materia='',fecha_desistimiento=NULL,archivo_desistimiento='',archivo_anv='',archivo_sin_materia='' 
                    WHERE id_queja_date='{$db->escape($id)}'";
            $result = $db->query($sql);
        }
        //Incompetencia
        if (($causa_incomp != '') && ($id_tipo_resolucion == 2)) {
            $sql2 = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='1',causa_incomp='$causa_incomp',
                    fecha_acuerdo_incomp='$fecha_acuerdo_incomp',a_quien_se_traslada='$a_quien_se_traslada',desechamiento=0,razon_desecha='',
                    num_recomendacion='',servidor_publico='',fecha_recomendacion=NULL,observaciones_recomendacion='',adjunto_recomendacion='',adjunto_rec_publico='',
                    id_tipo_resolucion='$id_tipo_resolucion',descripcion_sin_materia='',fecha_desistimiento=NULL,archivo_desistimiento='',archivo_anv='',archivo_sin_materia=''
                    WHERE id_queja_date='{$db->escape($id)}'";
            $result2 = $db->query($sql2);
            insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento a queja como "Incompetencia", Folio: ' . $folio_editar . '.', 2);
        }
        //Sin Materia
        if (($descripcion_sin_materia != '') && ($id_tipo_resolucion == 3)) {
            $carpeta1 = 'uploads/quejas/' . $resultado . '/Sin_Materia';

            $nameSM = $_FILES['archivo_sin_materia']['name'];
            $size1 = $_FILES['archivo_sin_materia']['size'];
            $type1 = $_FILES['archivo_sin_materia']['type'];
            $temp1 = $_FILES['archivo_sin_materia']['tmp_name'];

            if (is_dir($carpeta1)) {
                $move1 = move_uploaded_file($temp1, $carpeta1 . "/" . $nameSM);
            } else {
                mkdir($carpeta1, 0777, true);
                $move1 = move_uploaded_file($temp1, $carpeta1 . "/" . $nameSM);
            }

            $sql3 = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='0',causa_incomp='',
                    fecha_acuerdo_incomp=NULL,a_quien_se_traslada='',desechamiento=0,razon_desecha='',num_recomendacion='',servidor_publico='',
                    fecha_recomendacion=NULL,observaciones_recomendacion='',adjunto_recomendacion='',adjunto_rec_publico='',
                    id_tipo_resolucion='$id_tipo_resolucion',descripcion_sin_materia='{$descripcion_sin_materia}',archivo_sin_materia='{$nameSM}', 
                    fecha_desistimiento=NULL,archivo_desistimiento='',archivo_anv='' WHERE id_queja_date='{$db->escape($id)}'";
            $result3 = $db->query($sql3);
            insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento a queja como "Sin Materia", Folio: ' . $folio_editar . '.', 2);
        }
        //ANV
        if (($id_tipo_resolucion == 4)) {
            $carpeta2 = 'uploads/quejas/' . $resultado . '/ANV';

            $nameANV = $_FILES['archivo_anv']['name'];
            $size2 = $_FILES['archivo_anv']['size'];
            $type2 = $_FILES['archivo_anv']['type'];
            $temp2 = $_FILES['archivo_anv']['tmp_name'];

            if (is_dir($carpeta2)) {
                $move2 = move_uploaded_file($temp2, $carpeta2 . "/" . $nameANV);
            } else {
                mkdir($carpeta2, 0777, true);
                $move2 = move_uploaded_file($temp2, $carpeta2 . "/" . $nameANV);
            }

            $sql3 = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='0',causa_incomp='',
                    fecha_acuerdo_incomp=NULL,a_quien_se_traslada='',desechamiento=0,razon_desecha='',num_recomendacion='',servidor_publico='',
                    fecha_recomendacion=NULL,observaciones_recomendacion='',adjunto_recomendacion='',adjunto_rec_publico='',
                    id_tipo_resolucion='$id_tipo_resolucion',descripcion_sin_materia='',archivo_anv='{$nameANV}', 
                    fecha_desistimiento=NULL,archivo_desistimiento='',archivo_sin_materia='' WHERE id_queja_date='{$db->escape($id)}'";
            $result3 = $db->query($sql3);
            insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento a queja como "ANV", Folio: ' . $folio_editar . '.', 2);
        }
        //Recomendacion
        if (($num_recomendacion != '') && ($id_tipo_resolucion == 5)) {
            $carpetaRec = 'uploads/quejas/' . $resultado . '/Recomendacion';

            $nameRec = $_FILES['adjunto_recomendacion']['name'];
            $sizeRec = $_FILES['adjunto_recomendacion']['size'];
            $typeRec = $_FILES['adjunto_recomendacion']['type'];
            $tempRec = $_FILES['adjunto_recomendacion']['tmp_name'];

            $nameRecP = $_FILES['adjunto_rec_publico']['name'];
            $sizeRecP = $_FILES['adjunto_rec_publico']['size'];
            $typeRecP = $_FILES['adjunto_rec_publico']['type'];
            $tempRecP = $_FILES['adjunto_rec_publico']['tmp_name'];

            if (is_dir($carpetaRec)) {
                $moveRec = move_uploaded_file($tempRec, $carpetaRec . "/" . $nameRec);
                $moveRecP = move_uploaded_file($tempRecP, $carpetaRec . "/" . $nameRecP);
            } else {
                mkdir($carpetaRec, 0777, true);
                $moveRec = move_uploaded_file($tempRec, $carpetaRec . "/" . $nameRec);
                $moveRecP = move_uploaded_file($tempRecP, $carpetaRec . "/" . $nameRecP);
            }

            $sql5 = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='0',causa_incomp='',fecha_acuerdo_incomp=NULL,
                    a_quien_se_traslada='',desechamiento=0,razon_desecha='',num_recomendacion='$num_recomendacion',servidor_publico='$servidor_publico',
                    fecha_recomendacion='$fecha_recomendacion',observaciones_recomendacion='$observaciones_recomendacion',adjunto_recomendacion='$nameRec',
                    adjunto_rec_publico='$nameRecP',id_tipo_resolucion='$id_tipo_resolucion',
                    descripcion_sin_materia='',fecha_desistimiento=NULL,archivo_desistimiento='',archivo_anv='',archivo_sin_materia='' WHERE id_queja_date='{$db->escape($id)}'";
            $result5 = $db->query($sql5);
            insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento a queja como "Recomendación", Folio: ' . $folio_editar . '.', 2);
        }
        //Desechamiento
        if (($razon_desecha != '') && ($id_tipo_resolucion == 6)) {
            $sql6 = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='0',causa_incomp='',fecha_acuerdo_incomp=NULL,
                    a_quien_se_traslada='',desechamiento=1,razon_desecha='$razon_desecha',num_recomendacion='',servidor_publico='',
                    fecha_recomendacion=NULL,observaciones_recomendacion='',adjunto_recomendacion='',adjunto_rec_publico='',id_tipo_resolucion='$id_tipo_resolucion',
                    descripcion_sin_materia='',archivo_desistimiento='',archivo_anv='',archivo_sin_materia='',fecha_desistimiento=NULL WHERE id_queja_date='{$db->escape($id)}'";
            $result6 = $db->query($sql6);
            insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento a queja como "Desechamiento", Folio: ' . $folio_editar . '.', 2);
        }

        //Desistimiento
        if (($fecha_desistimiento != NULL) && ($id_tipo_resolucion == 10)) {
            $carpeta3 = 'uploads/quejas/' . $resultado . '/Desistimiento';

            $nameDesis = $_FILES['archivo_desistimiento']['name'];
            $size3 = $_FILES['archivo_desistimiento']['size'];
            $type3 = $_FILES['archivo_desistimiento']['type'];
            $temp3 = $_FILES['archivo_desistimiento']['tmp_name'];

            if (is_dir($carpeta3)) {
                $move3 = move_uploaded_file($temp3, $carpeta3 . "/" . $nameDesis);
            } else {
                mkdir($carpeta3, 0777, true);
                $move3 = move_uploaded_file($temp3, $carpeta3 . "/" . $nameDesis);
            }

            $sql10 = "UPDATE quejas_dates SET fecha_actualizacion='$fecha_actualizacion',incompetencia='0',causa_incomp='',
                    fecha_acuerdo_incomp=NULL,a_quien_se_traslada='',desechamiento=0,razon_desecha='',num_recomendacion='',
                    id_tipo_resolucion='$id_tipo_resolucion',descripcion_sin_materia='',fecha_desistimiento='{$fecha_desistimiento}',
                    archivo_desistimiento='{$nameDesis}',archivo_anv='',archivo_sin_materia='' WHERE id_queja_date='{$db->escape($id)}'";
            $result10 = $db->query($sql10);
            insertAccion($user['id_user'], '"' . $user['username'] . '" dió seguimiento a queja como "Desistimiento", Folio: ' . $folio_editar . '.', 2);
        }

        if (($result && $db->affected_rows() === 1) || ($result2 && $db->affected_rows() === 1) || ($result3 && $db->affected_rows() === 1) || ($result4 && $db->affected_rows() === 1) ||
            ($result5 && $db->affected_rows() === 1) || ($result6 && $db->affected_rows() === 1) || ($result10 && $db->affected_rows() === 1)
        ) {
            $session->msg('s', "Queja actualizada con su Seguimiento");
            redirect('quejas.php', false);
        } else {
            $session->msg('d', ' Lo siento no se pudieron actualizar los datos.');
            redirect('seguimiento_queja.php?id=' . (int) $e_detalle['id'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('seguimiento_queja.php?id=' . (int) $e_detalle['id'], false);
    }
}
?>
<?php include_once('layouts/header.php'); ?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading">
            <strong>
                <span class="glyphicon glyphicon-th"></span>
                <span>Queja
                    <?php echo $e_detalle['folio_queja']; ?>
                </span>
            </strong>
        </div>
        <div class="panel-body">
            <form method="post" action="seguimiento_queja.php?id=<?php echo (int) $e_detalle['id_queja_date']; ?>" enctype="multipart/form-data">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_cat_aut">Autoridad Responsable</label>
                            <input type="text" class="form-control" name="id_cat_aut" value="<?php echo remove_junk($e_detalle['nombre_autoridad']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_cat_quejoso">Nombre del Quejoso</label>
                            <input type="text" class="form-control" name="id_cat_quejoso" value="<?php echo remove_junk($e_detalle['nombre_quejoso'] . " " . $e_detalle['paterno_quejoso'] . " " . $e_detalle['materno_quejoso']); ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="id_area_asignada">Área a la que se asignó la queja</label>
                            <input type="text" class="form-control" name="id_user_asignado" value="<?php foreach ($area as $a) {
                                                                                                        if ($a['id_area'] === $e_detalle['id_area_asignada']) echo $a['nombre_area'];
                                                                                                    } ?>" readonly>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_cat_mun">Municipio</label>
                            <input type="text" class="form-control" name="id_cat_mun" value="<?php foreach ($cat_municipios as $municipio) {
                                                                                                    if ($municipio['id_cat_mun'] === $e_detalle['id_cat_mun'])
                                                                                                        echo ucwords($municipio['descripcion']);
                                                                                                } ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_estatus_queja">Estatus de Queja</label>
                            <input type="text" class="form-control" name="id_user_asignado" value="<?php foreach ($cat_estatus_queja as $estatus) {
                                                                                                        if ($estatus['id_cat_est_procesal'] === $e_detalle['estado_procesal'])
                                                                                                            echo ucwords($estatus['descripcion']);
                                                                                                    } ?>" readonly>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label for="adjunto">Archivo adjunto</label>
                            <?php
                            $folio_editar = $e_detalle['folio_queja'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <label style="font-size:14px; color:#E3054F;">Archivo Actual: <a target="_blank" style="color:#0094FF" href="uploads/quejas/<?php echo $resultado . '/' . $e_detalle['archivo']; ?>"><?php echo $e_detalle['archivo']; ?></a></label>
                        </div>
                    </div>
                </div>

                <hr style="height: 1px; background-color: #370494; opacity: 1;">
                <strong>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="#7263F0" width="25px" height="25px" viewBox="0 0 24 24" style="margin-top:-0.3%;">
                        <title>arrow-right-circle</title>
                        <path d="M22,12A10,10 0 0,1 12,22A10,10 0 0,1 2,12A10,10 0 0,1 12,2A10,10 0 0,1 22,12M6,13H14L10.5,16.5L11.92,17.92L17.84,12L11.92,6.08L10.5,7.5L14,11H6V13Z" />
                    </svg>
                    <span style="font-size: 20px; color: #7263F0">RESOLUCIÓN</span>
                </strong>
                <div class="row">
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="id_tipo_resolucion">Tipo de Resolución</label>
                            <select class="form-control" id="id_tipo_resolucion" name="id_tipo_resolucion" onchange="showInp()">
                                <option value="">Escoge una opción</option>
                                <!-- <?php foreach ($cat_tipo_resolucion as $tipo_res) : ?>
                                    <option value="<?php echo $tipo_res['id_cat_tipo_res']; ?>"><?php echo ucwords($tipo_res['descripcion']); ?></option>
                                <?php endforeach; ?> -->
                                <?php foreach ($cat_tipo_resolucion as $tipo_res) : ?>
                                    <option <?php if ($tipo_res['id_cat_tipo_res'] === $e_detalle['id_tipo_resolucion'])
                                                echo 'selected="selected"'; ?> value="<?php echo $tipo_res['id_cat_tipo_res']; ?>"><?php echo ucwords($tipo_res['descripcion']); ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <?php if ($e_detalle['id_tipo_resolucion'] == 2) : ?>
                        <div class="col-md-4" id="incompetencia2">
                        <?php endif; ?>
                        <?php if ($e_detalle['id_tipo_resolucion'] != 2) : ?>
                            <div class="col-md-4" id="incompetencia2" style="display: none">
                            <?php endif; ?>
                            <div class="form-group">
                                <label for="causa_incomp">Causa Incompetencia (Si la hay)</label>
                                <textarea class="form-control" name="causa_incomp" id="causa_incomp" cols="40" rows="3"><?php echo $e_detalle['causa_incomp'] ?></textarea>
                            </div>
                            </div>
                            <?php if ($e_detalle['id_tipo_resolucion'] == 2) : ?>
                                <div class="col-md-2" id="incompetencia3">
                                <?php endif; ?>
                                <?php if ($e_detalle['id_tipo_resolucion'] != 2) : ?>
                                    <div class="col-md-2" id="incompetencia3" style="display: none">
                                    <?php endif; ?>
                                    <div class="form-group">
                                        <label for="fecha_acuerdo_incomp">Fecha de Acuerdo de Incompetencia</label>
                                        <input type="date" class="form-control" name="fecha_acuerdo_incomp" value="<?php echo $e_detalle['fecha_acuerdo_incomp']; ?>">
                                    </div>
                                    </div>
                                    <?php if ($e_detalle['id_tipo_resolucion'] == 2) : ?>
                                        <div class="col-md-4" id="incompetencia4">
                                        <?php endif; ?>
                                        <?php if ($e_detalle['id_tipo_resolucion'] != 2) : ?>
                                            <div class="col-md-4" id="incompetencia4" style="display: none">
                                            <?php endif; ?>
                                            <div class="form-group">
                                                <label for="a_quien_se_traslada">¿A quién se traslada?</label>
                                                <textarea class="form-control" name="a_quien_se_traslada" id="a_quien_se_traslada" cols="40" rows="3"><?php echo $e_detalle['a_quien_se_traslada'] ?></textarea>
                                            </div>
                                            </div>
                                            <?php if ($e_detalle['id_tipo_resolucion'] == 3) : ?>
                                                <div class="col-md-4" id="sinmateria">
                                                <?php endif; ?>
                                                <?php if ($e_detalle['id_tipo_resolucion'] != 3) : ?>
                                                    <div class="col-md-4" id="sinmateria" style="display: none">
                                                    <?php endif; ?>
                                                    <div class="form-group">
                                                        <label for="descripcion_sin_materia">Descripción (Sin Materia)</label>
                                                        <textarea class="form-control" name="descripcion_sin_materia" id="descripcion_sin_materia" cols="40" rows="3"><?php echo $e_detalle['descripcion_sin_materia'] ?></textarea>
                                                    </div>
                                                    </div>
                                                    <?php if ($e_detalle['id_tipo_resolucion'] == 3) : ?>
                                                        <div class="col-md-4" id="sinmateria2">
                                                        <?php endif; ?>
                                                        <?php if ($e_detalle['id_tipo_resolucion'] != 3) : ?>
                                                            <div class="col-md-4" id="sinmateria2" style="display: none">
                                                            <?php endif; ?>
                                                            <div class="form-group">
                                                                <label for="archivo_sin_materia">Archivo (Sin Materia)</label>
                                                                <input type="file" accept="application/pdf" class="form-control" name="archivo_sin_materia" id="archivo_sin_materia">
                                                                <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                                                    <?php echo remove_junk($e_detalle['archivo_sin_materia']); ?>
                                                                </label>
                                                            </div>
                                                            </div>
                                                            <?php if ($e_detalle['id_tipo_resolucion'] == 4) : ?>
                                                                <div class="col-md-4" id="anv">
                                                                <?php endif; ?>
                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 4) : ?>
                                                                    <div class="col-md-4" id="anv" style="display: none">
                                                                    <?php endif; ?>
                                                                    <div class="form-group">
                                                                        <label for="archivo_anv">Archivo (ANV)</label>
                                                                        <input type="file" accept="application/pdf" class="form-control" name="archivo_anv" id="archivo_anv">
                                                                        <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                                                            <?php echo remove_junk($e_detalle['archivo_anv']); ?>
                                                                        </label>
                                                                    </div>
                                                                    </div>
                                                                        <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                                                                        <div class="col-md-2" id="recomendacion">
                                                                                <?php endif; ?>
                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 5) : ?>
                                                                            <div class="col-md-2" id="recomendacion" style="display: none">
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <label for="num_recomendacion">Núm. Recomendación</label>
                                                                                    <input type="text" class="form-control" name="num_recomendacion" value="<?php echo $e_detalle['num_recomendacion']; ?>">
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                                                                        <div class="col-md-3" id="recomendacion2">
                                                                                <?php endif; ?>
                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 5) : ?>
                                                                            <div class="col-md-3" id="recomendacion2" style="display: none">
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <label for="servidor_publico">Servidor Público</label>
                                                                                    <input type="text" class="form-control" name="servidor_publico" value="<?php echo $e_detalle['servidor_publico']; ?>">
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                                                                        <div class="col-md-2" id="recomendacion3">
                                                                                <?php endif; ?>
                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 5) : ?>
                                                                            <div class="col-md-2" id="recomendacion3" style="display: none">
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <label for="fecha_recomendacion">Fecha Recomendación</label>
                                                                                    <input type="date" class="form-control" name="fecha_recomendacion" value="<?php echo $e_detalle['fecha_recomendacion']; ?>">
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                                                                        <div class="col-md-3" id="recomendacion4">
                                                                                <?php endif; ?>
                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 5) : ?>
                                                                            <div class="col-md-3" id="recomendacion4" style="display: none">
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <label for="observaciones_recomendacion">Observaciones</label>
                                                                                    <!-- <input type="textarea" class="form-control" name="observaciones_recomendacion" value="<?php echo $e_detalle['observaciones_recomendacion']; ?>"> -->
                                                                                    <textarea class="form-control" name="observaciones_recomendacion" id="observaciones_recomendacion" cols="30" rows="5" value="<?php echo $e_detalle['observaciones_recomendacion']; ?>"><?php echo $e_detalle['observaciones_recomendacion']; ?></textarea>
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                                                                        <div class="col-md-4" id="recomendacion5">
                                                                                <?php endif; ?>
                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 5) : ?>
                                                                            <div class="col-md-4" id="recomendacion5" style="display: none">
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <label for="adjunto_recomendacion">Adjunto Recomendación</label>
                                                                                    <input type="file" accept="application/pdf" class="form-control" name="adjunto_recomendacion" id="adjunto_recomendacion">
                                                                                                            <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                                                                                                <?php echo remove_junk($e_detalle['adjunto_recomendacion']); ?>
                                                                                                            </label>
                                                                                </div>
                                                                            </div>
                                                                            <?php if ($e_detalle['id_tipo_resolucion'] == 5) : ?>
                                                                        <div class="col-md-4" id="recomendacion6">
                                                                                <?php endif; ?>
                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 5) : ?>
                                                                            <div class="col-md-4" id="recomendacion6" style="display: none">
                                                                                <?php endif; ?>
                                                                                <div class="form-group">
                                                                                    <label for="adjunto_rec_publico">Adjunto Recomendación Versión Pública</label>
                                                                                    <input type="file" accept="application/pdf" class="form-control" name="adjunto_rec_publico" id="adjunto_rec_publico">
                                                                                                            <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                                                                                                <?php echo remove_junk($e_detalle['adjunto_rec_publico']); ?>
                                                                                                            </label>
                                                                                </div>
                                                                            </div>
                                                                            
                                                                                        <?php if ($e_detalle['id_tipo_resolucion'] == 6) : ?>
                                                                                    <div class="col-md-4" id="desechamiento2">
                                                                                            <?php endif; ?>
                                                                                                <?php if ($e_detalle['id_tipo_resolucion'] != 6) : ?>
                                                                                            <div class="col-md-4" id="desechamiento2" style="display: none">
                                                                                                    <?php endif; ?>
                                                                                                <div class="form-group">
                                                                                                    <label for="razon_desecha">Razón Desechamiento (Si la hay)</label>
                                                                                                    <textarea class="form-control" name="razon_desecha" id="razon_desecha" cols="40" rows="3"><?php echo $e_detalle['razon_desecha'] ?></textarea>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2" style="display: none">
                                                                                                <div class="form-group">
                                                                                                    <label for="incompetencia">Incompetencia</label>
                                                                                                    <select class="form-control" name="incompetencia" id="incompetencia">
                                                                                                        <?php if ($e_detalle['incompetencia'] == 0) : ?>
                                                                                                            <option value="0">No</option>
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($e_detalle['incompetencia'] == 1) : ?>
                                                                                                            <option value="1">Sí</option>
                                                                                                        <?php endif; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div class="col-md-2" style="display: none">
                                                                                                <div class="form-group">
                                                                                                    <label for="desechamiento">Desechamiento</label>
                                                                                                    <select class="form-control" name="desechamiento" id="desechamiento">
                                                                                                        <?php if ($e_detalle['desechamiento'] == 0) : ?>
                                                                                                            <option value="0">No</option>
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($e_detalle['desechamiento'] == 1) : ?>
                                                                                                            <option value="1">Sí</option>
                                                                                                        <?php endif; ?>
                                                                                                    </select>
                                                                                                </div>
                                                                                            </div>
                                                                                                    <?php if ($e_detalle['id_tipo_resolucion'] == 10) : ?>
                                                                                            <div class="col-md-2" id="desistimiento">
                                                                                                    <?php endif; ?>
                                                                                                    <?php if ($e_detalle['id_tipo_resolucion'] != 10) : ?>
                                                                                                <div class="col-md-2" id="desistimiento" style="display: none">
                                                                                                    <?php endif; ?>
                                                                                                    <div class="form-group">
                                                                                                        <label for="fecha_desistimiento">Fecha de Desistimiento</label>
                                                                                                        <input type="date" class="form-control" name="fecha_desistimiento" value="<?php echo $e_detalle['fecha_desistimiento']; ?>">
                                                                                                    </div>
                                                                                                </div>
                                                                                                        <?php if ($e_detalle['id_tipo_resolucion'] == 10) : ?>
                                                                                                <div class="col-md-3" id="desistimiento2">
                                                                                                        <?php endif; ?>
                                                                                                        <?php if ($e_detalle['id_tipo_resolucion'] != 10) : ?>
                                                                                                    <div class="col-md-3" id="desistimiento2" style="display: none">
                                                                                                        <?php endif; ?>
                                                                                                        <div class="form-group">
                                                                                                            <label for="archivo_desistimiento">Archivo de Desistimiento</label>
                                                                                                            <input type="file" accept="application/pdf" class="form-control" name="archivo_desistimiento" id="archivo_desistimiento">
                                                                                                            <label style="font-size:12px; color:#E3054F;">Archivo Actual:
                                                                                                                <?php echo remove_junk($e_detalle['archivo_desistimiento']); ?>
                                                                                                            </label>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>
                                                                                                <div class="form-group clearfix">
                                                                                                    <a href="quejas.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                                                                                                        Regresar
                                                                                                    </a>
                                                                                                    <button type="submit" name="seguimiento_queja" class="btn btn-primary" value="subir">Guardar</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
            </form>
        </div>
    </div>
</div>
<script>
    function showInp() {
        var getSelectValue = document.getElementById("id_tipo_resolucion").value;
        console.log("ID: " + getSelectValue);

        if (getSelectValue == "1") { //En tramite
            document.getElementById("incompetencia2").style.display = "none";
            document.getElementById("incompetencia3").style.display = "none";
            document.getElementById("incompetencia4").style.display = "none";
            document.getElementById("recomendacion").style.display = "none";
            document.getElementById("recomendacion2").style.display = "none";
            document.getElementById("recomendacion3").style.display = "none";
            document.getElementById("recomendacion4").style.display = "none";
            document.getElementById("recomendacion5").style.display = "none";
            document.getElementById("recomendacion6").style.display = "none";
            document.getElementById("desechamiento2").style.display = "none";
            document.getElementById("sinmateria").style.display = "none";
            document.getElementById("sinmateria2").style.display = "none";
            document.getElementById("anv").style.display = "none";
            document.getElementById("desistimiento").style.display = "none";
            document.getElementById("desistimiento2").style.display = "none";
        }
        if (getSelectValue == "2") { //Incompetencia
            document.getElementById("incompetencia2").style.display = "inline-block";
            document.getElementById("incompetencia3").style.display = "inline-block";
            document.getElementById("incompetencia4").style.display = "inline-block";
            document.getElementById("recomendacion").style.display = "none";
            document.getElementById("recomendacion2").style.display = "none";
            document.getElementById("recomendacion3").style.display = "none";
            document.getElementById("recomendacion4").style.display = "none";
            document.getElementById("recomendacion5").style.display = "none";
            document.getElementById("recomendacion6").style.display = "none";
            document.getElementById("desechamiento2").style.display = "none";
            document.getElementById("sinmateria").style.display = "none";
            document.getElementById("sinmateria2").style.display = "none";
            document.getElementById("anv").style.display = "none";
            document.getElementById("desistimiento").style.display = "none";
            document.getElementById("desistimiento2").style.display = "none";
        }
        if (getSelectValue == "3") { //Sin materia
            document.getElementById("incompetencia2").style.display = "none";
            document.getElementById("incompetencia3").style.display = "none";
            document.getElementById("incompetencia4").style.display = "none";
            document.getElementById("recomendacion").style.display = "none";
            document.getElementById("recomendacion2").style.display = "none";
            document.getElementById("recomendacion3").style.display = "none";
            document.getElementById("recomendacion4").style.display = "none";
            document.getElementById("recomendacion5").style.display = "none";
            document.getElementById("recomendacion6").style.display = "none";
            document.getElementById("desechamiento2").style.display = "none";
            document.getElementById("anv").style.display = "none";
            document.getElementById("sinmateria").style.display = "inline-block";
            document.getElementById("sinmateria2").style.display = "inline-block";
            document.getElementById("desistimiento").style.display = "none";
            document.getElementById("desistimiento2").style.display = "none";
        }

        if (getSelectValue == "4") { //ANV
            document.getElementById("incompetencia2").style.display = "none";
            document.getElementById("incompetencia3").style.display = "none";
            document.getElementById("incompetencia4").style.display = "none";
            document.getElementById("recomendacion").style.display = "none";
            document.getElementById("recomendacion2").style.display = "none";
            document.getElementById("recomendacion3").style.display = "none";
            document.getElementById("recomendacion4").style.display = "none";
            document.getElementById("recomendacion5").style.display = "none";
            document.getElementById("recomendacion6").style.display = "none";
            document.getElementById("desechamiento2").style.display = "none";
            document.getElementById("anv").style.display = "inline-block";
            document.getElementById("sinmateria").style.display = "none";
            document.getElementById("sinmateria2").style.display = "none";
            document.getElementById("desistimiento").style.display = "none";
            document.getElementById("desistimiento2").style.display = "none";
        }

        if (getSelectValue == "5") { //Recomendación
            document.getElementById("recomendacion").style.display = "inline-block";
            document.getElementById("recomendacion2").style.display = "inline-block";
            document.getElementById("recomendacion3").style.display = "inline-block";
            document.getElementById("recomendacion4").style.display = "inline-block";
            document.getElementById("recomendacion5").style.display = "inline-block";
            document.getElementById("recomendacion6").style.display = "inline-block";
            document.getElementById("incompetencia2").style.display = "none";
            document.getElementById("incompetencia3").style.display = "none";
            document.getElementById("incompetencia4").style.display = "none";
            document.getElementById("desechamiento2").style.display = "none";
            document.getElementById("sinmateria").style.display = "none";
            document.getElementById("sinmateria2").style.display = "none";
            document.getElementById("anv").style.display = "none";
            document.getElementById("desistimiento").style.display = "none";
            document.getElementById("desistimiento2").style.display = "none";
        }

        if (getSelectValue == "6") { //Desechamiento
            document.getElementById("desechamiento2").style.display = "inline-block";
            document.getElementById("recomendacion").style.display = "none";
            document.getElementById("recomendacion2").style.display = "none";
            document.getElementById("recomendacion3").style.display = "none";
            document.getElementById("recomendacion4").style.display = "none";
            document.getElementById("recomendacion5").style.display = "none";
            document.getElementById("recomendacion6").style.display = "none";
            document.getElementById("incompetencia2").style.display = "none";
            document.getElementById("incompetencia3").style.display = "none";
            document.getElementById("incompetencia4").style.display = "none";
            document.getElementById("sinmateria").style.display = "none";
            document.getElementById("sinmateria2").style.display = "none";
            document.getElementById("anv").style.display = "none";
            document.getElementById("desistimiento").style.display = "none";
            document.getElementById("desistimiento2").style.display = "none";
        }

        if (getSelectValue == "10") { //Desistimiento
            document.getElementById("desistimiento").style.display = "inline-block";
            document.getElementById("desistimiento2").style.display = "inline-block";
            document.getElementById("desechamiento2").style.display = "none";
            document.getElementById("recomendacion").style.display = "none";
            document.getElementById("recomendacion2").style.display = "none";
            document.getElementById("recomendacion3").style.display = "none";
            document.getElementById("recomendacion4").style.display = "none";
            document.getElementById("recomendacion5").style.display = "none";
            document.getElementById("recomendacion6").style.display = "none";
            document.getElementById("incompetencia2").style.display = "none";
            document.getElementById("incompetencia3").style.display = "none";
            document.getElementById("incompetencia4").style.display = "none";
            document.getElementById("sinmateria").style.display = "none";
            document.getElementById("sinmateria2").style.display = "none";
            document.getElementById("anv").style.display = "none";
        }
    }

    if (document.getElementById("id_tipo_resolucion").value === "2") {
        document.getElementById("incompetencia2").addAttribute("required");
    }

    if (document.getElementById("id_tipo_resolucion").value === "3") {
        document.getElementById("sinmateria").addAttribute("required");
    }

    if (document.getElementById("id_tipo_resolucion").value === "4") {
        document.getElementById("anv").addAttribute("required");
    }

    if (document.getElementById("id_tipo_resolucion").value === "5") {
        document.getElementById("recomendacion").addAttribute("required");
    }

    if (document.getElementById("id_tipo_resolucion").value === "6") {
        document.getElementById("desechamiento2").addAttribute("required");
    }
    if (document.getElementById("id_tipo_resolucion").value === "10") {
        document.getElementById("desistimiento").addAttribute("required");
    }
</script>
<?php include_once('layouts/footer.php'); ?>
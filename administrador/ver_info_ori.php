<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Orientación';
require_once('includes/load.php');
?>
<?php
$e_detalle = find_by_id_orientacion((int)$_GET['id']);
//$all_detalles = find_all_detalles_busqueda($_POST['consulta']);
$user = current_user();
$nivel = $user['user_level'];

if ($nivel <= 2) {
    page_require_level(2);
}
if ($nivel == 5) {
    page_require_level_exacto(5);
}
if ($nivel == 7) {
    page_require_level_exacto(7);
}
if ($nivel == 19) {
    page_require_level_exacto(19);
}
if ($nivel == 21) {
    page_require_level_exacto(21);
}

if ($nivel > 2 && $nivel < 5) :
    redirect('home.php');
endif;
if ($nivel > 5 && $nivel < 7) :
    redirect('home.php');
endif;
if ($nivel > 7 && $nivel < 19) :
    redirect('home.php');
endif;
if ($nivel > 19 && $nivel < 21) :
    redirect('home.php');
endif;

?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading clearfix">
                <strong>
                    <span class="glyphicon glyphicon-th"></span>
                    <span>Información de Orientación</span>
                </strong>
            </div>

            <div class="panel-body">
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr style="height: 10px;">
                            <th style="width: 1%;" class="text-center">Folio</th>
                            <th style="width: 1%;" class="text-center">Fecha de Creación</th>
                            <th style="width: 3%;" class="text-center">Medio de presentación</th>
                            <th style="width: 7%;" class="text-center">Correo</th>
                            <!--SE PUEDE AGREGAR UN LINK QUE TE LLEVE A EDITAR EL USUARIO, COMO EN EL PANEL DE CONTROL EN ULTIMAS ASIGNACIONES-->
                            <th style="width: 5%;" class="text-center">Nombre Completo</th>
                            <th style="width: 3%;" class="text-center">Nivel de Estudios</th>
                            <th style="width: 5%;" class="text-center">Ocupación</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['folio'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['creacion'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['med'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['correo_electronico'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['nombre_completo']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['cesc']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['ocup']))) ?></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 1%;" class="text-center">Edad</th>
                            <th style="width: 1%;" class="text-center">Telefono</th>
                            <th style="width: 1%;" class="text-center">Ext.</th>
                            <th style="width: 1%;" class="text-center">Género</th>
                            <th style="width: 3%;" class="text-center">Grupo Vulnerable</th>
                            <th style="width: 1%;" class="text-center">Lengua</th>
                            <th style="width: 5%;" class="text-center">Autoridad señalada como responsable</th>
                            <th style="width: 5%;" class="text-center">Calle-Num.</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['edad'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['telefono'])) ?></td>
                            <td class="text-center"><?php echo remove_junk($e_detalle['extension']) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['gen'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['grupo'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['lengua'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['aut'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['calle_numero'])) ?></td>

                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 5%;" class="text-center">Colonia</th>
                            <th style="width: 1%;" class="text-center">Código Postal</th>
                            <th style="width: 2%;" class="text-center">Municipio</th>
                            <th style="width: 2%;" class="text-center">Localidad</th>
                            <th style="width: 2%;" class="text-center">Entidad</th>
                            <th style="width: 1%;" class="text-center">Nacionalidad</th>
                            <th style="width: 5%;" class="text-center">Observaciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['colonia']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['codigo_postal'])) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['municipio']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['municipio_localidad']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords(($e_detalle['ent']))) ?></td>
                            <td class="text-center"><?php echo remove_junk(ucwords($e_detalle['nac'])) ?></td>
                            <td><?php echo remove_junk(ucwords($e_detalle['observaciones'])) ?></td>
                            <?php
                            $folio_editar = $e_detalle['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                        </tr>
                    </tbody>
                </table>
                <table class="table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th style="width: 3%;" class="text-center">Archivo</th>
                            <th style="width: 3%;" class="text-center">Imágenes</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <?php
                            $folio_editar = $e_detalle['folio'];
                            $resultado = str_replace("/", "-", $folio_editar);
                            ?>
                            <td class="text-center"><a target="_blank" style="color: #0094FF;" href="uploads/orientacioncanalizacion/orientacion/<?php echo $resultado . '/' . $e_detalle['adjunto']; ?>"><?php echo $e_detalle['adjunto']; ?></a></td>
                            <?php
                            //Si es un directorio
                            $folio_editarO = $e_detalle['folio'];
                            $resultadoO = str_replace("/", "-", $folio_editarO);
                            $directorio = 'uploads/orientacioncanalizacion/orientacion/' . $resultadoO . '/imagenes';
                            if (is_dir($directorio)) {
                                //Escaneamos el directorio
                                $carpeta = @scandir($directorio);
                                //Miramos si existen archivos
                                if (count($carpeta) > 0) {
                            ?>
                                    <td class="text-center">
                                        <div class="form-group clearfix">
                                            <a href="descargar_zip.php?id=<?php echo (int) $e_detalle['idcan']; ?>&t=o" class="btn btn-md btn-success" data-toggle="tooltip" title="Descargar Imágenes">
                                                Descargar Imágenes
                                            </a>
                                        </div>
                                    </td>
                            <?php }
                            } else {
                                echo '<td class="text-center">No hay imágenes</td>';
                            }
                            ?>
                        </tr>
                    </tbody>
                </table>
                <a href="orientaciones.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                    Regresar
                </a>
            </div>
        </div>
    </div>
</div>

<?php include_once('layouts/footer.php'); ?>
<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Lista de derechos Indígenas';
require_once('includes/load.php');

// page_require_level(2);
$all_derechos = find_all_order('cat_der_vuln', 'descripcion');
$user = current_user();
$nivel = $user['user_level'];

// $user = current_user();
//$id_usuario = $user['id'];

// $user = current_user();
$id_user = $user['id'];
$busca_area = area_usuario($id_usuario);
$otro = $busca_area['nivel_grupo'];
$nivel_user = $user['user_level'];

if ($nivel_user > 2 && $nivel_user < 7):
    redirect('home.php');
endif;
if ($nivel_user > 7):
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
                    <span>Catálogo de derechos vulnerados<span>
                </strong>
                <?php if ($otro == 1 || $nivel == 1) : ?>
                    <a href="add_comunidad.php" class="btn btn-info pull-right btn-md"> Agregar Derecho Vulnerado</a>
                <?php endif ?>
            </div>
            <div class="panel-body">
                <table class="datatable table table-bordered table-striped">
                    <thead class="thead-purple">
                        <tr>
                            <th class="text-center" style="width: 5%;">#</th>
                            <th style="width: 40%;">Nombre del Derecho Vulnerado</th>
                            <th class="text-center" style="width: 20%;">Estatus</th>
                            <?php if ($otro == 1 || $nivel == 1) : ?>
                                <th class="text-center" style="width: 15%;">Acciones</th>
                            <?php endif ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($all_derechos as $a_derechos) : ?>
                            <tr>
                                <td class="text-center"><?php echo count_id(); ?></td>
                                <td><?php echo remove_junk(ucwords($a_derechos['descripcion'])) ?></td>
                                <td class="text-center">
                                    <?php if ($a_derechos['estatus'] === '1') : ?>
                                        <span class="label label-success"><?php echo "Activa"; ?></span>
                                    <?php else : ?>
                                        <span class="label label-danger"><?php echo "Inactiva"; ?></span>
                                    <?php endif; ?>
                                </td>
                                <?php if ($otro == 1 || $nivel == 1) : ?>
                                    <td class="text-center">
                                        <div class="btn-group">
                                            <?php if ($otro == 1 || $nivel == 1) : ?>
                                                <a href="edit_area.php?id=<?php echo (int)$a_derechos['id_cat_der_vuln']; ?>" class="btn btn-md btn-warning" data-toggle="tooltip" title="Editar">
                                                    <i class="glyphicon glyphicon-pencil"></i>
                                                </a>
                                            <?php endif ?>
                                            <?php if (($nivel == 1) && ($a_derechos['id_cat_der_vuln'] != 1)) : ?>

                                                <?php if ($a_derechos['estatus'] == 0) : ?>
                                                    <a href="activate_area.php?id=<?php echo (int)$a_derechos['id_cat_der_vuln']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                                                        <span class="glyphicon glyphicon-ok"></span>
                                                    </a>
                                                <?php else : ?>
                                                    <a href="inactivate_area.php?id=<?php echo (int)$a_derechos['id_cat_der_vuln']; ?>" class="btn btn-md btn-danger" data-toggle="tooltip" title="Inactivar">
                                                        <i class="glyphicon glyphicon-ban-circle"></i>
                                                    </a>
                                                <?php endif; ?>
                                                <a href="delete_area.php?id=<?php echo (int)$a_derechos['id_cat_der_vuln']; ?>" class="btn btn-md btn-delete" data-toggle="tooltip" title="Eliminar" onclick="return confirm('¿Seguro que deseas eliminar esta área? Los cargos de trabajo relacionados a esta se establecerán como *Sin área*.');">
                                                    <i class="glyphicon glyphicon-trash"></i>
                                                </a>

                                            <?php endif; ?>
                                        </div>
                                    </td>
                                <?php endif ?>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
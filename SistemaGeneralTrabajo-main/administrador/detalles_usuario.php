<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'Datos trabajadores';
require_once('includes/load.php');
?>
<?php
$all_detalles = find_all_trabajadores();
$user = current_user();
$nivel = $user['user_level'];

$id_usuario = $user['id'];
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
          <span>Lista de Trabajadores de la CEDH</span>
        </strong>
        <?php if ($otro == 1 || $nivel_user == 1) : ?>
          <a href="add_detalle_usuario.php" class="btn btn-info pull-right">Agregar trabajador</a>
        <?php endif ?>
      </div>

      <div class="panel-body">
        <table class="datatable table table-dark table-bordered table-striped">
          <thead>
            <tr style="height: 10px;" class="table-info">
              <th style="width: 1%;">#</th>
              <!--SE PUEDE AGREGAR UN LINK QUE TE LLEVE A EDITAR EL USUARIO, COMO EN EL PANEL DE CONTROL EN ULTIMAS ASIGNACIONES-->
              <th style="width: 10%;">Nombre(s)</th>
              <th style="width: 15%;">Apellidos</th>
              <th style="width: 5%;">Correo</th>
              <th style="width: 5%;">Celular</th>
              <th style="width: 17%;">Área y Cargo</th>
              <th style="width: 1%;">Estatus</th>
              <?php if ($otro == 1 || $nivel_user == 1) : ?>
                <th style="width: 5%;" class="text-center">Acciones</th>
              <?php endif ?>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($all_detalles as $a_detalle) : ?>
              <tr>
                <td><?php echo remove_junk(ucwords($a_detalle['detalleID'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['apellidos'])) ?></td>
                <td><?php echo remove_junk($a_detalle['correo']) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['telefono_celular'])) ?></td>
                <td><?php echo remove_junk(ucwords($a_detalle['nombre_cargo'])) ?> - <?php echo remove_junk(ucwords($a_detalle['nombre_area'])) ?></td>
                <td class="text-center">
                  <?php if ($a_detalle['estatus_detalle'] === '1') : ?>
                    <span class="label label-success"><?php echo "Activo"; ?></span>
                  <?php else : ?>
                    <span class="label label-danger"><?php echo "Inactivo"; ?></span>
                  <?php endif; ?>
                </td>
                <?php if ($otro == 1 || $nivel_user == 1) : ?>
                  <td class="text-center">
                    <div class="btn-group">
                      <a href="ver_info_detalle.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-md btn-info" data-toggle="tooltip" title="Ver información">
                        <i class="glyphicon glyphicon-eye-open"></i>
                      </a>
                      <?php if ($nivel == 1) : ?>
                        <?php if ($a_detalle['estatus_detalle'] == 0) : ?>
                          <a href="activate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-success btn-md" title="Activar" data-toggle="tooltip">
                            <span class="glyphicon glyphicon-ok"></span>
                          </a>
                        <?php else : ?>
                          <a href="inactivate_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-danger btn-md" title="Inactivar" data-toggle="tooltip">
                            <span class="glyphicon glyphicon-ban-circle"></span>
                          </a>
                        <?php endif; ?>
                        <!-- <a href="delete_detalle_usuario.php?id=<?php echo (int)$a_detalle['detalleID']; ?>" class="btn btn-delete btn-md" title="Eliminar" data-toggle="tooltip" onclick="return confirm('¿Seguro que deseas eliminar este trabajador? También se eliminarán su usuario, asignaciones y resguardos.');">
                          <span class="glyphicon glyphicon-trash"></span>
                        </a> -->
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
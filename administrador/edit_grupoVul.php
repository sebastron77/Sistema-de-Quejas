<?php
$page_title = 'Editar Género';
require_once('includes/load.php');
$user = current_user();
page_require_level(1);
?>
<?php
$e_comunidad = find_by_id('cat_grupos_vuln', (int)$_GET['id'], 'id_cat_grupo_vuln');
if (!$e_comunidad) {
    $session->msg("d", "El Grupo Vulnerable no existe, verifique el ID.");
    redirect('cat_grupo_vulnerable.php');
}
?>
<?php
if (isset($_POST['update'])) {

    $req_fields = array('descripcion');
    validate_fields($req_fields);
    if (empty($errors)) {
        $name = remove_junk($db->escape(($_POST['descripcion'])));
		
        $estatus = $_POST['estatus'];

        $query  = "UPDATE cat_grupos_vuln SET ";
        $query .= "descripcion='{$name}' ";
        $query .= "WHERE id_cat_grupo_vuln='{$db->escape($e_comunidad['id_cat_grupo_vuln'])}'";
		 
		$result = $db->query($query);
        if ($result && $db->affected_rows() === 1) {
            //sucess
            $session->msg('s', "Género ha actualizado! '".($name)."'");
			insertAccion($user['id_user'],'"'.$user['username'].'" edito el Género '.$name.'(id:'.(int)$e_comunidad['id_cat_grupo_vuln'].').',2);
            redirect('edit_grupoVul.php?id=' . (int)$e_comunidad['id_cat_grupo_vuln'], false);
        } else {
            //failed
            $session->msg('d', 'Lamentablemente no se ha actualizado la Género, debido a que no hay cambios registrados en la descripción!');
            redirect('edit_grupoVul.php?id=' . (int)$e_comunidad['id_cat_grupo_vuln'], false);
        }
    } else {
        $session->msg("d", $errors);
        redirect('edit_grupoVul.php?id=' . (int)$e_comunidad['id_cat_grupo_vuln'], false);
    }
}
?>
<?php header('Content-Type: text/html; charset=utf-8'); include_once('layouts/header.php'); ?>
<div class="login-page">
    <div class="text-center">
        <h3>Editar Género</h3>
    </div>
    <?php echo display_msg($msg); ?>
    <form method="post" action="edit_grupoVul.php?id=<?php echo (int)$e_comunidad['id_cat_grupo_vuln']; ?>" class="clearfix">
        <div class="form-group">
            <label for="area-name" class="control-label">Nombre del Género</label>
            <input type="name" class="form-control" name="descripcion" value="<?php echo ucwords($e_comunidad['descripcion']); ?>">            
        
        </div>
        <div class="form-group clearfix">
            <a href="cat_grupo_vulnerable.php" class="btn btn-md btn-success" data-toggle="tooltip" title="Regresar">
                Regresar
            </a>
            <button type="submit" name="update" class="btn btn-info">Actualizar</button>
        </div>
    </form>
</div>

<?php include_once('layouts/footer.php'); ?>
<?php
require_once('includes/load.php');

page_require_level(1);
?>
<?php
$e_user = find_by_id('users', (int)$_GET['id'], 'id_user');
$user = current_user();
//$delete_id = delete_by_id('users',(int)$_GET['id']);
$inactivate_id = inactivate_by_id('users', (int) $_GET['id'], 'status', 'id_user');
insertAccion($user['id_user'], '"'.$user['username'].'" inactivó el usuario: '.$e_user['username'].'.', 4);
if ($inactivate_id) {
  $session->msg("s", "Usuario inactivado");
  redirect('users.php');
} else {
  $session->msg("d", "Se ha producido un error en la inactivación del usuario");
  redirect('users.php');
}
?>
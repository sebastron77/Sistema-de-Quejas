<?php
$page_title = 'Coordinación de Sistemas';
require_once('includes/load.php');
?>
<?php
$user = current_user();
$id_user = $user['id_user'];
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
    page_require_level(2);
}
if ($nivel_user == 5) {
    redirect('home.php');
}
if ($nivel_user == 13) {
    page_require_level(13);
}
if ($nivel_user == 21) {
    redirect('home.php');
}
if ($nivel_user == 51) {
    redirect('home.php');
}
if ($nivel_user == 19) {
    redirect('home.php');
}
if ($nivel_user > 2 && $nivel_user < 5) :
    redirect('home.php');
endif;
if ($nivel_user > 5 && $nivel_user < 7) :
    redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 21) :
    redirect('home.php');
endif;

if ($nivel_user > 21 && $nivel_user < 50) :
    redirect('home.php');
endif;
?>

<?php
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
?>

<?php include_once('layouts/header.php'); ?>

<a href="solicitudes.php" class="btn btn-info">Regresar a Áreas</a><br><br>
<h1 style="color: #3a3d44;">Solicitudes de la Cooridnación de Sistemas de Informática</h1>


<div class="row">
    <div class="col-md-12">
        <?php echo display_msg($msg); ?>
    </div>
</div>

<div class="container-fluid">
    <div class="full-box tileO-container">
        <a href="informes.php" class="tileO">
            <div class="tileO-tittle">Informe Trimestral/Anual</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    inbox_customize
                </span>
            </div>
        </a>

        <a href="poa.php" class="tileO">
            <div class="tileO-tittle">Programa Operativo Anual</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    settings
                </span>
            </div>
        </a>

        <a href="capacitaciones.php" class="tileO">
            <div class="tileO-tittle">Capacitaciones</div>
            <div class="tileO-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    supervisor_account
                </span>
            </div>
        </a>

        <a href="eventos.php" class="tile">
            <div class="tile-tittle">Eventos</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    event_available
                </span>
            </div>
        </a>
        <a href="informes_areas.php?a=3" class="tile">
            <div class="tile-tittle">Informe Actividades</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    task_alt
                </span>
            </div>
        </a>
        <a href="env_correspondencia.php" class="tile">
            <div class="tile-tittle">Corresp. Int. Enviada</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    edit_document
                </span>
            </div>
        </a>
        <a href="correspondencia_recibida.php" class="tile">
            <div class="tile-tittle">Corresp. Int. Recibida</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    file_open
                </span>
            </div>
        </a>
    </div>
</div>
<?php include_once('layouts/footer.php'); ?>
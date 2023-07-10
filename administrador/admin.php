<?php
error_reporting(E_ALL ^ E_NOTICE);
$page_title = 'SUIGCEDH';
require_once('includes/load.php');

$user = current_user();
$nivel_user = $user['user_level'];

if ($nivel_user <= 2) {
	page_require_level(2);
}
if ($nivel_user == 7) {
	page_require_level_exacto(7);
}
if ($nivel_user > 2 && $nivel_user < 7) :
	redirect('home.php');
endif;
if ($nivel_user > 7 && $nivel_user < 21) :
	redirect('home.php');
endif;
if ($nivel_user == 21) :
	page_require_level_exacto(21);
endif;
?>
<?php
$c_user = count_by_id('users', 'id_user');
$c_trabajadores = count_by_id('detalles_usuario', 'id_det_usuario');
$c_areas = count_by_id('area', 'id_area');
$c_cargos = count_by_id('cargos', 'id_cargos');
$c_orientacion = count_by_id_orientacion('orientacion_canalizacion', 'id_or_can');
$c_canalizacion = count_by_id_canalizacion('orientacion_canalizacion', 'id_or_can');
$c_quejas = count_by_id('quejas_dates', 'id_queja_date');
$c_actuaciones = count_by_id('actuaciones', 'id_actuacion');
$c_convenios = count_by_id('convenios', 'id_convenio');
$c_consejo = count_by_id('consejo', 'id_acta_consejo');
$c_actividades = count_by_id('eventos_presidencia', 'id_eventos_presidencia');
$c_colaboraciones = count_by_id('colaboraciones', 'id_colaboraciones');
$c_informe = count_by_id('informe_actividades_areas', 'id_info_act_areas');
$c_eventos = count_by_id('eventos', 'id_evento');
?>
<?php include_once('layouts/header.php'); ?>

<div class="row">
	<div class="col-md-12">
		<?php echo display_msg($msg); ?>
	</div>
</div>

<div class="container-fluid">
	<div class="full-box tile-container">

		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="areas.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Áreas</div>
			<div class="tile-icon">
				<span class="material-symbols-outlined" style="font-size:95px;">domain</span>
				<p> <?php echo $c_areas['total']; ?> Registradas</p>
			</div>
		</a>


		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="users.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Trabajadores</div>
			<div class="tile-icon">
				<svg xmlns="http://www.w3.org/2000/svg" width="100px" height="100px" fill="#455a64" class="bi bi-person-video3" viewBox="0 0 16 16">
					<path d="M14 9.5a2 2 0 1 1-4 0 2 2 0 0 1 4 0Zm-6 5.7c0 .8.8.8.8.8h6.4s.8 0 .8-.8-.8-3.2-4-3.2-4 2.4-4 3.2Z" />
					<path d="M2 2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h5.243c.122-.326.295-.668.526-1H2a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v7.81c.353.23.656.496.91.783.059-.187.09-.386.09-.593V4a2 2 0 0 0-2-2H2Z" />
				</svg>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_user['total']; ?> Registrados</p>
			</div>
		</a>

		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="quejas.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Quejas</div>
			<div class="tile-icon">
				<svg width="100px" height="100px" fill="#455a64" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
					<title>book-alert-outline</title>
					<path d="M16 2H4C2.9 2 2 2.9 2 4V20C2 21.11 2.9 22 4 22H16C17.11 22 18 21.11 18 20V4C18 2.9 17.11 2 16 2M16 20H4V4H6V12L8.5 9.75L11 12V4H16V20M20 15H22V17H20V15M22 7V13H20V7H22Z" />
				</svg>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_quejas['total']; ?> Registradas</p>
			</div>
		</a>

		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="orientaciones.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Orientaciones</div>
			<div class="tile-icon">
				<span class="material-symbols-rounded" style="font-size:95px;">psychology_alt</span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_orientacion['total']; ?> Registradas</p>
			</div>
		</a>

		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="canalizaciones.php" <?php endif; ?> class="tile">
			<div class="tile-tittle">Canalizaciones</div>
			<div class="tile-icon">
				<span class="material-symbols-rounded" style="font-size:95px;">
					transfer_within_a_station
				</span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_canalizacion['total']; ?> Registradas</p>
			</div>
		</a>
		
		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="actuaciones.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Actuaciones</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    receipt_long
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_actuaciones['total']; ?> Registradas</p>
            </div>
        </a>
		
		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="convenios.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Convenios</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    description
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_convenios['total']; ?> Registrados</p>
            </div>
        </a>
		
		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="convenios.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Actas de consejo</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    groups_2
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_consejo['total']; ?> Registrados</p>
            </div>
        </a>
		
		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="eventos_pres.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Actividades Pres.</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    event
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_actividades['total']; ?> Registrados</p>
            </div>
        </a>
		
		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="colaboraciones_ud.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Colaboraciones</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    handshake
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_colaboraciones['total']; ?> Registrados</p>
            </div>
        </a>
		
		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="informes_areas.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Informe Actividades</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    task_alt
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_informe['total']; ?> Registrados</p>
            </div>
        </a>

		<a style="text-decoration:none;" <?php if ($nivel_user == 1) : ?> href="eventos.php" <?php endif; ?> class="tile">		 
            <div class="tile-tittle">Eventos Áreas</div>
            <div class="tile-icon">
                <span class="material-symbols-rounded" style="font-size:95px;">
                    event_available
                </span>
				<i class="fas fa-user-tie"></i>
				<p><?php echo $c_eventos['total']; ?> Registrados</p>
            </div>
        </a>

	</div>
</div>

<?php include_once('layouts/footer.php'); ?>
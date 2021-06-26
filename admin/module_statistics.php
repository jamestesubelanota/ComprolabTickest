<?php

/**
 *
 * This file is part of HESK - PHP Help Desk Software.
 *
 * (c) Copyright Klemen Stirn. All rights reserved.
 * https://www.hesk.com
 *
 * For the full copyright and license agreement information visit
 * https://www.hesk.com/eula.php
 *
 */

define('IN_SCRIPT', 1);
define('HESK_PATH', '../');

/* Get all the required files and functions */
require(HESK_PATH . 'hesk_settings.inc.php');
require(HESK_PATH . 'inc/common.inc.php');
require(HESK_PATH . 'inc/admin_functions.inc.php');
require(HESK_PATH . 'inc/reporting_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();
hesk_isLoggedIn();

// Check permissions for this feature
hesk_checkPermission('can_run_reports');

/* Print header */
require_once(HESK_PATH . 'inc/header.inc.php');


/* Print main manage users page */
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');


?>
<div class="main__content reports">
	<form action="module_statistics.php" method="get" name="form1">
		<div class="reports__head">
			<h2>
				<?php echo "Gráficos"; ?>
				<div class="tooltype right out-close">
					<svg class="icon icon-info">
						<use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
					</svg>
					<div class="tooltype__content">
						<div class="tooltype__wrapper">
							<?php echo $hesklang['statistics']['intro']; ?>
						</div>
					</div>
				</div>
			</h2>
		</div>
	</form>

	<!-- Grafico de dona  -->

	<?php
	$conn = new mysqli('localhost', 'root', '', 'helpdesk', '3306');

	if ($conn->connect_errno) {
		echo "Error en la conexión de bases de datos: " . $conn->connect_errno;
	}

	$sql = "SELECT count(id) as cantidad, IF (status=0,\"nuevo\",IF(status=3,\"Resuelto\",IF(status=2,\"Respondido\",
										  IF(status=4,\"En espera\",IF(status=5,\"Esperando respuesta\",IF(status=6,\"En progreso\",\"No se encontró\")))))) as estado
				FROM `hesk_tickets` GROUP BY `status`";

	$res = $conn->query($sql);

	?>

	<div style="display: flex; justify-content: space-around;">
		<div style="width: 300px; height: auto; float: left;">
			<canvas id="myChart"></canvas>
		</div>
		<script src="<?php echo HESK_PATH ?>js/chart.js"></script>
		<script>
			var ctx = document.getElementById('myChart').getContext('2d');
			var chart = new Chart(ctx, {
				type: 'bar',
				data: {
					datasets: [{
						data: [
							<?php
							while ($reg = $res->fetch_assoc()) {
							?> '<?php echo $reg['cantidad']; ?>',
							<?php
							}
							$conn->close();
							?>
						],
						backgroundColor: ['#42a5f5', 'red', 'green', 'blue', 'violet'],
						label: 'Cantidad de tickets contra estados'
					}],
					labels: [

						<?php
						$conn = new mysqli('localhost', 'root', '', 'helpdesk', '3306');
						if ($conn->connect_errno) {
							echo "Error en la conexión de bases de datos: " . $conn->connect_errno;
						}

						$res = $conn->query($sql);

						?>

						<?php
						while ($reg = $res->fetch_assoc()) {
						?> '<?php echo $reg['estado']; ?>',
						<?php
						}
						$conn->close();
						?>
					]
				},
				options: {
					responsive: true
				}
			});
		</script>

		<div class="card" style="width: 18rem;">
			<div class="card-body">
				<h2 class="card-title">Total de tickets</h5>
					<h3 class="card-text">
						<?php
						$conn = new mysqli('localhost', 'root', '', 'helpdesk', '3306');
						if ($conn->connect_errno) {
							echo "Error en la conexión de bases de datos: " . $conn->connect_errno;
						}

						$sql = "SELECT count(id) as total FROM `hesk_tickets`";

						$res = $conn->query($sql);

						$reg = $res->fetch_assoc();

						echo $reg['total'];

						$conn->close();
						?>
					</h3>
					<a href="show_tickets.php" class="btnb btnb-primary">Ver tickets</a>
			</div>
		</div>
	</div>

	<!-- / . Grafico de dona . / -->



	<!-- <p><?php echo $hesklang['statistics']['intro']; ?></p>

    <ul style="list-style-type: disc ! important; padding-left: 40px ! important; margin-top: 20px; margin-bottom: 20px;">
        <li><?php echo $hesklang['statistics']['pie_title_ro']; ?>,</li>
        <li><?php echo $hesklang['statistics']['pie_title_so']; ?>,</li>
        <li><?php echo $hesklang['statistics']['chart_title_md']; ?>,</li>
        <li><?php echo $hesklang['statistics']['chart_title_wd']; ?>,</li>
        <li><?php echo $hesklang['statistics']['chart_title_hd']; ?>,</li>
        <li><?php echo $hesklang['statistics']['chart_title_tfr']; ?>,</li>
        <li><?php echo $hesklang['statistics']['chart_title_ttr']; ?>,</li>
        <li><?php echo $hesklang['statistics']['chart_title_srt']; ?>,</li>
        <li><?php echo $hesklang['and_more']; ?></li>
    </ul>

    <p><?php echo sprintf($hesklang['see_demo'], '<a href="https://www.hesk.com/get/hesk3-statistics-demo">HESK Demo</a>'); ?></p>

    <img src="<?php echo HESK_PATH; ?>img/statistics.jpg" alt="<?php echo $hesklang['statistics']['tab']; ?>" style="margin-top:35px;"> -->

</div>

<?php
require_once(HESK_PATH . 'inc/footer.inc.php');
exit();

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

define('IN_SCRIPT',1);
define('HESK_PATH','../');

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
    <form action="module_escalate.php" method="get" name="form1">
        <div class="reports__head">
            <h2>
                <?php echo 'Reportes'; ?>
                <div class="tooltype right out-close">
                    <svg class="icon icon-info">
                        <use xlink:href="<?php echo HESK_PATH; ?>img/sprite.svg#icon-info"></use>
                    </svg>
                    <div class="tooltype__content">
                        <div class="tooltype__wrapper">
                            <?php echo 'Sección de reportes'; ?>
                        </div>
                    </div>
                </div>
            </h2>
        </div>
    </form>

    <p><?php echo $hesklang['escalate']['intro']; ?></p>

    <ul style="list-style-type: disc ! important; padding-left: 40px ! important; margin-top: 20px; margin-bottom: 20px;">
        <li><?php echo $hesklang['escalate']['feat1']; ?>,</li>
        <li><?php echo $hesklang['escalate']['feat2']; ?>,</li>
        <li><?php echo $hesklang['escalate']['feat3']; ?>,</li>
        <li><?php echo $hesklang['escalate']['feat4']; ?>,</li>
        <li><?php echo $hesklang['and_more']; ?></li>
    </ul>

    <a href="reporte1.php" target="_blank">tickets no resueltos en plazo de 3 días</a>

</div>

<?php
require_once(HESK_PATH . 'inc/footer.inc.php');
exit();

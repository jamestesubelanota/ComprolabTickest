<?php


define('IN_SCRIPT', 1);
define('HESK_PATH', '../');

// Get all the required files and functions
require(HESK_PATH . 'hesk_settings.inc.php');

// Save the default language for the settings page before choosing user's preferred one
$hesk_settings['language_default'] = $hesk_settings['language'];
require(HESK_PATH . 'inc/common.inc.php');
$hesk_settings['language'] = $hesk_settings['language_default'];
require(HESK_PATH . 'inc/admin_functions.inc.php');
require(HESK_PATH . 'inc/setup_functions.inc.php');
hesk_load_database_functions();

hesk_session_start();
hesk_dbConnect();
hesk_isLoggedIn();

// Load custom fields
require_once(HESK_PATH . 'inc/custom_fields.inc.php');

// Test languages function
if (isset($_GET['test_languages'])) {
    hesk_testLanguage(0);
} elseif (isset($_GET['test_themes'])) {
    hesk_testTheme(0);
}

$help_folder = '../language/' . $hesk_settings['languages'][$hesk_settings['language']]['folder'] . '/help_files/';

$enable_save_settings   = 0;
$enable_use_attachments = 0;


?>

<?php

$ident = $_GET['cas0'];
$cliente = $_GET['cas'];
$zona = $_GET['cas2'];
$idzona = $_GET['cas3'];

if (isset($_POST['btnEditar'])) {

        echo "<a href='add_customer.php'>Volver a la lista</a>";
        $sql = "UPDATE `hesk_customers` SET `nombre` = '$_POST[nombre]', `zona` = '$_POST[zona]' WHERE `hesk_customers`.`id` = $_POST[ide];";
        hesk_dbQuery($sql);
    header("Location: add_customer.php");
}

// Print header
require_once(HESK_PATH . 'inc/header.inc.php');

// Print main manage users page
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');
?>


<div style="margin-left: 10px;" class="main__content settings">
    <h1 class="h1est">Modificar cliente</h1>
    <div class="table-wrap">
        <form action="edit_customer.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>">
            <div class="form-group">
                <input value="<?php echo $cliente ?>" type="text" name="nombre" class="form-control">
            </div>
            <div class="form-group">
                <select name="zona" id="" class="form-control">
                    <?php
                    $sql = "SELECT id,nombre FROM hesk_zones";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                        echo "<option value='$reg[id]'>$reg[nombre]</option>";
                    }
                    ?>
                </select>
            </div>
            <input type="text" name="ide" hidden value="<?php echo $ident ?>" id="">

            <input type="submit" name="btnEditar" value="Editar" class="btnb btnb-primary">
        </form>
    </div>
</div>


<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>
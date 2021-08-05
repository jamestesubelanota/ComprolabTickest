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
<script type="text/javascript" src="../js/validation.js"></script>

<?php

    
    $ident = $_GET['cas0'];
    $old_id = $_GET['cas'];
    $old_name = $_GET['cas2'];

    if (isset($_POST['editar'])) {
        
        // $new_id = $_POST['id'];
        $new_name = $_POST['nombre'];
        // $new_enginner = $_POST['ingeniero'];
        $query = "UPDATE hesk_zones SET nombre='$new_name' WHERE id=$_POST[idZ]";
        echo "<a href='add_zone.php'>volver al listado</a>";
        hesk_dbQuery($query);
        header("Location: add_zone.php");
    }
    
// Print header
require_once(HESK_PATH . 'inc/header.inc.php');

// Print main manage users page
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');

?>

<div style="margin-left: 10px;" class="main__content settings">
    <h1 class="h1est">Modificar zona</h1>
    <div class="table-wrap">

        <form action="edit_zone.php" method="post" class="form" onsubmit='return validar()'>


            <!-- <div class="form-group">
                <input autocomplete="off" value="<?php echo $_GET['cas'] ?>" type="text" name="id" id="id" class="form-control" placeholder="Id Zona">
            </div> -->
            <div class="form-group">
                <input value="<?php echo $_GET['cas2'] ?>" type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre Zona" require="true">
            </div>
            <!-- <div class="form-group">
                <select class="form-control" name="ingeniero" id="ingeniero" require="true">
                    <option disabled>Asignar zona</option>
                    <?php
                    $sqlI = "SELECT id, name FROM hesk_users WHERE rol = 1";
                    $resI = hesk_dbQuery($sqlI);
                    while ($regI = hesk_dbFetchAssoc($resI)) {

                        echo "<option value='$regI[id]'>$regI[name]</option>";
                    }
                    ?>

                </select>
            </div> -->
            <input autocomplete="off" type="hidden" name="idZ" value="<?php echo $ident ?>">


            <input name="editar" type="submit" value="Editar" class="btn btn-full">
        </form>
    </div>
</div>

<?php

require_once(HESK_PATH . 'inc/footer.inc.php');

?>
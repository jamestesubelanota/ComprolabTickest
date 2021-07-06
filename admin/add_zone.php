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

// Print header
require_once(HESK_PATH . 'inc/header.inc.php');

// Print main manage users page
require_once(HESK_PATH . 'inc/show_admin_nav.inc.php');

?>
<script type="text/javascript" src="cache/js/validation.js"></script>
<?php

// agregar

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$ingeniero = $_POST['ingeniero'];



if (isset($_POST['Crear'])) {
    
    if (isset($_POST['id']) && isset($_POST['nombre'])) {


        if (isset($_POST['ingeniero'])) {

            hesk_dbQuery("INSERT INTO
    
            hesk_zones (
                id,
                nombre,
            )
            VALUES (
                '$id',
                '$nombre'
            )
            
            ");
            hesk_dbQuery("UPDATE hesk_users
    
                SET zone=$id
                WHERE id=$ingeniero      
            
            ");
        } else {
            hesk_dbQuery("INSERT INTO
    
            hesk_zones (
                id,
                nombre
            )
            VALUES (
                '$id',
                '$nombre'
            )
            
            ");
        }
    } else {
        echo "Por favor ingrese todos los datos";
    }
}


?>

<div class="main__content settings">
    <h1>Agregar</h1>
    <div class="table-wrap">

        <form action="add_zone.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>" onsubmit='return validar()'>
            <div class="form-group">
                <input type="text" name="id" id="id" class="form-control" placeholder="Id Zona">
            </div>
            <div class="form-group">
                <input type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre Zona" require = "true">
            </div>
            <div class="form-group">
                <select  class="form-control" name="ingeniero" id="ingeniero" require = "true">
                    <option disabled>Asignar zona</option>
                    <?php
                    $sqlI = "SELECT id, name FROM hesk_users WHERE zone IS NULL AND rol = 1";
                    $resI = hesk_dbQuery($sqlI);
                    while ($regI = hesk_dbFetchAssoc($resI)) {
                        
                        echo "<option value='$regI[id]'>$regI[name]</option>";
                    }
                    ?>
                    
                </select>
            </div>

            <input name="Crear" type="submit" value="Crear" class="btn btn-full">
            <input name="Editar" type="submit" value="Editar" class="btn btn-full">
        </form>
    </div>
    <h1>Zonas registradas </h1>
    <div class="table-wrap">
        <table class="table sindu-table ticket-list sindu_origin_table">
            <tr>
                <th>Ingeniero a cargo</th>
                <th>Id de zona</th>
                <th>Zona</th>
                <th>Acciones</th>
            </tr>
            <?php
            // consultar
            //hu = hesk_users, hz=hesk_zones
            $sqlC = "SELECT
                        hz.id AS zone, 
                        hz.nombre AS nomZone, 
                        hu.name as nomUsu 
                        FROM hesk_users AS hu
                        RIGHT JOIN hesk_zones AS hz
                        ON hu.zone=hz.id;

                    ";

            $res = hesk_dbQuery($sqlC);

            while ($reg = hesk_dbFetchAssoc($res)) {
                echo "<tr>";
                if ($reg["nomUsu"] === null) {
                    echo "<td>No hay nadie encargado</td>";
                } else {
                    echo "<td>$reg[nomUsu]</td>";
                }
                echo "<td>$reg[zone]</td>";
                echo "<td>$reg[nomZone]</td>";
                echo "<td><a href='' class=''>Editar</a></td>";
                echo "</tr>";
            }
            ?>
        </table>
    </div>
</div>
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>
<script type="text/javascript" src="../js/no-resend.js"> </script>


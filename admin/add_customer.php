<?php

use function PHPSTORM_META\sql_injection_subst;

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

<head>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>

</head>

<?php 

    // agregar cliente
    if (isset($_POST['btnCrear'])) {
        $nombre = $_POST["nombre"];
        $zona = $_POST["zona"];

        $sql = "INSERT INTO hesk_customers (nombre,zona) VALUES ('$nombre','$zona');";
        hesk_dbQuery($sql);
    }

?>

<div style="margin-left: 10px;" class="main__content settings">
    <h1 class="h1est">Agregar</h1>
    <div class="table-wrap">
        <form action="add_customer.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>">
            <div class="form-group">
                <input type="text" name="nombre" class="form-control">
            </div>
            <div class="form-group">
                <select name="zona" id="" class="form-control">
                    <?php 
                        $sql = "SELECT id,nombre FROM hesk_zones";
                        $res = hesk_dbQuery($sql);
                        echo "<option disabled='true' value='0' selected>Asignar</option>";
                        while ($reg = hesk_dbFetchAssoc($res)) {
                            echo "<option value='$reg[id]'>$reg[nombre]</option>";
                        }
                    ?>
                </select>
            </div>

            <input type="submit" name="btnCrear" value="Crear" class="btnb btnb-primary">
        </form>
    </div>
    <h1 class="h1est">Clientes registrados </h1>
    <div class="table-wrap">
        <table id="tablaclientes" class="display">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Zonas</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // consultar
                //hu = hesk_users, hz=hesk_zones
                $sqlC = "SELECT hc.id as ident, hc.nombre as cliente, hz.nombre as zona, hc.zona as idzona FROM `hesk_customers` hc JOIN `hesk_zones` hz ON hc.zona=hz.id;

                    ";

                $res = hesk_dbQuery($sqlC);

                while ($reg = hesk_dbFetchAssoc($res)) {
                    echo "<tr>";
                    echo "<td>$reg[cliente]</td>";
                    echo "<td>$reg[zona]</td>";
                    echo "<td style='text-align: center;'><a style='color:blue;' href='edit_customer.php?cas0=$reg[ident]&cas=$reg[cliente]&cas2=$reg[zona]&cas3=$reg[idzona]' class=''><span data-tooltip='Editar'><i class='fas fa-edit'></i></span></a>\t<a style='color:red;' href='delete_customer.php?cas=$reg[ident]'><i class='fas fa-trash-alt'></i></a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Cliente</th>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
    
</div>
<script>
    $(document).ready(function() {
        $('#tablaclientes').DataTable({
            "language": {
                "lengthMenu": "Mostrar _MENU_ filas por pagina",
                "zeroRecords": "No se encuentran resultados",
                "info": "Mostrando _PAGE_ de _PAGES_",
                "infoEmpty": "Sin filas disponibles",
                "infoFiltered": "(filtered from _MAX_ total records)"
            },
            "pagingType": "full_numbers"
        });
    });
</script>
<script type="text/javascript" src="../js/no-resend.js"> </script>
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>

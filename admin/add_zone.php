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
<script type="text/javascript" src="../js/validation.js"></script>

<head>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>

</head>
<?php

// agregar

$id = $_POST['id'];
$nombre = $_POST['nombre'];
$ingeniero = $_POST['ingeniero'];


// al presionar en botón de crear
if (isset($_POST['Crear'])) {
    // condición para confirmar que no estén vacios los campos
    // if (isset($_POST['id']) && isset($_POST['nombre'])) {

        // condicion para saber si hay un ingeniero al que se le vaya a asignar la zona
        // if (isset($_POST['ingeniero'])) {

            hesk_dbQuery("INSERT INTO
    
            hesk_zones (
                nombre
            )
            VALUES (
                '$nombre'
            )
            
            ");
        // } else {
        //     hesk_dbQuery("INSERT INTO
    
        //     hesk_zones (
        //         codigo_zona,
        //         nombre
        //     )
        //     VALUES (
        //         '$id',
        //         '$nombre'
        //     )
            
        //     ");
        }
    // } else {
    //     echo "Por favor ingrese todos los datos";
    // }
// }


?>

<div style="margin-left: 10px;" class="main__content settings">
    <h1 class="h1est">Agregar</h1>
    <div class="table-wrap">

        <form action="add_zone.php" method="post" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>" onsubmit='return validar()'>


            <!-- <div class="form-group">
                <input autocomplete="off" type="text" name="id" id="id" class="form-control" placeholder="Id Zona">
            </div> -->
            <div class="form-group">
                <input autocomplete="off" type="text" name="nombre" id="nombre" class="form-control" placeholder="Nombre Zona" require="true">
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

            <input name="Crear" type="submit" value="Crear" class="btn btn-full">
        </form>
    </div>
    <h1 class="h1est">Zonas registradas </h1>
    <div class="table-wrap">
        <table id="tablazonas" class="display">
            <thead>
                <tr>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // consultar
                //hu = hesk_users, hz=hesk_zones
                $sqlC = "SELECT
                        hz.id AS ident,
                        hz.nombre AS nomZone
                        FROM hesk_zones AS hz
                        ORDER BY hz.id;

                    ";

                $res = hesk_dbQuery($sqlC);

                while ($reg = hesk_dbFetchAssoc($res)) {
                    echo "<tr>";
                    echo "<td>$reg[nomZone]</td>";
                    echo "<td style='text-align: center;'><a style='color:blue;' href='edit_zone.php?cas0=$reg[ident]&cas=$reg[zone]&cas2=$reg[nomZone]' class=''><span data-tooltip='Editar'><i class='fas fa-edit'></i></span></a>\t<a style='color:red;' href='delete_zone.php?cas=$reg[ident]&cas2=$reg[idUsu]'><i class='fas fa-trash-alt'></i></a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Zona</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#tablazonas').DataTable({
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
<?php require_once(HESK_PATH . 'inc/footer.inc.php'); ?>

<script type="text/javascript" src="../js/no-resend.js"> </script>
<script type="text/javascript" src="../js/validation.js"> </script>
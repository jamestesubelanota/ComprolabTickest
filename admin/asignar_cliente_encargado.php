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

<head>
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <script src="../js/jquery-3.5.1.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>

</head>

<div style="margin-left: 10px;" class="main__content settings">

    <h1 class="h1est">Asignamiento de clientes y encargados</h1>

    <div class="table-wrap">
        <form action="asignar_cliente_encargado.php" class="form <?php echo isset($_SESSION['iserror']) && count($_SESSION['iserror']) ? 'invalid' : ''; ?>" method="post" >
            <div class="form-group">
                <select name="customer" id="customer" class="form-control">
                    <option disabled selected value="0">Cliente</option>
                    <?php
                    $sql = "SELECT id,nombre FROM hesk_customers";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                    ?>
                        <option value="<?php echo $reg['id'] ?>"><?php echo $reg['nombre'] ?></option>
                    <?php
                    }
                    ?>
                </select>
                <select style="margin-top: 10px;" name="encargado" id="encargado" class="form-control">
                    <option disabled selected value="0">Encargado</option>
                    <?php
                    $sql = "SELECT id, name FROM hesk_users";
                    $res = hesk_dbQuery($sql);
                    while ($reg = hesk_dbFetchAssoc($res)) {
                        if ($reg['id'] == 0) {
                            continue;
                        }
                    ?>
                        <option value="<?php echo $reg['id'] ?>"><?php echo $reg['name'] ?></option>
                    <?php
                    }
                    ?>
                </select>

                <input style="margin-top: 10px;" type="submit" value="Enviar" class="btnb btnb-primary">

            </div>
        </form>

        <table class="display" id="tablaclienteencargado">
            <thead>
                <tr>
                    <th>Cliente</th>
                    <th>Encargado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT hcu.id AS idEncuentro, hcu.idcustomer AS idC,hcu.idencargado AS idE,hu.name AS nombreE,hc.nombre AS nombreC FROM hesk_customers_users hcu JOIN hesk_users hu ON hu.id=hcu.idencargado JOIN hesk_customers hc ON hc.id = hcu.idcustomer";
                $res = hesk_dbQuery($sql);
                while ($reg = hesk_dbFetchAssoc($res)) {
                ?>
                    <tr>
                        <td><?php echo $reg['nombreC'] ?></td>
                        <td><?php echo $reg['nombreE'] ?></td>
                        <td style="text-align: center;">
                            <a href="<?php echo "delete_encargado.php?id=$reg[idEncuentro]" ?>" style="color: red ;"><i class='fas fa-trash-alt'></i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <th>Cliente</th>
                    <th>Encargado</th>
                    <th>Acciones</th>
                </tr>
            </tfoot>
        </table>

    </div>

</div>
<script>
    $(document).ready(function() {
        $('#tablaclienteencargado').DataTable({
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
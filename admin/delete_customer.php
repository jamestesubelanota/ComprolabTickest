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

$id = $_GET['cas'];
$idUsu = $_GET['cas2'];
echo $id;
$sql1 = "DELETE FROM hesk_customers WHERE id = '$id'";

hesk_dbQuery($sql1);

// $resZ = hesk_dbQuery("SELECT id FROM hesk_zones WHERE codigo_zona = '$id'");
// $regZ = hesk_dbFetchAssoc($resZ);
// hesk_dbQuery("UPDATE hesk_users
//                 SET zone=NULL
//                 WHERE id=$idUsu 
//             ");

header("Location: add_customer.php");

?>
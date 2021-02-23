<?php
 
// Get Class Orobnat
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/src/orobnat.php');


// Request data
$orobnat = new orobnat($_GET['idRegion'] , $_GET['departement'], $_GET['communeDepartement'], $_GET['reseau']);
$data = $orobnat->getData();

// Transform result form a "PHP array" to a JSON
$data = json_encode($data);

// Output data
header('Content-Type: application/json');
echo(($data));


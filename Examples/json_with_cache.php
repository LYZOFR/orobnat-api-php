<?php


// small security
if (array_key_exists('idRegion', $_GET) === false
        || array_key_exists('departement', $_GET) === false
        || array_key_exists('communeDepartement', $_GET) === false
        || array_key_exists('reseau', $_GET) === false
        
        || filter_var($_GET['idRegion'], FILTER_SANITIZE_STRING) == false
        || filter_var($_GET['departement'], FILTER_SANITIZE_STRING) == false
        || filter_var($_GET['communeDepartement'], FILTER_SANITIZE_STRING) == false
        || filter_var($_GET['reseau'], FILTER_SANITIZE_STRING) == false
) {
    echo "bad guys";
    die();
}



// Get Class Orobnat
define('__ROOT__', dirname(dirname(__FILE__)));
require_once(__ROOT__ . '/src/orobnat.php');


$post['idRegion'] = filter_var($_GET['idRegion'], FILTER_SANITIZE_STRING);
$post['departement'] = filter_var($_GET['departement'], FILTER_SANITIZE_STRING);
$post['communeDepartement'] = filter_var($_GET['communeDepartement'], FILTER_SANITIZE_STRING);
$post['reseau'] = filter_var($_GET['reseau'], FILTER_SANITIZE_STRING);


// Cache result (in JSON)
$cacheKey = implode('-', $post);
$cacheFile = __DIR__ . "/cache/$cacheKey.json"; // make this file in same dir
$force_refresh = false; // dev  = true  // Prod = false
$refresh = 60 * 60 * 24; // once per day


// cache json results so to not over-query (api restrictions)
if ($force_refresh || ((time() - filectime($cacheFile)) > ($refresh) || 0 == filesize($cacheFile))) {

    // Request data
    $orobnat = new orobnat($post['idRegion'] , $post['departement'], $post['communeDepartement'], $post['reseau']);
    $data = $orobnat->getData();

    // Transform result form a "PHP array" to a JSON
    $data = json_encode($data);

    // Save as file, for caching purpose.
    $orobnat->file_force_contents($cacheFile, $data); 


} else {
    // Get local JSON, from cache
    $data = file_get_contents($cacheFile);
}


// Output data
header('Content-Type: application/json');
echo(($data));

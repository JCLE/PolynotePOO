<?php 
/*
    ************ ONLY FOR DEV ************
*/

// require_once "public/useful/formatting.php"; 
require_once "config/config.php";
require_once "models/development.dao.php";


function getPageDeleteAll()
{
    $stmt = deleteAll();

    $folder = 'public/sources/images/';
    deleteDirectory($folder.'icons');
    deleteDirectory($folder.'images');

    $alert_message = "Suppression de toutes les données";
    $alert_type= ALERT_DANGER;
    getPageHomeLogged($alert_message, $alert_type);
    // header ("Location: home");
}

function deleteDirectory($dir) {
    if (!file_exists($dir)) {
        return true;
    }

    if (!is_dir($dir)) {
        return unlink($dir);
    }

    foreach (scandir($dir) as $item) {
        if ($item == '.' || $item == '..') {
            continue;
        }

        if (!deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
            return false;
        }

    }

    return rmdir($dir);
}
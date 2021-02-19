<?php
require_once "models/pdo.php";

/**     *****************
 *      ****** GET ******
 */

function deleteAll(){
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    delete from uses;
    delete from category;
    delete from note;
    delete from image;
    ');
    $result = $stmt->execute();
    $stmt->closeCursor();
    return $result;
}


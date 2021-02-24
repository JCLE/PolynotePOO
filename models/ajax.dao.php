<?php
require_once "models/pdo.php";

/**     *****************
 *      ****** GET ******
 */
function getSearch($search,$id_user){
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT n.id_user, n.id, n.title, i.name, i.url, i.description, n.content, n.date_creation, n.date_edit, c.id as id_category
    FROM note n
    INNER JOIN category c on n.id_category = c.id
    INNER JOIN image i on c.id_image = i.id
    WHERE n.id_user = :id_user
    AND (
    title LIKE :research
    OR content LIKE :research
    OR tags LIKE :research
    OR c.name LIKE :research
    )');
    $stmt->bindValue(":search",$search,PDO::PARAM_STR);
    $stmt->bindValue(":research",'%'.$search.'%',PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $notes = $stmt->fetchALL(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $notes;
}

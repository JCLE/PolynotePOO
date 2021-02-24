<?php
require_once "models/pdo.php";

/**     *****************
 *      ****** GET ******
 */

function getIfCategoriesExist($id_user){
    $bdd = connexionPDO();
    $req = '
    SELECT *
    FROM category
    WHERE id_user = :id_user
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    $stmt->closeCursor();
    if($count > 0) return true;
    return false;
}

function getIfCategoryExist($name_category, $id_user){
    $bdd = connexionPDO();
    $req = '
    SELECT *
    FROM category c
    WHERE name = :name_category
    AND id_user = :id_user
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":name_category",$name_category,PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    $stmt->closeCursor();
    if($count > 0) return true;
    return false;
}

function getCategoryFromID($id_category, $id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT c.id as id_category, c.name, c.id_image, c.id_user, i.url, i.description
    FROM category c
    INNER JOIN image i on c.id_image = i.id
    WHERE c.id = :id_category
    AND c.id_user = :id_user
    ');
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $category;
}

function getCategories($id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT c.id as id_category, c.id_image, c.id_user,c.name,i.url,i.description, count(n.id) AS nb_notes
    FROM category c 
    LEFT JOIN note n on c.id = n.id_category
    INNER JOIN image i on c.id_image = i.id
    WHERE c.id_user = :id_user
    GROUP BY c.id
    ');
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $categories = $stmt->fetchALL(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $categories;
}

function getResultNumberFromCategory($page, $id_category, $id_user)
{
    $start_page = ($page - 1) * LIMIT_NOTES_BY_PAGE;
    $bdd = connexionPDO();
    $req = '
    SELECT SQL_CALC_FOUND_ROWS * 
    FROM note 
    WHERE id_category = :id_category
    AND id_user = :id_user
    LIMIT :limit 
    OFFSET :start_page
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":start_page",$start_page,PDO::PARAM_INT);
    $stmt->bindValue(":limit",LIMIT_NOTES_BY_PAGE,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    
    $resultFoundRows = $bdd->query('
    SELECT found_rows()
    ');
    
    $totalNumberNotes = $resultFoundRows->fetchColumn();
    return $totalNumberNotes;
}

/**     ******************
 *      ***** INSERT *****
 */
function insertCategoryIntoBD($name,$id_image, $id_user){
    $bdd = connexionPDO();
    $req = '
    INSERT INTO category (name, id_image, id_user)
    VALUES (:name, :id_image, :id_user)
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":name",$name,PDO::PARAM_STR);
    $stmt->bindValue(":id_image",$id_image,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $resultat = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $resultat;
}

/**     ******************
 *      ***** UPDATE *****
 */

function updateCategoryName($id, $name, $id_user){
    $bdd = connexionPDO();
    $req = '
    UPDATE category
    SET  name = :name
    WHERE id_user = :id_user AND id = :id
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":name",$name,PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
    $stmt->execute();
    $resultat = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $resultat;
}

function updateCategoryImage($id_category, $id_image, $id_user){
    $bdd = connexionPDO();
    $req = '
    UPDATE category
    SET  id_image = :id_image
    WHERE id_user = :id_user AND id = :id_category
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_image",$id_image,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $resultat = $stmt->execute();
    $stmt->closeCursor();
    if($resultat > 0) return true;
    return false;
}
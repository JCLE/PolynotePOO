<?php
require_once "models/pdo.php";

/**     *****************
 *      ****** GET ******
 */

function getImagesFromUser($id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT i.id, i.name, i.url, i.description, i.id_user
    FROM image i
    WHERE i.id_user = :id_user 
    AND i.id NOT IN
    (
        SELECT id_image
        FROM category
    )
    ');
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $images;
}

function getUnusedImages($id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT i.id, i.name, i.url, i.description, i.id_user
    FROM image i
    WHERE i.id_user = :id_user 
    AND i.id NOT IN
    (
        SELECT id_image
        FROM category
    )
    AND i.id NOT IN
    (
        SELECT id_image
        FROM uses
    )
    ');
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $images;
}

function getImagesFromNote($id_note, $id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT i.id, i.name, i.url, i.description, i.id_user
    FROM image i
    INNER JOIN uses u on i.id = u.id_image
    WHERE i.id_user = :id_user 
    AND u.id_note = :id_note
    AND i.id NOT IN
    (
        SELECT id_image
        FROM category
    )
    ');
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->bindValue(":id_note",$id_note,PDO::PARAM_INT);
    $stmt->execute();
    $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $images;
}

function getIfImageExist($name, $id_user)
{
    $bdd = connexionPDO();
    $req = '
    SELECT *
    FROM image
    WHERE name = :name
    AND id_user = :id_user
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":name",$name,PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $count = $stmt->rowCount();
    $stmt->closeCursor();
    if($count > 0) return true;
    return false;
}

function getImageFromCategory($id_category, $id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT i.id, c.name, i.url, i.description, i.id_user
    FROM image i
    INNER JOIN category c on c.id_image = i.id
    WHERE c.id = :id_category
    AND c.id_user = :id_user
    ');
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $image;
}

function getImageFromID($id, $id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT id, name, url, description, id_user
    FROM image 
    WHERE id = :id
    AND id_user = :id_user
    ');
    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $image = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $image;
}

/**     ******************
 *      ***** INSERT *****
 */

function insertImageIntoBD($name, $url, $description, $id_user /*= null*/){
    $bdd = connexionPDO();
    $req = '
    INSERT INTO image (name, url, description, id_user)
    values (:name, :url, :description, :id_user)
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":name",$name,PDO::PARAM_STR);
    $stmt->bindValue(":url",$url,PDO::PARAM_STR);
    $stmt->bindValue(":description",$description,PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $result = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $result;
}

function insertNoteUsesImg($id_note, $id_image){
    $bdd = connexionPDO();
    $req = '
    INSERT INTO uses (id_note, id_image)
    VALUES (:id_note, :id_image)
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_note",$id_note,PDO::PARAM_INT);
    $stmt->bindValue(":id_image",$id_image,PDO::PARAM_INT);
    $stmt->execute();
    $result = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $result;
}

/**     ******************
 *      ***** DELETE *****
 */

/**
 * Delete image linked with category but the foreign key
 * fk_category_image need to be set to CASCADE ON DELETE
 *
 * @param  mixed $id_category
 * @param  mixed $id_user
 *
 * @return int number of affected rows
 */
function deleteCategory($id_category, $id_user){
    $bdd = connexionPDO();
    $req = '
    DELETE i FROM image i
    INNER JOIN category c on c.id_image = i.id
    WHERE c.id = :id_category
    AND c.id_user = :id_user';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $result = $stmt->execute();
    $stmt->closeCursor();
    return $result;
}

function deleteImage($id_image, $id_user){
    $bdd = connexionPDO();
    $req = '
    DELETE FROM image
    WHERE id = :id_image
    AND id_user = :id_user';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_image",$id_image,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $result = $stmt->execute();
    $stmt->closeCursor();
    return $result;
}

function deleteAllNoteUsesImg($id_note){
    $bdd = connexionPDO();
    $req = '
    DELETE FROM uses
    WHERE id_note = :id_note
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_note",$id_note,PDO::PARAM_INT);
    $stmt->execute();
    $result = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $result;
}
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

function getNbPagesFromCategory($page, $id_category, $id_user)
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

function getNoteFromID($id_note, $id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT n.*, c.name as name_category, i.url, i.description
    FROM note n
    INNER JOIN category c on n.id_category = c.id
    INNER JOIN image i on c.id_image = i.id
    WHERE n.id = :id_note
    AND n.id_user = :id_user
    ');
    $stmt->bindValue(":id_note",$id_note,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $note;
}

function getNotesFromCategory($page, $id_category, $id_user)
{
    $start_page = ($page - 1) * LIMIT_NOTES_BY_PAGE;
    $bdd = connexionPDO();
    $req = '
    SELECT * 
    FROM note 
    WHERE id_category = :id_category
    AND id_user = :id_user
    LIMIT :limit 
    OFFSET :start_page
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->bindValue(":start_page",$start_page,PDO::PARAM_INT);
    $stmt->bindValue(":limit",LIMIT_NOTES_BY_PAGE,PDO::PARAM_INT);
    $stmt->execute();
    $notes = $stmt->fetchALL(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $notes;
}

function getNoteIDFromTitle($title, $id_user)
{
    $bdd = connexionPDO();
    $stmt = $bdd->prepare('
    SELECT id
    FROM note
    WHERE title = :title
    AND id_user = :id_user
    ');
    $stmt->bindValue(":title",$title,PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $note = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $note['id'];
}

/**     ******************
 *      ***** INSERT *****
 */
function insertNoteFromCategory($title,$content,$tags, $id_category, $id_user){
    $bdd = connexionPDO();
    $req = '
    INSERT INTO note (title, content, tags, date_creation, id_category, id_user)
    VALUES (:title, :content, :tags, :date_creation, :id_category, :id_user)
    ';
    $stmt = $bdd->prepare($req);
    $date = new DateTime();
    $stmt->bindValue(":title",$title,PDO::PARAM_STR);
    $stmt->bindValue(":content",$content,PDO::PARAM_STR);
    $stmt->bindValue(":tags",$tags,PDO::PARAM_STR);
    $stmt->bindValue(":date_creation",$date->format('Y-m-d H:i:s'),PDO::PARAM_STR);
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->execute();
    $resultat = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $resultat;
}

/**     ******************
 *      ***** UPDATE *****
 */
function updateNoteFromUser($id, $title, $content, $tags, $id_category, $id_user){
    $bdd = connexionPDO();
    $req = '
    UPDATE note
    SET  title = :title, content = :content, tags = :tags, id_category = :id_category, date_edit = :date_edit
    WHERE id_user = :id_user AND id = :id
    ';
    $stmt = $bdd->prepare($req);
    $date = new DateTime();
    $stmt->bindValue(":title",$title,PDO::PARAM_STR);
    $stmt->bindValue(":content",$content,PDO::PARAM_STR);
    $stmt->bindValue(":tags",$tags,PDO::PARAM_STR);
    $stmt->bindValue(":id_category",$id_category,PDO::PARAM_INT);
    $stmt->bindValue(":date_edit",$date->format('Y-m-d H:i:s'),PDO::PARAM_STR);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $stmt->bindValue(":id",$id,PDO::PARAM_INT);
    $stmt->execute();
    $resultat = $bdd->lastInsertId();
    $stmt->closeCursor();
    return $resultat;
}

/**     ******************
 *      ***** DELETE *****
 */
function deleteNote($id_note, $id_user){
    $bdd = connexionPDO();
    $req = '
    DELETE FROM note 
    WHERE id = :id_note
    AND id_user = :id_user';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_note",$id_note,PDO::PARAM_INT);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $result = $stmt->execute();
    $stmt->closeCursor();
    return $result;
}
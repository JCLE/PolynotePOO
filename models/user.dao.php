<?php
require_once "models/pdo.php";



function getIfEmailExist($email){
    $bdd = connexionPDO();
    $req = '
    SELECT *
    FROM user
    where email = :email
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":email",$email,PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $stmt->closeCursor();
    if($count > 0) return true;
    return false;
}

function getIfPseudoExist($pseudo){
    $bdd = connexionPDO();
    $req = '
    SELECT *
    FROM user
    where pseudo = :pseudo
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
    $stmt->execute();
    $count = $stmt->rowCount();
    $stmt->closeCursor();
    if($count > 0) return true;
    return false;
}
        
function insertMember($email, $pseudo, $password)
{
    $bdd = connexionPDO();
    $req = '
    INSERT INTO user (email, pseudo, password)
    VALUES (:email, :pseudo, :password)
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":email",$email,PDO::PARAM_STR);
    $stmt->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
    $stmt->bindValue(":password",$password,PDO::PARAM_STR);
    $stmt->execute();
    $stmt->closeCursor();
}

function getPasswordUser($pseudo){
    $bdd = connexionPDO();
    $req = '
    SELECT * 
    FROM user 
    where pseudo = :pseudo';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
    $stmt->execute();
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    return $admin;
}

function isConnexionValid($pseudo,$password){
    $user = getPasswordUser($pseudo);
    if(!$user) return false;
    // var_dump($user);
    // $password = Security::encryptPassword($password);
    // var_dump($password);
    // var_dump(password_verify($password,$user['password']));
    return password_verify($password,$user['password']);
}

function getUser($pseudo, $password){
    $bdd = connexionPDO();
    $req = '
    SELECT * 
    FROM user 
    where pseudo = :pseudo
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":pseudo",$pseudo,PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    $isPasswordCorrect = password_verify($password, $user['password']);
    $stmt->closeCursor();
    if(!$isPasswordCorrect)
    {
        throw new Exception("Mot de passe ou pseudo incorrect");
    }
    else
    {
        return $user;
    }
}

/**     ******************
 *      ***** DELETE *****
 */

function deleteUser($id_user){
    $bdd = connexionPDO();
    $req = '
    DELETE s FROM uses s
    INNER JOIN note n on s.id_note = n.id
    WHERE n.id_user = :id_user;
    DELETE c FROM category c
    WHERE id_user = :id_user;
    DELETE n FROM note n
    WHERE id_user = :id_user;
    DELETE i FROM image i
    WHERE id_user = :id_user;
    ';
    $stmt = $bdd->prepare($req);
    $stmt->bindValue(":id_user",$id_user,PDO::PARAM_INT);
    $result = $stmt->execute();
    $stmt->closeCursor();
    if($result > 0) return true;
    return false;
}
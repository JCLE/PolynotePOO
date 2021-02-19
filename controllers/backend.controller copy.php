<?php
require_once "public/useful/formatting.php";
require_once "models/user.dao.php";
require_once "models/note.dao.php";
require_once "models/category.dao.php";
require_once "models/image.dao.php";
require_once "public/useful/MyBreadcrumb.php"; 
require_once "public/useful/imgManager.php";
// require_once "public/useful/alertManager.php";
// require_once "models/image.dao.php";
// require_once "models/admin.dao.php";
require_once "config/config.php";

class BackendController
{

    function __construct()
    {
        
    }

    /**
     * HOME PAGE need to be logged to access
     */
    function getPageHomeLogged($alert_message = '', $alert_type = 0)
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            $menu_state = MENU_STATE_LOGGED;
            $title = "Page d'accueil de ".$_SESSION['user']['pseudo'];
            $description = "Bienvenue sur Meganote";
            require_once "views/front/home.view.php";
        }
        else
        {
            throw new Exception("Vous n'avez pas le droit d'accéder à cette page, Veuillez vous connecter");
        }
    }

    /**
     * LOGIN PAGE
     */
    function getPageLogin($alert_message = "", $alert_type = 0)
    {

        $menu_state = MENU_STATE_INITIAL;
        $title = "Page d'authentification";
        $description = "Page permettant de s'authentifier pour accéder au site";

        if(Security::checkAccess())
        {
            Security::generateCookiePassword();
            header ("Location: home");
        }

        if(isset($_POST['pseudo']) && !empty($_POST['pseudo']) &&
        isset($_POST['password']) && !empty($_POST['password']))
        {
            $pseudo = Security::secureHTML($_POST['pseudo']);
            $password = Security::secureHTML($_POST['password']);

            if(isConnexionValid($pseudo,$password)){
                $_SESSION['user'] = getUser($pseudo,$password);
                Security::generateCookiePassword();

                if(isset($_POST['remember_me']) && $_POST['remember_me'] === 'on')
                {
                    getRememberMe($pseudo,$password);
                }
                else
                {
                    setcookie(COOKIE_PSEUDO, NULL, -1);
                    setcookie(COOKIE_PASSWORD, NULL, -1);
                }
                header ("Location: home");
            } else 
            {
                $alert_message = "Mot de passe invalide";
                $alert_type= ALERT_DANGER;
                
                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $alert_type;
            }
        }
        require_once "views/back/login.view.php";
    }

    function getPageLogout()
    {
        // session_destroy();
        unset($_SESSION['user']);
        // unset($_SESSION['alert']);
        // $alert_message = "Deco réussie";
        // $alert_type = ALERT_DANGER;
        // echo Alert::getAlert();
        // return;
        header("Location: home");
    }

    function getInitAlert()
    {
        $alert_message = '';
        $alert_type = 0;
        if(isset($_SESSION['alert_message']) && !empty($_SESSION['alert_message']))
        {
            $alert_message = Security::secureHTML($_SESSION['alert_message']);
            unset($_SESSION['alert_message']);
        }
        if(isset($_SESSION['alert_type']) && !empty($_SESSION['alert_type']))
        {
            $alert_type = Security::secureHTML($_SESSION['alert_type']);
            unset($_SESSION['alert-type']);
        }
        $alert['message'] = $alert_message;
        $alert['type'] = $alert_type;
        return $alert;
    }

    function getRememberMe($pseudo, $password)
    {
        setcookie(COOKIE_PSEUDO, $pseudo, time() + 365*24*3600, null, null, false, true);
        setcookie(COOKIE_PASSWORD, $password, time() + 365*24*3600, null, null, false, true);
    }

    /**
     * REGISTER PAGE
     */
    function getPageRegister()
    {
        $menu_state = MENU_STATE_INITIAL;

        $title = "Page d'enregistrement";
        $description = "Page permettant de s'enregistrer sur le site";

        if(isset($_POST['email']) && !empty($_POST['email']) &&
        isset($_POST['pseudo']) && !empty($_POST['pseudo']) &&
        isset($_POST['password']) && !empty($_POST['password']) &&
        isset($_POST['password_check']) && !empty($_POST['password_check']) ) 
        {
            $email = Security::secureHTML($_POST['email']);
            $pseudo = Security::secureHTML($_POST['pseudo']);
            $password = Security::secureHTML($_POST['password']);
            $password_check = Security::secureHTML($_POST['password_check']);
            $emailExist = getIfEmailExist($email);
            $pseudoExist = getIfPseudoExist($pseudo);

            //********** PARTIE A REFACTORISER **********/
            if(empty($_POST['email']))
            {
                $validate_email['valid'] = false;
                $validate_email['text'] = "Ce champ ne peut être laissé vide";                
            }
            else
            {
                $validate_email['valid'] = true;
            }
            if(empty($_POST['pseudo']))
            {
                $validate_pseudo['valid'] = false;
                $validate_pseudo['text'] = "Ce champ ne peut être laissé vide";
            }
            else
            {
                $validate_pseudo['valid'] = true;
            }
            //********** FIN DE PARTIE A REFACTORISER **********/

            if($emailExist)
            {
                $alert_message = "Un compte existe déja avec cet email";
                $alert_type = ALERT_WARNING;
                $validate_email['valid'] = false;
            }
            elseif($pseudoExist)
            {
                $alert_message = "Ce pseudo est déja utilisé. Veuillez en choisir un autre";
                $alert_type = ALERT_WARNING;
                $validate_pseudo['valid'] = false;
            }
            elseif($password != $password_check)
            {
                $alert_message = "Les mots de passe ne correspondent pas";
                $alert_type = ALERT_DANGER;
                $validate_password['valid'] = false;
                $validate_password_check['valid'] = false;
            }
            else
            {
                try
                {
                    $password = Security::encryptPassword($password);
                    insertMember($email, $pseudo, $password);
                    $alert_message = "L'enregistrement de ".$pseudo." a été effectué ";
                    $alert_type = ALERT_SUCCESS;
                    header ("Location: home");
                }catch(Exception $e)
                {
                    $alert_message  = "L'enregistrement n'a pas marché <br />". $e->getMessage();
                    $alert_type  = ALERT_DANGER;
                }
            }
            $_SESSION['alert_message'] = $alert_message;
            $_SESSION['alert_type'] = $alert_type;
        }
        else
        {
            if(!empty($_POST))
            {
                //********** PARTIE A REFACTORISER **********/

                if(empty($_POST['email']))
                {
                    $validate_email['valid'] = false;
                    $validate_email['text'] = "Ce champ ne peut être laissé vide";                
                }
                else
                {
                    $validate_email['valid'] = true;
                }
                if(empty($_POST['pseudo']))
                {
                    $validate_pseudo['valid'] = false;
                    $validate_pseudo['text'] = "Ce champ ne peut être laissé vide";
                }
                else
                {
                    $validate_pseudo['valid'] = true;
                }
                if(empty($_POST['password']))
                {
                    $validate_password['valid'] = false;
                    $validate_password['text'] = "Ce champ ne peut être laissé vide";
                }
                else
                {
                    $validate_password['valid'] = true;
                }
                if(empty($_POST['password_check']))
                {
                    $validate_password_check['valid'] = false;
                    $validate_password_check['text'] = "Ce champ ne peut être laissé vide";
                }
                else
                {
                    $validate_password_check['valid'] = true;
                }
                //********** FIN DE PARTIE A REFACTORISER **********/
            }
        }
        require_once "views/back/register.view.php";
    }

    function getPageCategory()
    {
        // var_dump($_SESSION['alert']);
        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_category = Security::secureHTML($_GET['id']);
            }
            else
            {
                throw new Exception("Identifiant de category inexistant");
            }
            try
            {
                $id_user = $_SESSION['user']['id'];
                $page = (!empty($_GET['page']) ? Security::secureHTML($_GET['page']) : 1);

                $nb_max_pages = getNbPagesFromCategory($page, $id_category, $id_user);
                $notes = getNotesFromCategory($page, $id_category, $id_user);
                $category = getCategoryFromID($id_category, $id_user);     

                // Replace [Library] by <img> in BDD
                $i = 1;
                foreach( $notes as $key => $note )
                {
                    $matches = findImgID($note['content']);
                    $nbMatches = count($matches); 

                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            $image[$i] = getImageFromID($matches[$i][1],$_SESSION['user']['id']);
                            $pattern[$i] = '[library]'.$image[$i]['id'].'[/library]';
                            $replace[$i] = createImgTag($image[$i]);
                        }
                        $notes[$key]['content'] = str_replace($pattern, $replace, $note['content']);
                    }
                    $i++;
                }      

                if($category === false)
                {
                    throw new Exception();
                }            
            }
            catch(Exception $e)
            {
                throw new Exception("Une erreure est survenue lors de la récupération des notes");
            }

            $title = "Page des notes";
            $description = "Page regroupant toutes vos notes";
            $menu_state = MENU_STATE_BREADCRUMB;
            $MyBreadcrumb = new MyBreadcrumb();
            $MyBreadcrumb->add('Notes', 'categories');
            $MyBreadcrumb->add($category['name'], '');      
            $breadcrumb = $MyBreadcrumb->breadcrumb();

            require_once "views/back/notes/category.view.php";
        } 
        else 
        {
            throw new Exception("Acces interdit si vous n'êtes pas authentifié");
        }
    }


    function getPageCategories()
    {
        if(Security::checkAccess())
        {
            Security::generateCookiePassword();
            $title = "Page des notes";
            $description = "Page regroupant toutes vos notes par categories";
            $menu_state = MENU_STATE_BREADCRUMB;
            $MyBreadcrumb = new MyBreadcrumb();
            $MyBreadcrumb->add('Notes', '');        
            $breadcrumb = $MyBreadcrumb->breadcrumb();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            $categories = array();
            if(getIfCategoriesExist($_SESSION['user']['id']))
            {
                $categories = getCategories($_SESSION['user']['id']);
            }
            // var_dump($_SESSION['user']['id']);
            // var_dump(getIfCategoriesExist($_SESSION['user']['id']));
            // var_dump($categories[0]);
            require_once "views/back/notes/categories.view.php";
        } 
        else 
        {
            throw new Exception("Acces interdit si vous n'êtes pas authentifié");
        }
    }

    function getPageAddCategory()
    {
        $title = "Ajout de catégorie";
        $description = "Page permettant l'ajout de categories";
        $menu_state = MENU_STATE_BREADCRUMB;
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Ajout Catégorie', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        $alert = getInitAlert();
        $alert_message = $alert['message'];
        $alert_type = $alert['type'];

        // if(Security::checkAccess())
        // {
        //     Security::generateCookiePassword();

            if(!empty($_FILES) &&
            isset($_POST['category_name']) && !empty($_POST['category_name']))
            {
                $name_category = Security::secureHTML($_POST['category_name']);

                try
                {
                    $fileImage = $_FILES['img_file'];
                    // print_r($fileImage);
                    // return;
                    $dir = "public/sources/images/icons";
                    // create folder if not exist
                    if(!file_exists($dir)) mkdir($dir,0777);
                    $directory = $dir."/user".$_SESSION['user']['id']."/";
                    $tmp_name_category = cleanString($name_category);
                    $imgName = addImg($fileImage, $directory,$tmp_name_category);
                    image_resize($directory.$imgName,$directory.$imgName,50,50);
                }
                catch(Exception $e)
                {
                    throw new Exception("L'insertion en BD n'a pas fonctionné");
                }
                if(!getIfCategoryExist($name_category, $_SESSION['user']['id']))
                {
                    $description = "Image representant la catégorie ".$name_category;
                    $id_image =  insertImageIntoBD($name_category, $imgName, $description, $_SESSION['user']['id']);
                    $id_category = insertCategoryIntoBD($name_category, $id_image, $_SESSION['user']['id']);
                }
                else
                {
                    throw new Exception("Cette catégorie existe déja apparement");
                }

                $alert_message = "La catégorie ".$name_category." a été ajoutée";
                $alert_type = ALERT_SUCCESS;

                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $alert_type;

                header ('Location: category&id='.$id_category);
                // getPageCategory($id_category, $alert_message, $alert_type);
            }
            else
            {
                if(!empty($_POST))
                {           
                    // TODO : erreur pour chaque champs
                    $alert_message = "Erreur lors de l'ajout";
                    $alert_type = ALERT_DANGER;

                    $_SESSION['alert_message'] = $alert_message;
                    $_SESSION['alert_type'] = $alert_type;
                }
            }


            require_once "views/back/notes/addCategory.view.php";
        // }
        // else 
        // {
        //     throw new Exception("Acces interdit si vous n'êtes pas authentifié");
        // }
    }

    function getPageEditCategory()
    {
        $title = "Edition de catégorie";
        $description = "Page permettant l'édition de categories";
        $menu_state = MENU_STATE_BREADCRUMB;
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Edition Catégorie', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        
        if(Security::checkAccessSession())
        {
            // Security::generateCookiePassword();
            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            if( isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_category = Security::secureHTML($_GET['id']);
                $category = getImageFromCategory($id_category, $_SESSION['user']['id']);
            }
            else
            {
                throw new Exception("Identifiant non reconnu");
            }

            // var_dump('<pre>');
            // var_dump($_POST['category_name']);
            // var_dump($_FILES['img_file']);
            // var_dump('</pre>');

            if(isset($_POST['category_name']) && !empty($_POST['category_name']))
            {
                
                if($_POST['category_name'] != $category['name'])
                {
                    $name_category = Security::secureHTML($_POST['category_name']);
                    updateCategoryName($id_category, $name_category, $_SESSION['user']['id']);

                    if(isset($_FILES['img_file']['size']) && empty($_FILES['img_file']['size']))
                    {
                        $alert_message = "Catégorie modifiée avec succes";
                        $alert_type = ALERT_SUCCESS;
        
                        $_SESSION['alert_message'] = $alert_message;
                        $_SESSION['alert_type'] = $alert_type;
        
                        header ('Location: category&id='.$id_category);
                    }
                }
                else
                {
                    $name_category = $category['name'];
                }
            }
            else
            {
                $name_category = $category['name'];
            }

            if(isset($_FILES['img_file']['size']) && !empty($_FILES['img_file']['size']))
            {
                try
                {
                    $oldimage = getImageFromCategory($id_category, $_SESSION['user']['id']);

                    // add and resize new icon in FOLDER
                    $fileImage = $_FILES['img_file'];
                    $dir = "public/sources/images/icons";
                    // create folder if not exist
                    if(!file_exists($dir)) mkdir($dir,0777);
                    $directory = $dir."/user".$_SESSION['user']['id']."/";
                    $filename =  explode('.', $fileImage['name']); // 0-name 1-extension
                    $filename_category = cleanString($filename[0]);
                    $imgName = addImg($fileImage, $directory,$filename_category);
                    image_resize($directory.$imgName,$directory.$imgName,50,50);

                    // Add new icon in BDD
                    $description = "Image representant la catégorie ".$name_category;
                    $id_image =  insertImageIntoBD($filename_category, $fileImage['name'], $description, $_SESSION['user']['id']);

                    // Add new image to Category in BDD
                    $updated = updateCategoryImage($id_category, $id_image, $_SESSION['user']['id']);

                    // Delete old icon in FOLDER and in BDD
                    if(deleteImage($oldimage['id'], $_SESSION['user']['id'])<1)
                    {
                        throw new Exception ("la suppression n'a pas fonctionné en BD");
                    }
                    $url = "public/sources/images/icons/user".$_SESSION['user']['id']."/".$oldimage['url'];
                    deleteFile($url);
                }
                catch(Exception $e)
                {
                    throw new Exception("L'insertion en BD n'a pas fonctionné");
                }

                $alert_message = "Catégorie modifiée avec succes";
                $alert_type = ALERT_SUCCESS;

                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $alert_type;

                header ('Location: category&id='.$id_category);
            }
            else
            {
                if( isset($_POST['category_name']) && empty($_POST['category_name']))
                {           
                    // TODO : erreur pour chaque champs
                    $alert_message = "La categorie ne peut rester vide";
                    $alert_type = ALERT_DANGER;

                    $_SESSION['alert_message'] = $alert_message;
                    $_SESSION['alert_type'] = $alert_type;
                }
            }

            require_once "views/back/notes/editCategory.view.php";
        }
        else
        {
            throw new Exception("Accès refusé");
        }
    }

    function getPageDeleteCategory()
    {
        $title = "Supprimer notes";
        $description = "Page de suppression de notes";

        $alert = getInitAlert();
        $alert_message = $alert['message'];
        $alert_type = $alert['type'];


        if(isset($_GET['del']))
        {
            $id_category = Security::secureHTML($_GET['del']);
            
            try
            {
                $image = getImageFromCategory($id_category, $_SESSION['user']['id']);
                var_dump($image);
                if(deleteCategory($id_category,$_SESSION['user']['id'])<1){
                    throw new Exception ("la suppression n'a pas fonctionné en BD");
                }
                // return;
                $url = "public/sources/images/icons/user".$_SESSION['user']['id']."/".$image['url'];
                deleteFile($url);
                $alert_message = "La suppression de la catégorie est effective";
                $alert_type = ALERT_WARNING;
            } 
            catch(Exception $e)
            {
                $alert_message = "La suppression de la catégorie n'a pas fonctionnée";
                $alert_type = ALERT_DANGER;
            }
            $_SESSION['alert_message'] = $alert_message;
            $_SESSION['alert_type'] = $alert_type;
        }
        // getPageCategories($alert_message,$alert_type);
        header ('Location: categories');
        

    }

    function getPageNote()
    {
        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_note = Security::secureHTML($_GET['id']);
                try
                {
                    $note = getNoteFromID($id_note,$_SESSION['user']['id']);

                    $matches = findImgID($note['content']);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            $image[$i] = getImageFromID($matches[$i][1],$_SESSION['user']['id']);
                            $pattern[$i] = '[library]'.$image[$i]['id'].'[/library]';
                            $replace[$i] = createImgTag($image[$i]);
                        }
                        $note['content'] = str_replace($pattern, $replace, $note['content']);
                    }

                    $title = "Note - ".$note['name_category'];
                    $description = "Page permettant de voir une note";
                    $menu_state = MENU_STATE_BREADCRUMB;
                    $MyBreadcrumb = new MyBreadcrumb();
                    $MyBreadcrumb->add('Notes', 'categories');
                    $MyBreadcrumb->add($note['name_category'], 'category&id='.$note['id_category']);
                    $MyBreadcrumb->add('Note', '#');
                    $breadcrumb = $MyBreadcrumb->breadcrumb();
                    
                    require_once "views/back/notes/note.view.php";
                }
                catch(Exception $e)
                {
                    throw new Exception("aucune note correspondante");
                }
            }
            else
            {
                throw new Exception("Identifiant de note inexistant");
            }

        }
    }

    function getPageAddNote()
    {
        $title = "Ajout d'une note";
        $description = "Page permettant l'ajout d'une note";
        $menu_state = MENU_STATE_BREADCRUMB;
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');

        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            $images = getUnusedImages($_SESSION['user']['id']);
            // var_dump ('<pre>'); 
            // var_dump($images);
            // var_dump ('</pre>'); 
        
            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_category = Security::secureHTML($_GET['id']);
            }
            if(isset($_POST['id']) && !empty($_POST['id']))
            {
                $id_category = Security::secureHTML($_POST['id']);
            }

            $category = getCategoryFromID($id_category, $_SESSION['user']['id']);
            if($category === false)
            {
                throw new Exception("Acces interdit - Identifiant incorrect");
            }
            $MyBreadcrumb->add($category['name'], 'category&id='.$id_category);


            if( isset($_POST['title']) && !empty($_POST['title']) 
                && isset($_POST['content']) && !empty($_POST['content'])
                )
            {
                $title = Security::secureHTML($_POST['title']);
                $content = Security::secureHTML($_POST['content']);
                if(isset($_POST['tags']) && !empty($_POST['tags']) )
                    $tags = Security::secureHTML($_POST['tags']);
                else
                    $tags="";
                try
                {
                    $id_note = insertNoteFromCategory($title, $content, $tags, $id_category, $_SESSION['user']['id']);
                    $note = getNoteFromID($id_note,  $_SESSION['user']['id']);

                    // Start insert note uses image ***********
                    $matches = findImgID($note['content']);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            if(getImageFromID($matches[$i][1],$_SESSION['user']['id']))
                            {
                                insertNoteUsesImg($note['id'], $matches[$i][1]);
                            }
                        }
                    }
                    // End uses ********************************

                    $alert_message = "La note a été ajoutée";
                    $alert_type = ALERT_SUCCESS;
                    $_SESSION['alert_message'] = $alert_message;
                    $_SESSION['alert_type'] = $alert_type;
                    header ('Location: note&id='.$id_note);
                }
                catch(Exception $e)
                {
                    throw new Exception("Enregistrement de la note impossible");
                }
            }
            else
            {
                if(isset($_POST['title']) && empty($_POST['title']))
                {
                    $alert_message = "Le titre ne peut être laissé vide";
                    $alert_type = ALERT_DANGER;
                }
                elseif(isset($_POST['content']) && empty($_POST['content']))
                {
                    $alert_message = "Le contenu ne peut être laissé vide";
                    $alert_type = ALERT_DANGER;
                }
                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $alert_type;
            }
            
            $MyBreadcrumb->add('Ajout Note', '#');
            $breadcrumb = $MyBreadcrumb->breadcrumb();

            require_once "views/back/notes/addNote.view.php";
        }
        else 
        {
            throw new Exception("Acces interdit si vous n'êtes pas authentifié");
        }
    }

    function getPageEditNote()
    {
        // var_dump($_GET);
        $title = "Edition d'une note";
        $description = "Page permettant l'édition d'une note";
        $menu_state = MENU_STATE_BREADCRUMB;
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');

        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            if(isset($_POST['id_note']) && !empty($_POST['id_note'])
            && isset($_POST['title']) && !empty($_POST['title'])
            && isset($_POST['content']) && !empty($_POST['content'])
            && isset($_POST['tags'])
            && isset($_POST['id_category']) && !empty($_POST['id_category']))
            {
                try 
                {
                    $id_note = Security::secureHTML($_POST['id_note']);
                    $id_category = Security::secureHTML($_POST['id_category']);
                    $title = Security::secureHTML($_POST['title']);
                    $content = Security::secureHTML($_POST['content']);
                    $tags = Security::secureHTML($_POST['tags']);
        
                    $note_updated = updateNoteFromUser($id_note, $title, $content, 
                        $tags, $id_category, $_SESSION['user']['id']);

                    // Start update note uses image ***********
                    $note = getNoteFromID($id_note, $_SESSION['user']['id']);

                    $matches = findImgID($note['content']);
                    // delete all uses and insert afterward
                    deleteAllNoteUsesImg($id_note);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            if(getImageFromID($matches[$i][1],$_SESSION['user']['id']))
                            {
                                insertNoteUsesImg($note['id'], $matches[$i][1]);
                            }
                        }
                    }
                    // End uses ********************************
                    
                    header ('Location: note&id='.$id_note);
                } 
                catch (Exception $e) 
                {
                    throw new Exception("Erreur lors de l'insertion en base de donnée");
                }
            }
            else
            {
                if(isset($_POST['title']) && empty($_POST['title']))
                {
                    $alert_message = "Le titre ne peut rester vide";
                    $alert_type = ALERT_DANGER;
                }
                elseif(isset($_POST['content']) && empty($_POST['content']))
                {
                    $alert_message = "Le contenu ne peut être laissé vide";
                    $alert_type = ALERT_DANGER;
                }
                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $alert_type;
                try
                {
                    if(isset($_GET['id']) && !empty($_GET['id']))
                        $id_note = Security::secureHTML($_GET['id']);
                    elseif(isset($_POST['id_note']) && !empty($_POST['id_note']))
                        $id_note = Security::secureHTML($_POST['id_note']);
                    else
                        throw new Exception("Identifiant de note inexistant");

                    $note = getNoteFromID($id_note, $_SESSION['user']['id']);
                    // Merge img from this note and unused img
                    $images = array_merge(getImagesFromNote($id_note, $_SESSION['user']['id']),
                                            getUnusedImages($_SESSION['user']['id']));

                    $matches = findImgID($note['content']);
                    // delete all uses and insert afterward ****
                    deleteAllNoteUsesImg($id_note);
                    $nbMatches = count($matches);                
                    if($nbMatches  > 0)
                    {
                        for($i=0; $i<$nbMatches; $i++ )
                        {
                            if(getImageFromID($matches[$i][1],$_SESSION['user']['id']))
                            {
                                insertNoteUsesImg($note['id'], $matches[$i][1]);
                            }
                        }
                    }
                    // End uses ********************************

                    $MyBreadcrumb->add($note['name_category'], 'category&id='.$note['id_category']);
                    $MyBreadcrumb->add('Edition Note', '#');
                    $breadcrumb = $MyBreadcrumb->breadcrumb();
                    require_once "views/back/notes/editNote.view.php";
                }
                catch(Exception $e)
                {
                    throw new Exception("aucune note correspondante");
                }
            }
            

        }
    }

    function getPageDeleteNote()
    {
        $title = "Supprimer note";
        $description = "Page de suppression de note";

        $alert = getInitAlert();
        $alert_message = $alert['message'];
        $alert_type = $alert['type'];


        if(isset($_GET['del']))
        {
            $id_note = Security::secureHTML($_GET['del']);
            
            try
            {
                deleteAllNoteUsesImg($id_note);
                if(deleteNote($id_note,$_SESSION['user']['id'])<1)
                {
                    throw new Exception ("la suppression de la note n'a pas fonctionné");
                }
                $alert_message = "La note à été suprimée";
                $alert_type = ALERT_WARNING;
            } catch(Exception $e){
                $alert_message = "La suppression de la catégorie n'a pas fonctionnée";
                $alert_type = ALERT_DANGER;
            }
            $_SESSION['alert_message'] = $alert_message;
            $_SESSION['alert_type'] = $alert_type;
        }
        // return;
        // getPageCategories($alert_message,$alert_type);
        header ('Location: categories');

    }

    function getPageLibrary()
    {
        $title = "Bibliotheque d'images";
        $description = "Page regroupant les images de l'utilisateur";

        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            try 
            {
                $images = getUnusedImages($_SESSION['user']['id']);
            } 
            catch (Exception $e) 
            {
                throw new Exception("Impossible de récupérer la page existante");
            }

            $menu_state = MENU_STATE_BREADCRUMB;
            $MyBreadcrumb = new MyBreadcrumb();
            $MyBreadcrumb->add('Bibliotheque', '#');
            $breadcrumb = $MyBreadcrumb->breadcrumb();
            
            require_once "views/back/images/library.view.php";
        }
    }

    function getPageAddImage()
    {
        $title = "Ajout de d'image";
        $description = "Page permettant l'ajout d'images'";
        $menu_state = MENU_STATE_BREADCRUMB;
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Ajout d\'images', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        if(Security::checkAccessSession())
        {
            // Security::generateCookiePassword();
            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            if(!empty($_FILES))
            {
                // var_dump('<pre>');
                // var_dump($_FILES);
                // var_dump('</pre>');

                // $url = Security::secureHTML($_POST['url']);
                // $description = Security::secureHTML($_POST['description']);
                
                try
                {
                    // var_dump('<pre>');
                    $nb_files = count($_FILES['img_file']['name']);
                    // var_dump('nb : '.$nb_files);

                    for($i=0; $i<$nb_files; $i++)
                    {
                        // var_dump($_FILES['img_file']);
                        
                        $filename =  explode('.', $_FILES['img_file']['name'][$i]); // 0-name 1-extension
                        $name = cleanString($filename[0]);
                        $tempName = $name;
                        $j = 1;
                        // if image name exists, change his name as long as needed
                        while(getIfImageExist($tempName,$_SESSION['user']['id']) > 0)
                        {
                            $tempName = $name.$j;
                            $j++;
                        }
                        $fileImage['name'] = $_FILES['img_file']['name'][$i];
                        $fileImage['type'] = $_FILES['img_file']['type'][$i];
                        $fileImage['tmp_name'] = $_FILES['img_file']['tmp_name'][$i];
                        $fileImage['error'] = $_FILES['img_file']['error'][$i];
                        $fileImage['size'] = $_FILES['img_file']['size'][$i];

                        // create folder if not exist
                        $dir = "public/sources/images/images";
                        if(!file_exists($dir)) mkdir($dir,0777);
                        $directory = $dir."/user".$_SESSION['user']['id']."/";
                        
                        $tmp_name_img = cleanString($tempName);
                        $imgName = addImg($fileImage, $directory,$tmp_name_img);
                        
                        $description = "Image representant ".$name;
                        $id_image =  insertImageIntoBD($tempName, $imgName, $description, $_SESSION['user']['id']);
                        }
                        // var_dump('</pre>');
                        // return;
                }
                catch(Exception $e)
                {
                    throw new Exception("Erreur lors de l'insertion de l'image");
                }

                if($nb_files > 1)
                    $alert_message = "Les images ont été ajoutées";
                else
                    $alert_message = "L'image ".$tempName." a été ajoutée";

                $alert_type = ALERT_SUCCESS;
                $_SESSION['alert_message'] = $alert_message;
                $_SESSION['alert_type'] = $alert_type;
                // header ('Location: image&id='.$id_image);
                header ('Location: library');
                // getPageLibrary($alert_message, $alert_type);
                // exit();
            }
            else
            {
                // if(!empty($_POST))
                // {           
                //     // TODO : erreur pour chaque champs
                //     $alert_message = "Erreur lors de l'ajout";
                //     $alert_type = ALERT_DANGER;
                // }
            }


            require_once "views/back/images/addImage.view.php";
        }
        else 
        {
            throw new Exception("Acces interdit si vous n'êtes pas authentifié");
        }
    }

    function getPageSearch()
    {
        $alert_message = "";
        $alert_type="";
        $title = "Recherche";
        $description = "Page de recherche";

        $menu_state = MENU_STATE_BREADCRUMB;
        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Resultats de la recherche', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            $alert = getInitAlert();
            $alert_message = $alert['message'];
            $alert_type = $alert['type'];

            if(isset($_POST['search']))
            {
                $search = Security::secureHTML($_POST['search']);
                if(strlen($search) <= 0)
                {
                    $alert_message = 'La recherche ne peut être laissée vide';
                    $alert_type = ALERT_DANGER;
                    $_SESSION['alert_message'] = $alert_message;
                    $_SESSION['alert_type'] = $alert_type;
                    header ('Location: home');
                } 
            }
            else
            {
                throw new Exception("Erreur d'acces !");
            }


            try 
            {
                $id_note = getNoteIDFromTitle($search, $_SESSION['user']['id']);
                if($id_note)
                {
                    header ('Location: note&id='.$id_note);
                }
                else
                {
                    // cut string into array and delete empty slots with array_filter
                    $result = array_filter(explode(" ", $search));

                    $reqArray = array();
                    foreach($result as $key => $value)
                    {
                        $wordSearch = getSearch($value, $_SESSION['user']['id']);
                        $reqArray = array_merge($wordSearch, $reqArray);
                    }

                    $newArr = array(); // new array without duplication id
                    $arTemp = array(); // contains id to avoid
                    // Eliminate data duplication
                    foreach($reqArray as $ar)
                    {
                        if(!in_array($ar['id'], $arTemp)) 
                        {
                            $newArr[] = $ar;
                            $arTemp[] = $ar['id'];
                        }
                    }

                    $min_return = 0;
                    $notes = array_slice($newArr,$min_return,LIMIT_SEARCH_RETURN);
                    require_once "views/back/notes/search.view.php";
                }
            } 
            catch (Exception $e) 
            {
                throw new Exception("Impossible de récupérer la page existante");
            }        
        }
        else
        {
            echo "Tu recherches <strong> $search </strong> et tu n'a rien trouvé ?<br />C'est normal :)";
        }
    }

    function getPageDeleteImage()
    {

        $title = "Supprimer image";
        $description = "Page de suppression d'image";

        $alert = getInitAlert();
        $alert_message = $alert['message'];
        $alert_type = $alert['type'];


        if(isset($_GET['del']))
        {
            $id_image = Security::secureHTML($_GET['del']);
            
            try
            {
                $image = getImageFromID($id_image, $_SESSION['user']['id']);
                if(deleteImage($id_image,$_SESSION['user']['id'])<1){
                    throw new Exception ("la suppression n'a pas fonctionné en BD");
                }
                $url = "public/sources/images/images/user".$_SESSION['user']['id']."/".$image['url'];
                deleteFile($url);
                $alert_message = "La suppression de l'image est effective";
                $alert_type = ALERT_WARNING;
            } catch(Exception $e){
                $alert_message = "La suppression de l'image n'a pas fonctionnée";
                $alert_type = ALERT_DANGER;
            }
            $_SESSION['alert_message'] = $alert_message;
            $_SESSION['alert_type'] = $alert_type;
        }
        // getPageCategories($alert_message,$alert_type);
        header ('Location: library');

    }


    // Actuellement, il ne supprime que toutes ses données mais pas l'utilisateur
    function getPageDeleteUser()
    {
        $stmt = deleteUser($_SESSION['user']['id']);

        $folder = 'public/sources/images/';
        deleteFolder($folder.'icons/user'.$_SESSION['user']['id']);
        deleteFolder($folder.'images/user'.$_SESSION['user']['id']);

        $alert_message = "Suppression de toutes les données concernant ".$_SESSION['user']['pseudo'];
        $alert_type= ALERT_DANGER;
        // getPageHomeLogged($alert_message, $alert_type);
        $_SESSION['alert_message'] = $alert_message;
        $_SESSION['alert_type'] = $alert_type;
        header ("Location: home");
    }

    /**
     * Delete all data from folder and his subfolders
     */
    function deleteFolder($dir) {
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

}




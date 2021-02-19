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

class CategoryController
{
    function getPageCategory()
    {
        if(Security::checkAccess())
        {
            Security::generateCookiePassword();

            // $alert = getInitAlert();
            // $alert_message = $alert['message'];
            // $alert_type = $alert['type'];

            if(isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_category = Security::secureHTML($_GET['id']);
            }
            else
            {
                throw new Exception("Identifiant de categorie inexistant");
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

            // $alert = getInitAlert();
            // $alert_message = $alert['message'];
            // $alert_type = $alert['type'];

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

        // $alert = getInitAlert();
        // $alert_message = $alert['message'];
        // $alert_type = $alert['type'];

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
            // $alert = getInitAlert();
            // $alert_message = $alert['message'];
            // $alert_type = $alert['type'];

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

        // $alert = getInitAlert();
        // $alert_message = $alert['message'];
        // $alert_type = $alert['type'];


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

}




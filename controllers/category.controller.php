<?php

class CategoryController
{    
    /**
     * getPageCategory
     *
     * @return void
     */
    function getPageCategory()
    {
        $alert = Security::checkAlert();
        // var_dump($alert);
        // return;
        $title = "Page des notes";
        $description = "Page regroupant toutes vos notes pour une catégorie";
        
        if(Security::checkAccess())
        {        
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
                $pageNum = (!empty($_GET['pageNum']) ? Security::secureHTML($_GET['pageNum']) : 1);
                $ResultNumber = getResultNumberFromCategory($pageNum, $id_category, $id_user);
                $notes = getNotesFromCategory($pageNum, $id_category, $id_user);
                $category = getCategoryFromID($id_category, $id_user);     
                
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
                  
            $MyBreadcrumb = new MyBreadcrumb();
            $MyBreadcrumb->add('Notes', 'categories');
            $MyBreadcrumb->add($category['name'], '');      
            $breadcrumb = $MyBreadcrumb->breadcrumb();
            require_once "views/back/categories/category.view.php";
        } 
        else 
        {
            throw new Exception("Accès interdit si vous n'êtes pas authentifié");
        }
    }

    
    /**
     * getPageCategories
     *
     * @return void
     */
    function getPageCategories()
    {
        $alert = Security::checkAlert();
        $title = "Page des notes";
        $description = "Page regroupant toutes vos notes par categories";

        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', '');        
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        if(Security::checkAccess())
        {    
            $categories = array();
            if(getIfCategoriesExist($_SESSION['user']['id']))
            {
                $categories = getCategories($_SESSION['user']['id']);
            }
            require_once "views/back/categories/categories.view.php";
        } 
        else 
        {
            throw new Exception("Accès interdit si vous n'êtes pas authentifié");
        }
    }

    
    /**
     * getPageAddCategory
     *
     * @return void
     */
    function getPageAddCategory()
    {
        $alert = Security::checkAlert();
        $title = "Ajout de catégorie";
        $description = "Page permettant l'ajout de categories";

        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Ajout Catégorie', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        if(Security::checkAccess())
        {
            if(isset($_FILES) && isset($_POST['category_name']) && !empty($_POST['category_name']))
            {
                $name_category = Security::secureHTML($_POST['category_name']);

                if(!getIfCategoryExist($name_category, $_SESSION['user']['id']))
                {
                    try // Insert Img file
                    {
                        $fileImage = $_FILES['img_file'];
                        $dir = USER_DIRECTORY."icons";
                        // create folder if not exist
                        if(!file_exists($dir)) mkdir($dir,0777);
                        $directory = $dir."/user".$_SESSION['user']['id']."/";
                        $tmp_name_category = cleanString($name_category);
                        $imgName = addImg($fileImage, $directory,$tmp_name_category);
                        image_resize($directory.$imgName,$directory.$imgName,50,50);
                    }
                    catch(Exception $e)
                    {
                        throw new Exception("Une erreur s'est produite pendant l'insertion du fichier");
                    }
                    // Insert category into BDD
                    $description = "Image representant la catégorie ".$name_category;
                    $id_image =  insertImageIntoBD($name_category, $imgName, $description, $_SESSION['user']['id']);
                    $id_category = insertCategoryIntoBD($name_category, $id_image, $_SESSION['user']['id']);
                }
                else
                {
                    $alert['msg'] = "La catégorie \"".$name_category."\" existe déjà";
                    $alert['type'] = ALERT_WARNING;
                    $_FILES = null;
                    require_once "views/back/categories/addCategory.view.php";
                    return;
                }
                $alert['msg'] = "La catégorie \"".$name_category."\" a été ajoutée";
                $alert['type'] = ALERT_SUCCESS;
                Alert::setAlert($alert);
                header ('Location: category&id='.$id_category);
                return;
            }
            else
            {
                if(!empty($_POST))
                {   
                    if(!$_POST['category_name'])
                    {
                        $alert['msg'] = "Le nom de la catégorie ne peut être vide";
                        $alert['type'] = ALERT_DANGER;
                    }   
                    else
                    {
                        $alert['msg'] = "Une erreur inattendue s'est produite";
                        $alert['type'] = ALERT_DANGER;
                    }
                }
            }
            require_once "views/back/categories/addCategory.view.php";
        }
        else 
        {
            throw new Exception("Accès interdit si vous n'êtes pas authentifié");
        }
    }
    
    /**
     * getPageEditCategory
     *
     * @return void
     */
    function getPageEditCategory()
    {
        $alert = Security::checkAlert();
        $title = "Edition de catégorie";
        $description = "Page permettant l'édition de categories";

        $MyBreadcrumb = new MyBreadcrumb();
        $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Edition Catégorie', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();
        
        if(Security::checkAccess())
        {

            if( isset($_GET['id']) && !empty($_GET['id']))
            {
                $id_category = Security::secureHTML($_GET['id']);
                $category = getImageFromCategory($id_category, $_SESSION['user']['id']);
            }
            else
            {
                throw new Exception("Identifiant non reconnu");
            }

            if(isset($_POST['category_name']) && !empty($_POST['category_name']))
            {
                
                if($_POST['category_name'] != $category['name'])
                {
                    $name_category = Security::secureHTML($_POST['category_name']);
                    updateCategoryName($id_category, $name_category, $_SESSION['user']['id']);

                    if(isset($_FILES['img_file']['size']) && empty($_FILES['img_file']['size']))
                    {
                        $alert['msg'] = "Catégorie modifiée avec succes";
                        $alert['type'] = ALERT_SUCCESS;        
                        Alert::setAlert($alert);        
                        header ('Location: category&id='.$id_category);
                        return;
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
            {                try
                {
                    // Get old image reference
                    $oldimage = getImageFromCategory($id_category, $_SESSION['user']['id']);

                    $fileImage = $_FILES['img_file'];
                    $dir = USER_DIRECTORY."icons";
                    // create folder if not exist
                    if(!file_exists($dir)) mkdir($dir,0777);
                    $directory = $dir."/user".$_SESSION['user']['id']."/";
                    $tmp_name_category = cleanString($name_category);
                    $imgName = addImg($fileImage, $directory,$tmp_name_category);
                    image_resize($directory.$imgName,$directory.$imgName,50,50);

                    $description = "Image representant la catégorie ".$name_category;
                    $id_image =  insertImageIntoBD($name_category, $imgName, $description, $_SESSION['user']['id']);

                    // Add new image to Category in BDD
                    $updated = updateCategoryImage($id_category, $id_image, $_SESSION['user']['id']);

                    // Delete old icon in FOLDER and in BDD
                    if(deleteImage($oldimage['id'], $_SESSION['user']['id'])<1)
                    {
                        throw new Exception ("la suppression n'a pas fonctionné en BD");
                    }
                    $url = USER_DIRECTORY."icons/user".$_SESSION['user']['id']."/".$oldimage['url'];
                    deleteFile($url);
                }
                catch(Exception $e)
                {
                    throw new Exception("L'insertion en BD n'a pas fonctionné");
                }

                $alert['msg'] = "Catégorie modifiée avec succès";
                $alert['type'] = ALERT_SUCCESS;
                Alert::setAlert($alert);
                header ('Location: category&id='.$id_category);
                return;
            }
            else
            {
                if( isset($_POST['category_name']) && empty($_POST['category_name']))
                {
                    $alert['msg'] = "La categorie ne peut rester vide";
                    $alert['type'] = ALERT_DANGER;
                    Alert::setAlert($alert);
                }
            }
            require_once "views/back/categories/editCategory.view.php";
        }
        else
        {
            throw new Exception("Accès refusé");
        }
    }
    
    /**
     * getPageDeleteCategory
     *
     * @return void
     */
    function getPageDeleteCategory()
    {
        $title = "Supprimer notes";
        $description = "Page de suppression de notes";

        if(Security::checkAccess())
        {
            if(isset($_GET['del']))
            {
                $id_category = Security::secureHTML($_GET['del']);
                
                try
                {
                    $image = getImageFromCategory($id_category, $_SESSION['user']['id']);
                    if(deleteCategory($id_category,$_SESSION['user']['id'])<1){
                        throw new Exception ("la suppression n'a pas fonctionné en BD");
                    }
                    // return;
                    $url = USER_DIRECTORY."icons/user".$_SESSION['user']['id']."/".$image['url'];
                    deleteFile($url);
                    $alert['msg'] = "La suppression de la catégorie est effective";
                    $alert['type'] = ALERT_WARNING;
                } 
                catch(Exception $e)
                {
                    $alert['msg'] = "La suppression de la catégorie n'a pas fonctionné";
                    $alert['type'] = ALERT_DANGER;
                }
                Alert::setAlert($alert);
            }
            header ('Location: categories');
        }
        else
        {
            throw new Exception("Accès refusé");
        }
    }

}




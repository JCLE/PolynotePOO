<?php

class NoteController
{
    function getPageLibrary()
    {
        $alert = Security::checkAlert();
        $title = "Bibliotheque d'images";
        $description = "Page regroupant les images de l'utilisateur";

        if(Security::checkAccess())
        {
            Security::generateCookiePassword();
    
            try 
            {
                $images = getUnusedImages($_SESSION['user']['id']);
            } 
            catch (Exception $e) 
            {
                throw new Exception("Impossible de récupérer la page existante");
            }    
            
            $MyBreadcrumb = new MyBreadcrumb();
            $MyBreadcrumb->add('Bibliotheque', '#');
            $breadcrumb = $MyBreadcrumb->breadcrumb();
            require_once "views/back/images/library.view.php";
        }
    }

    function getPageAddImage()
    {
        $alert = Security::checkAlert();
        $title = "Ajout de d'image";
        $description = "Page permettant l'ajout d'images'";

        $MyBreadcrumb = new MyBreadcrumb();
        // $MyBreadcrumb->add('Notes', 'categories');
        $MyBreadcrumb->add('Ajout d\'images', '#');
        $breadcrumb = $MyBreadcrumb->breadcrumb();

        if(Security::checkAccess())
        {
            if(!empty($_FILES))
            {
                try
                {
                    $nb_files = count($_FILES['img_file']['name']);

                    for($i=0; $i<$nb_files; $i++)
                    {                        
                        $filename =  explode('.', $_FILES['img_file']['name'][$i]); 
                        // 0-name 1-extension
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
                }
                catch(Exception $e)
                {
                    throw new Exception("Erreur lors de l'insertion de l'image");
                }

                if($nb_files > 1)
                    $alert['msg'] = "Les images ont été ajoutées";
                else
                    $alert['msg']  = "L'image \"".$filename[0]."\" a été ajoutée";

                $alert_type = ALERT_SUCCESS;
                $_SESSION['alert']['msg'] = $alert['msg'];
                $_SESSION['alert']['type'] = $alert['type'];
                header ('Location: library');
            }

            require_once "views/back/images/addImage.view.php";
        }
        else 
        {
            throw new Exception("Acces interdit si vous n'êtes pas authentifié");
        }
    }


    function getPageDeleteImage()
    {
        $title = "Supprimer image";
        $description = "Page de suppression d'image";

        if(isset($_GET['del']))
        {
            $id_image = Security::secureHTML($_GET['del']);
            
            try
            {
                $image = getImageFromID($id_image, $_SESSION['user']['id']);
                if(deleteImage($id_image,$_SESSION['user']['id'])<1)
                {
                    throw new Exception ("Aucune image supprimée");
                }
                $url = "public/sources/images/images/user".$_SESSION['user']['id']."/".$image['url'];
                deleteFile($url);
                $alert['msg'] = "La suppression de l'image est effective";
                $alert['type'] = ALERT_WARNING;
            } catch(Exception $e){
                $alert['msg'] = "La suppression de l'image n'a pas fonctionné";
                $alert['type'] = ALERT_DANGER;
            }
            $_SESSION['alert']['msg'] = $alert['msg'];
            $_SESSION['alert']['type'] = $alert['type'];
        }
        header ('Location: library');

    }

}
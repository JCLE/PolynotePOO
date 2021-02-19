<?php
// require_once "public/useful/formatting.php";
// require_once "models/user.dao.php";
// require_once "models/note.dao.php";
// require_once "models/category.dao.php";
// require_once "models/image.dao.php";
// require_once "public/useful/MyBreadcrumb.php"; 
// require_once "public/useful/imgManager.php";
// require_once "public/useful/alertManager.php";
// require_once "models/image.dao.php";
// require_once "models/admin.dao.php";
// require_once "config/config.php";

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
    
            $menu_state = MENU_STATE_BREADCRUMB; // TODO : refactoriser ... ou pas
            $MyBreadcrumb = new MyBreadcrumb();
            $MyBreadcrumb->add('Bibliotheque', '#');
            $breadcrumb = $MyBreadcrumb->breadcrumb();
            
            require_once "views/back/images/library.view.php";
        }
    }
}




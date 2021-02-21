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

}
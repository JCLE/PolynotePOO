<?php 
require_once "public/useful/formatting.php";
// require_once "controllers/backend.controller.php";
require_once "config/config.php";

class FrontendController
{
    function getPageHome()
    {
        if(Security::checkAccess())
        {
            Security::generateCookiePassword();
            // ************************* ALERT MESSAGE INIT **************************
            // $alert = getInitAlert();
            // $alert_message = $alert['message'];
            // $alert_type = $alert['type'];
            // ************************ END ALERT MESSAGE INIT ************************
            $backendController = new BackendController();
            $backendController->getPageHome();
        }
        else
        {
            $title = "Page d'accueil";
            $menu_state = MENU_STATE_INITIAL;
            $description = "Bienvenue sur Polynote";
            require_once "views/front/home.view.php";
         }
    }
}

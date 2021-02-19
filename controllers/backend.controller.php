<?php
require_once "public/useful/formatting.php";
require_once "models/user.dao.php";
require_once "models/note.dao.php";
require_once "models/category.dao.php";
require_once "models/image.dao.php";
require_once "public/useful/MyBreadcrumb.php"; 
require_once "public/useful/imgManager.php";
// require_once "controllers/category.controller.php";
require_once "controllers/user.controller.php";
require_once "controllers/frontend.controller.php";
require_once "controllers/note.controller.php";
// require_once "public/useful/alertManager.php";
// require_once "models/image.dao.php";
// require_once "models/admin.dao.php";
require_once "config/config.php";

// $categoryController = new CategoryController();

class BackendController
{
    /**
     * HOME PAGE LOGGED
     */
    public function getPageHome()
    {
        // $alert = Security::checkAlert();
        if(isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            $menu_state = MENU_STATE_LOGGED; // TODO : refactoriser menu
            $title = "Page d'accueil de ".$_SESSION['user']['pseudo'];
            $description = "Bienvenue sur Polynote";
            require_once "views/front/home.view.php";
        }
        else
        {
            $frontendController = new FrontendController();
            $frontendController->getPageHome();
        }
    }

    /**
     * LOGIN PAGE
     */
    public function getPageLogin()
    {
        $userController = new UserController();
        $userController->getPageLogin();
    }

    /**
     * REGISTER PAGE
     */
    public function getPageRegister()
    {
        $userController = new UserController();
        $userController->getPageRegister();
    }

    /**
     * LOGOUT PAGE
     */
    function getPageLogout()
    {
        $userController = new UserController();
        $userController->getPageLogout();
    }

    /**
     * LIBRARY PAGE
     */
    function getPageLibrary()
    {
        $noteController = new NoteController();
        $noteController->getPageLibrary();
    }
}


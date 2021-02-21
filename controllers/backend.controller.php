<?php
require_once "public/useful/formatting.php";
require_once "models/user.dao.php";
require_once "models/note.dao.php";
require_once "models/category.dao.php";
require_once "models/image.dao.php";
require_once "public/useful/MyBreadcrumb.php"; 
require_once "public/useful/imgManager.php";
require_once "controllers/user.controller.php";
require_once "controllers/frontend.controller.php";
require_once "controllers/category.controller.php";
require_once "controllers/note.controller.php";

// require_once "public/useful/alertManager.php";
// require_once "models/image.dao.php";
// require_once "models/admin.dao.php";
require_once "config/config.php";
require_once "config/Alert.class.php";

// $categoryController = new CategoryController();

class BackendController
{
    private $frontendController;
    private $noteController;
    private $userController;
    private $categoryController;

    function __construct()
    {
        $this->noteController = new NoteController();
        $this->userController = new UserController();
        $this->frontendController = new FrontendController();
        $this->categoryController = new CategoryController();
    }

    /**
     * HOME PAGE LOGGED
     */
    public function getPageHome()
    {
        if(isset($_SESSION['user']) && !empty($_SESSION['user']))
        {
            $title = "Page d'accueil de ".$_SESSION['user']['pseudo'];
            $description = "Bienvenue sur Polynote";
            require_once "views/front/home.view.php";
        }
        else
        {
            $this->frontendController->getPageHome();
        }
    }

    /**
     * LOGIN PAGE
     */
    public function getPageLogin()
    {
        $this->userController->getPageLogin();
    }

    /**
     * REGISTER PAGE
     */
    public function getPageRegister()
    {
        $this->userController->getPageRegister();
    }

    /**
     * LOGOUT PAGE
     */
    function getPageLogout()
    {
        $this->userController->getPageLogout();
    }

    /**
     * LIBRARY PAGE
     */
    function getPageLibrary()
    {
        // $noteController = new NoteController();
        $this->noteController->getPageLibrary();
    }

    /**
     * ADD IMAGE TO LIBRARY
     */
    function getPageAddImage()
    {
        // $noteController = new NoteController();
        $this->categoryController->getPageAddImage();
    }

    /**
     * DELETE IMAGE FROM LIBRARY
     */
    function getPageDeleteImage()
    {
        $this->categoryController->getPageDeleteImage();
    }
    
    /**
     * DISPLAY CATEGORY
    */
    function getPageCategory()
    {
        $this->categoryController->getPageCategory();
    }

    /**
     * DISPLAY ALL CATEGORIES
     */
    function getPageCategories()
    {
        $this->categoryController->getPageCategories();
    }

    /**
     * ADD CATEGORY
     */
    function getPageAddCategory()
    {
        $this->categoryController->getPageAddCategory();
    }

    /**
     * EDIT CATEGORY
     */
    function getPageEditCategory()
    {
        $this->categoryController->getPageEditCategory();
    }

    /**
     * DELETE CATEGORY
     */
    function getPageDeleteCategory()
    {
        $this->categoryController->getPageDeleteCategory();
    }
}


<?php
require_once "public/useful/formatting.php";
/*                          *************   Changer l'emplacement des DAO  *************   */
// Pourquoi pas dans les classes correspondantes user dans controller user, etc...
require_once "models/user.dao.php";
require_once "models/note.dao.php";
require_once "models/category.dao.php";
require_once "models/image.dao.php";
require_once "public/useful/MyBreadcrumb.php"; 
require_once "public/useful/imgManager.php";
require_once "controllers/user.controller.php";
require_once "controllers/frontend.controller.php";
require_once "controllers/category.controller.php";
require_once "controllers/library.controller.php";
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
        $this->libraryController = new LibraryController();
    }
 
    /**
     * getPageHome
     *
     * @return void
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

    
    // ******************** USER ********************

 
    /**
     * getPageLogin
     *
     * @return void
     */
    public function getPageLogin(){$this->userController->getPageLogin();}

    /**
     * getPageRegister
     *
     * @return void
     */
    public function getPageRegister(){$this->userController->getPageRegister();}

    /**
     * getPageLogout
     *
     * @return void
     */
    function getPageLogout(){$this->userController->getPageLogout();}



    // ******************** LIBRARY ********************

    /**
     * getPageLibrary
     *
     * @return void
     */
    function getPageLibrary(){$this->libraryController->getPageLibrary();}
   
    /**
     * getPageAddImage
     *
     * @return void
     */
    function getPageAddImage(){$this->libraryController->getPageAddImage();}

    /**
     * getPageDeleteImage
     *
     * @return void
     */
    function getPageDeleteImage(){$this->libraryController->getPageDeleteImage();}



    // ******************** CATEGORY ********************
    
    /**
     * getPageCategory
     *
     * @return void
     */
    function getPageCategory(){$this->categoryController->getPageCategory();}

    /**
     * getPageCategories
     *
     * @return void
     */
    function getPageCategories(){$this->categoryController->getPageCategories();}
 
    /**
     * getPageAddCategory
     *
     * @return void
     */
    function getPageAddCategory(){$this->categoryController->getPageAddCategory();}

    /**
     * getPageEditCategory
     *
     * @return void
     */
    function getPageEditCategory(){$this->categoryController->getPageEditCategory();}
  
    /**
     * getPageDeleteCategory
     *
     * @return void
     */
    function getPageDeleteCategory(){$this->categoryController->getPageDeleteCategory();}



    // ******************** NOTE ********************
   
    /**
     * getPageNote
     *
     * @return void
     */
    function getPageNote(){$this->noteController->getPageNote();}

    /**
     * getPageAddNote
     *
     * @return void
     */
    function getPageAddNote(){$this->noteController->getPageAddNote();}

    /**
     * getPageEditNote
     *
     * @return void
     */
    function getPageEditNote(){$this->noteController->getPageEditNote();}

    /**
     * getPageDeleteNote
     *
     * @return void
     */
    function getPageDeleteNote(){$this->noteController->getPageDeleteNote();}
    
    /**
     * getPageSearch
     *
     * @return void
     */
    function getPageSearch(){$this->noteController->getPageSearch();}
    
}


<?php
session_start();
require_once "controllers/backend.controller.php";
require_once "controllers/frontend.controller.php";
require_once "config/Security.class.php";

// **************** DEVELOPMENT ENVIRONMENT ONLY ****************
require_once "controllers/development.controller.php";

$backendController = new BackendController();
$frontendController = new FrontendController();
try
{
    if(empty($_GET['page']))
    {
        $frontendController->getPageHome();
    }
    else
    {
        $url = explode("/", filter_var($_GET['page']), FILTER_SANITIZE_URL);
        switch($url[0])
        {
            case "home" : $frontendController->getPageHome();
            break;
            case "login" : $backendController->getpageLogin();
            break;
            case "register" : $backendController->getPageRegister();
            break;
            case "logout" : $backendController->getPageLogout();
            break;
            case "library" : $backendController->getPageLibrary();
            break;
            case "addimage" : $backendController->getPageAddImage();
            break;
            case "deleteimage" : $backendController->getPageDeleteImage();
            break;
            case "categories" : $backendController->getPageCategories();
            break;
            case "category" : $backendController->getPageCategory();
            break;
            case "addcategory" : $backendController->getPageAddCategory();
            break;
            case "editcategory" : $backendController->getPageEditCategory();
            break;
            case "deletecategory" : $backendController->getPageDeleteCategory();
            break;
            default : throw new Exception("La page n'existe pas");
        }
    }
}
catch(Exception $e)
{
    $msg = $e->getMessage();
    require "views/commons/error.view.php";
}




// try {
//     if(isset($_GET['url']) && !empty($_GET['url'])){
//         $url = Security::secureHTML($_GET['url']);
//         switch ($url){
//             /*
//                 ONLY FOR DEV
//             */
//             case "deleteall" : getPageDeleteAll();
//             break;
//             case "deleteuser" : getPageDeleteUser();
//             break;
//             /*
//                 END ONLY FOR DEV
//             */
//             case "home": getPageHome();
//             break;
//             case "search": getPageSearch();
//             break;
//             case "login" : getPageLogin();
//             break;
//             case "logout" : getPageLogout();
//             break;
//             case "register" : getPageRegister();
//             break;
//             case "category" : getPageCategory();
//             break;
//             case "categories" : getPageCategories();
//             break;
//             case "addcategory" : getPageAddCategory();
//             break;
//             case "editcategory" : getPageEditCategory();
//             break;
//             case "deletecategory" : getPageDeleteCategory();
//             break;
//             // case "home": getPageHomeLogged();
//             // break;
//             // case "ajaxsearch" : getAjaxRequest();
//             // break;
//             case "note" : getPageNote();
//             break;
//             case "addnote" : getPageAddNote();
//             break;
//             case "editnote" : getPageEditNote();
//             break;
//             case "deletenote" : getPageDeleteNote();
//             break;
//             case "library" : getPageLibrary();
//             break;
//             case "addimage" : getPageAddImage();
//             break;
//             case "deleteimage" : getPageDeleteImage();
//             break;
//             case "error301": 
//             case "error302": 
//             case "error400": 
//             case "error401": 
//             case "error402": 
//             case "error405": 
//             case "error500": 
//             case "error505": throw new Exception("Erreur de type : "+$url);
//             break;
//             case "error403": throw new Exception("vous n'avez pas le droit d'accéder à ce dossier");
//             break;
//             case "error404":
//             default: throw new Exception("La page n'existe pas");
//         }
//     } else {
//         getPageHome();
//     }
// } catch(Exception $e){
//     $title = "Erreur";
//     $description = "Page de gestion des erreurs";
//     $errorMessage = $e->getMessage();
//     $menu_state = MENU_STATE_BREADCRUMB;
//     $MyBreadcrumb = new MyBreadcrumb();
//     $MyBreadcrumb->add('Erreur', '');        
//     $breadcrumb = $MyBreadcrumb->breadcrumb();
//     require "views/commons/error.view.php";
// }



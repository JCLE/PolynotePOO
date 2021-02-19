<?php 
ob_start(); 

 if( isset($_SESSION['user']) && !empty($_SESSION['user']))
 { ?>
<form class=" form-group row mx-0 offset-sm-1 offset-md-1" id="form-search" name="form-search" action="search" method="POST">
    <div class="px-0 pb-2 p-md-0 offset-lg-2 col-12 col-md-9 col-lg-7">
        <input id="search" name="search" class="form-control form-control-lg" type="search" placeholder="" aria-label="recherche" autofocus autocomplete="off">
    </div>
    <button id="submit" class="btn btn-outline-info px-0 col-12 col-md-3 col-lg-1" type="submit">rechercher</button>
</form>

<div class="row justify-content-center">
    <div class="d-inline-block">
        <a style="height:6em;" href="library" class="btn btn-outline-info mx-1 d-table-cell align-middle ">
            <span class="">Bibliotheque d'images</span>
        </a>
    </div>
</div>
 <?php }
 else
 { ?>
<div class="row justify-content-center">
    <div>
        <div style="height:6em;background-color:gray;" class="d-table-cell align-middle">
            <span class="border m-4">Authentification requise</span>
        </div>
    </div>
</div>
 <?php } ?>



<!-- <div class="row text-center">
    <p class="col-12 mt-4 mt-sm-0 d-sm-block d-none">Test non visible sur portable</p>
    <br />
</div> -->

<!-- Useful to know which device is in use for javascript -->
<!-- used by SearchManager.js with getBootstrapDeviceSize() -->
<div id="users-device-size">
  <div id="xs" class="d-sm-none"></div>
  <div id="sm" class="d-md-none"></div>
  <div id="md" class="d-lg-none"></div>
  <div id="lg" class="d-xl-none"></div>
  <div id="xl" class=""></div>
</div>

<!-- <img src="public/sources/images/utiles/ajax-loader.gif" style="display:none;" id="img_ajax_wait" alt="chargement ajax" /> -->
<!-- <div id="retour" class="text-center">retour</div> -->


<script src="public/js/searchManager.js"></script>

<?php
$content = ob_get_clean();
// $menu_state = MENU_STATE_INITIAL; // TODO : refactoriser menu
// $title = "Page d'accueil";
// $description = "Bienvenue sur Polynote";
require "views/commons/template.php"
?>
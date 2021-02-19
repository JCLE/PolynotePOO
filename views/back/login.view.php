<?php 
ob_start(); 
?>


<form class="text-center" action="login" method="post">
    <h1 class="mb-3">Authentification</h1>
    <div class="form-inline row">
        <label for="pseudo" class="offset-sm-2 offset-md-2 offset-lg-2 offset-xl-4 col-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Pseudo</label>
        <input type="text" name="pseudo" class="form-control col-12 col-sm-6 col-sm-6 col-lg-6 col-xl-3" id="pseudo" aria-describedby="pseudo" placeholder="Entrer votre pseudo" autofocus value="<?=
            // isset($_POST['pseudo']) ? $_POST['pseudo'] : '';
            isset($_COOKIE[COOKIE_PSEUDO]) ? $_COOKIE[COOKIE_PSEUDO] : '';
        ?>">
    </div>
    <div class="form-inline row">
        <label for="password" class="offset-sm-2 offset-md-2 offset-lg-2 offset-xl-4 col-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Mot de passe</label>
        <input type="password" name="password" class="form-control col-12 col-sm-6 col-sm-6 col-lg-6 col-xl-3" id="password" placeholder="Entrer votre mot de passe" 
                value="<?= isset($_COOKIE[COOKIE_PASSWORD]) ? $_COOKIE[COOKIE_PASSWORD] : '' ?>">
    </div>
    <div class=" form-group text-center mb-1">
        <a href="forget_pass">Mot de passe oublié ?</a>
    </div>
    <div class="form-check mb-1">
        <input type="checkbox" class="form-check-input" id="check_log" name="remember_me" <?= isset($_COOKIE[COOKIE_PSEUDO]) ? 'checked' : '' ?>>
        <label class="form-check-label" for="check_log">Se souvenir de moi</label>
    </div>
    <button type="reset" class="btn btn-outline-danger">Reset all</button>
    <button type="submit" class="btn btn-outline-info">Valider</button>
</form>


<?php
$content = ob_get_clean();
// $menu_state = MENU_STATE_INITIAL; // TODO : refactoriser menu
// $title = "Page d'authentification";
// $description = "Page permettant de s'authentifier pour accéder au site";
require "views/commons/template.php"
?>
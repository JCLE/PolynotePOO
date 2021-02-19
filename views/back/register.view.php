<?php 
ob_start();
?>


<form class="" action="register" method="post">
    <h1 class="mb-2 text-center">Enregistrement</h1>
    <div class="form-inline  row p-1">
            <label for="email" class="offset-sm-2 offset-md-2 offset-lg-2 offset-xl-4 col-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Email</label>
            <div class="form-row col-12 col-sm-6 col-sm-6 col-lg-6 col-xl-3">
                <input type="email" name="email" class="form-control col-12 <?php 
                    if(isset($validate_email['valid'])){ echo $validate_email['valid']===true ? "is-valid" : 'is-invalid'; }
                ?>" id="email" aria-describedby="email" placeholder="Entrer un email valide" value="<?=
                    isset($_POST['email']) ? $_POST['email'] : ''
                ?>">
                <?php if(isset($validate_email['valid']) && isset($validate_email['text'])){ ?>
                    <div class="col-12 <?= $validate_email['valid']===true ? "valid-feedback" : 'invalid-feedback' ?>">
                        <?= $validate_email['text'] ?>
                    </div>
                <?php } ?>
            </div>
    </div>
    <div class="form-inline row p-1">
        <label for="pseudo" class="offset-sm-2 offset-md-2 offset-lg-2 offset-xl-4 col-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Pseudo</label>
        <div class="form-row col-12 col-sm-6 col-sm-6 col-lg-6 col-xl-3">
            <input type="text" name="pseudo" class="form-control col-12 <?php 
                        if(isset($validate_pseudo['valid'])){ echo $validate_pseudo['valid']===true ? "is-valid" : 'is-invalid'; }
                    ?>" id="pseudo" aria-describedby="pseudo" placeholder="Entrer un pseudo" value="<?=
                        isset($_POST['pseudo']) ? $_POST['pseudo'] : ''
                    ?>">
            <?php if(isset($validate_pseudo['valid']) && isset($validate_pseudo['text'])){ ?>
                <div class="col-12 <?= $validate_pseudo['valid']===true ? "valid-feedback" : 'invalid-feedback' ?>">
                    <?= $validate_pseudo['text'] ?>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="form-inline row p-1">
            <label for="password" class="offset-sm-2 offset-md-2 offset-lg-2 offset-xl-4 col-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Mot de passe</label>
            <div class="form-row col-12 col-sm-6 col-sm-6 col-lg-6 col-xl-3">
                <input type="password" name="password" class="form-control col-12 <?php 
                        if(isset($validate_password['valid'])){ echo $validate_password['valid']===true ? "is-valid" : 'is-invalid'; }
                    ?>" id="password" placeholder="Entrer un mot de passe">
                <?php if(isset($validate_password['valid']) && isset($validate_password['text'])){ ?>
                    <div class="col-12 <?= $validate_password['valid']===true ? "valid-feedback" : 'invalid-feedback' ?>">
                        <?= $validate_password['text'] ?>
                    </div>
                <?php } ?>
            </div>
    </div>
    <div class="form-inline row p-1">
            <label for="password_check" class="offset-sm-2 offset-md-2 offset-lg-2 offset-xl-4 col-12 col-sm-2 col-md-2 col-lg-2 col-xl-1">Mot de passe</label>
            <div class="form-row col-12 col-sm-6 col-sm-6 col-lg-6 col-xl-3">
                <input type="password" name="password_check" class="form-control col-12 <?php 
                        if(isset($validate_password_check['valid'])){ echo $validate_password_check['valid']===true ? "is-valid" : 'is-invalid'; }
                    ?>" id="password_check" placeholder="Entrer un mot de passe">
                <?php if(isset($validate_password_check['valid']) && isset($validate_password_check['text'])){ ?>
                    <div class="col-12 <?= $validate_password_check['valid']===true ? "valid-feedback" : 'invalid-feedback' ?>">
                        <?= $validate_password_check['text'] ?>
                    </div>
                <?php } ?>
            </div>
    </div>
    <div class="form-inline row justify-content-center">
        <button type="reset" class="btn btn-outline-danger">Reset all</button>
        <button type="submit" class="btn btn-outline-info">Valider</button>
    </div>
</form>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
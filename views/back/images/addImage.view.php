<?php 
ob_start(); 
?>

<h1 class="text-center">Ajouter une ou plusieurs images</h1>
<form method="post" action="" enctype="multipart/form-data">
    <div class="col-12 mb-2">
        <div class="form-group custom-file offset-4 col-4" id="input_file">
        <!-- Input file -->
        <input type="file" class="filestyle" data-text="Ajouter"
                id="img_file" 
                data-btnClass="btn-outline-info" 
                data-placeholder="Vide" 
                data-dragdrop="false"

                name="img_file[]"
                enctype="multipart/form-data"
                multiple="multiple">
            <!-- <input method="post" type="file" class="custom-file-input" id="img_file" name="img_file" lang="fr" accept=".jpg,.jpeg,.gif,.png" />
            <label class="custom-file-label" for="img_file">Sélectionner un fichier</label> -->
        </div>
    </div>
    <div class="col-12">
        <button type="submit" class="btn btn-outline-info offset-4 col-4">Valider</button>
    </div>
</form>


<!-- <script src="public/js/fileManager.js"></script> -->

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
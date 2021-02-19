    <?php 
ob_start(); 
?>

<h1 class="text-center">Ajouter une catégorie</h1>
<form method="post" action="" enctype="multipart/form-data">

    <div class="col-12 mb-2">
        <div class="form-group custom-file offset-4 col-4" id="input_file">
            <!-- Input file -->
            <input type="file" class="filestyle" data-text="Choisir une image"
                id="img_file" name="img_file" data-btnClass="btn-outline-info" 
                data-placeholder="Vide" data-dragdrop="false">
            <!-- <input method="post" type="file" class="custom-file-input" id="img_file" name="img_file" lang="fr" accept=".jpg,.jpeg,.gif,.png" /> -->
            <!-- <label class="custom-file-label" for="img_file">Sélectionner un fichier</label> -->
        </div>
    </div>    
    <div class="col-12 mb-2 text-center">
        <a href="addcategory" >
            <img class="col-4 p-0" id="img_receiver" src="#" alt="your image" style="width:50px;height:50px;"/>
        </a>
    </div>
    <div class="col-12 mb-2">
        <div class="form-group offset-4 col-4 text-center" id="input_name">
            <label for="exampleFormControlInput1">Nom de la catégorie</label>
            <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Nom de la catégorie">
        </div>
    </div>
    <div class="row">
    <button type="button" onclick="window.location.href='categories'"
            class="btn btn-outline-danger offset-4 col-2">Annuler</button>
        <button type="submit" class="btn btn-outline-info col-2">Valider</button>
    </div>

</form>


<script src="public/js/fileManager.js"></script>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
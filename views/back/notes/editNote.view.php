<?php 
ob_start(); 
?>

<!-- Category and img -->
<div class="row justify-content-center text-center mb-2">
    <h1 class="col-12"><?= $note["name_category"] ?></h1>
    <img src="public/sources/images/icons/user<?= $note['id_user'] 
    ?>/<?= $note['url'] ?>" alt="<?= $note['description'] ?>"/>
</div>
<form method="post" action="editnote">

    <!-- hidden note and category id -->
    <input type="hidden" id="id_note" name="id_note" value="<?=  $note['id']  ?>" />
    <input type="hidden" id="id_category" name="id_category" value="<?=  $note['id_category']  ?>" />

    <!-- Title input -->
    <div class="form-inline row p-1 justify-content-center">
        <label for="title" class="col-1">Titre</label>
        <div class="form-row col-12 col-sm-8 col-md-7 col-xl-7">
            <input type="text" name="title" class="form-control col-12 <?php 
                        //if(isset($validate_pseudo['valid'])){ echo $validate_pseudo['valid']===true ? "is-valid" : 'is-invalid'; }
                        ?>" id="title" aria-describedby="title" placeholder="Entrer un titre" value="<?=
                        isset($_POST['title']) ? $_POST['title'] : $note['title']
                        ?>" autofocus >
            <?php if(isset($validate_title['valid']) && isset($validate_title['text'])){ ?>
                <div class="col-12 <?= $validate_title['valid']===true ? "valid-feedback" : 'invalid-feedback' ?>">
                    <?= $validate_title['text'] ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Content input -->
    <div class="form-inline row p-1 justify-content-center">
        <label for="content" class="col-1" >Contenu</label>
        <div class="form-row col-12 col-sm-8 col-md-7 col-xl-7">
            <textarea onKeyUp="applyPreview('#preview')" class="form-control col-12"
                 id="content" name="content" rows="10"><?= 
                isset($_POST['content']) ? $_POST['content'] : $note['content'] ?></textarea>
        </div>
    </div>

    <!-- Images Library -->
    <div id="images-library" class="form-inline justify-content-center collapse">
        <label for="content" class="col-12 col-sm-1 text-center" >Images</label>
        <div id="img-library" class="form-row col-12 col-sm-8 col-md-7 col-xl-7">
            <?php if( isset($images) && !empty($images))
            {
                foreach( $images as $image)
                { ?>

                    <div class="border col-12 col-sm-5 col-md-3 col-lg-2 align-middle">
                        <button type="button" class="col-12 p-0 mb-1" onclick="addTagImg('<?= $image['id'] ?>', '#content')">
                            <img class="img-thumbnail align-self-center" src="public/sources/images/images/user<?= $image['id_user'] ?>/<?= $image['url'] ?>" alt="<?= $image['description'] ?>"/>
                        </button>
                    </div>    

                <?php   
                }
            }
            // else
            // {
            //     echo 'Bibliotheque d\'images vide';
            // }
            ?>
            <input type="file" class="hidden-input-file" data-text="Choisir une image"
                id="img_file" name="img_file" data-btnClass="btn-outline-info" 
                data-placeholder="Vide" data-dragdrop="false">
                <div class="col-12 col-sm-5 col-md-3 col-lg-2 d-flex align-items-center">
                    <label for="img_file">
                        <a class="clickable" onclick="">
                            <img class="offset-4 col-4 offset-sm-1 col-sm-10 offset-lg-2 col-lg-8" src="public/sources/images/useful/add-picture.png" alt="Image d'ajout d'images"/>
                        </a>
                    </label>
                </div>
        </div>
    </div>

    <!-- <div class="form-inline justify-content-center">
        <div id="preview" class="col-8 collapse card">
        </div>
    </div> -->
    <!-- <div id="preview2" class="col-10 border">
        <?php //echo BBCode2Html('test [b]pour [/b]voir'); ?>
    </div> -->

    <!-- Tags input -->
    <div class="form-inline row p-1 justify-content-center">
        <label for="tags" class="col-1">Tags</label>
        <div class="form-row col-12 col-sm-8 col-md-7 col-xl-7">
            <input type="text" name="tags" class="form-control col-12 <?php 
                        //if(isset($validate_pseudo['valid'])){ echo $validate_pseudo['valid']===true ? "is-valid" : 'is-invalid'; }
                        ?>" id="tags" aria-describedby="tags" placeholder="Entrer des tags" value="<?=
                        isset($_POST['tags']) ? $_POST['tags'] : $note['tags']
                        ?>">
            <?php if(isset($validate_tags['valid']) && isset($validate_tags['text'])){ ?>
                <div class="col-12 <?= $validate_tags['valid']===true ? "valid-feedback" : 'invalid-feedback' ?>">
                    <?= $validate_tags['text'] ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <!-- Submit input -->
    <div class="col-12">
        <button type="submit" class="btn btn-outline-info offset-4 col-4">Valider</button>
    </div>

</form>

<!-- Javascript required -->
<script src="public/js/fileManager.js"></script>
<script src="public/js/imageManager.js"></script>
<script src="public/js/tagManager.js"></script>
<script src="public/js/toolboxManager.js"></script>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
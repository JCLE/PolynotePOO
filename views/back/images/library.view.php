<?php 
ob_start(); 
// print_r($categories[0]);
?>


<h1 class="text-center">Bibliotheque d'images</h1>
<div class="row justify-content-center mb-2">
    <a tabindex=1 href="addimage" class="btn btn-outline-primary">Ajout d'images</a>
</div>
<?php
if(empty($images))
{ ?>
    <p class="text-center">Aucune image dans votre bibliotheque</p>
<?php }
// var_dump($categories[0]);
?>

<div class="row justify-content-center">
    <?php
    foreach( $images as $image ) : ?>

    <div class="form-inline justify-content-center border col-2 m-1">
        <div class="position-absolute top-right m-0 p-0">
            <a tabindex=-1 href="deleteimage&del=<?= $image['id']; ?>">
                <button type="button" class="rounded-circle" >
                    <span class="ui-icon ui-icon-trash justify-content-center"></span>
                </button>
            </a>
        </div>

        <!-- clas retirée : d-flex align-items-center -->
        <a tabindex=2 class="text-decoration-none col-auto" href="category&id=<?= $category['id_category'] ?>">
            <div class=" justify-content-center">
                <img style="max-height: 100px;" class="img-thumbnail align-self-center" src="<?= USER_DIRECTORY ?>images/user<?= $image['id_user'] ?>/<?= $image['url'] ?>" alt="<?= $image['description'] ?>"/>
            </div>
        </a>


    </div>    

    <?php endforeach; ?>
</div>



<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>

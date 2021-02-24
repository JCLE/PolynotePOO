<?php 
ob_start(); 
// print_r($categories[0]);
?>


<h1 class="text-center">Mes Notes</h1>
<div class="row justify-content-center mb-2">
    <a tabindex=1 href="addcategory" class="btn btn-outline-primary">Ajouter une catégorie</a>
</div>
<?php
if(empty($categories))
{ ?>
    <p class="text-center">Pas encore de notes</p>
<?php }
// var_dump($categories[0]);
?>

<div class="row justify-content-center offset-lg-1 col-lg-10">
    <?php
    foreach( $categories as $category ) : ?>

    <div class="border col-12 col-sm-5 col-md-3 col-lg-2  text-center position-relative m-2">
        <div class="position-absolute top-right m-0 p-0">
        <a tabindex=-1 onclick="javascript: 
                if(confirm('Êtes-vous sure de vouloir supprimer la categorie <?= $category['name'] ?> ?'))
                {
                    window.location.href='deletecategory&del=<?= $category['id_category'] ?>';
                }
                "  >
                <button type="button" class="rounded-circle" >
                    <span class="ui-icon ui-icon-trash justify-content-center"></span>
                </button>
            </a>
        </div>
        <a tabindex=3 class="text-decoration-none col-12" href="category&id=<?= $category['id_category'] ?>">
            <div class="thumbnail col-12">
                <img src="<?= USER_DIRECTORY ?>icons/user<?= $category['id_user'] ?>/<?= $category['url'] ?>" alt="<?= $category['description'] ?>"/>
                <p class=" col-12 text-primary mb-0"><?= $category['name'] ?><span class="ml-2 badge alert-primary"><?= $category['nb_notes'] ?></span></p>
            </div>
        </a>
    </div>    

    <?php endforeach; ?>
</div>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>

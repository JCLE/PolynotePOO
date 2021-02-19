<?php 
ob_start();
// var_dump($category);
?>

<div class="row justify-content-center text-center mb-2">
    <a class="text-decoration-none text-primary" href="editcategory&id=<?= $category["id_category"] ?>">
        <h1 class="col-12"><?= $category["name"] ?></h1>
        <img src="public/sources/images/icons/user<?= $category['id_user'] ?>/<?= $category['url'] ?>" alt="<?= $category['description'] ?>"/>
    </a>
</div>
<div class="row justify-content-center mb-2">
    <a tabindex=1 href="addnote&id=<?= $category['id_category'] ?>" class="btn btn-outline-primary">Ajouter une note</a>
</div>
<?php
if(empty($notes))
{ ?>
    <p class="text-center">Pas encore de notes pour cette catégorie</p>
<?php }
foreach( $notes as $note ) : ?>
<?php //var_dump($category); ?>
<div class="card mb-3 shadow-lg offset-0 col-12 offset-sm-1 col-sm-10 p-0">
    <a tabindex=2 class="text-decoration-none text-dark" href="note&id=<?= $note['id'] ?>">
        <div class="row no-gutters col-12 m-0 p-0">
            <div class="d-flex align-items-center col-1 justify-content-center alert-primary">
                <img class="d-none d-md-block" src="public/sources/images/icons/user<?= $category['id_user'] ?>/<?= $category['url'] ?>" class="card-img img-fluid" alt="<?= $category['description'] ?>">
            </div>
            <div class="col-md-11">
            <div class="card-body text-center">
                <h5 class="card-title text-primary"><?= $note['title'] ?></h5>
                <!-- <p class="card-text"><?php // BBCode2Html(summarize($note['content'], 300)) ?></p>
                <p class="card-text"><small class="text-muted"><?php // elapsedTime($note['date_creation'], $note['date_edit']) ?></small></p> -->
            </div>
            </div>
        </div>
    </a>
</div>

<?php endforeach; ?>


<!-- paging -->
<div class="row justify-content-center">
    <?php 
        $nb_pages = ceil($nb_max_pages / LIMIT_NOTES_BY_PAGE);
        // print_r('page : '.$page.' --  nbpages : '.$nb_max_pages);

        /* Si on est sur la première page, on n'a pas besoin d'afficher de lien
        * vers la précédente. On va donc l'afficher que si on est sur une autre
        * page que la première */
        if ($page > 1):
            ?><a tabindex=10 class="text-decoration-none" href="category&id=<?= $category['id_category'] ?>&page=<?php echo $page - 1; ?>">Page précédente</a><?php
        endif;

        if($nb_pages > 1)
        {
            /* On va effectuer une boucle autant de fois que l'on a de pages */
            for ($i = 1; $i <= $nb_pages; $i++):
                if($page==$i):
                     echo '&nbsp;'.$i; 
                else: ?>
                    <a tabindex=10 class="text-decoration-none" href="category&id=<?= $category['id_category'] ?>&page=<?php 
                            echo $i; ?>"><?php echo '&nbsp;'.$i; ?></a>
        <?php   endif; 
            endfor;
        }

        /* Avec le nombre total de pages, on peut aussi masquer le lien
        * vers la page suivante quand on est sur la dernière */
        if ($page < $nb_pages):
            ?> <a tabindex=10 class="text-decoration-none" href="category&id=<?= $category['id_category'] ?>&page=<?php echo $page + 1; ?>">&nbsp;Page suivante</a><?php
        endif;
    ?>
</div>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
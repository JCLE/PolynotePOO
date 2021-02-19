<?php 
ob_start();
// print_r('<pre>');
// print_r($notes);
// print_r('</pre>');
?>
<div class="row justify-content-center text-center mb-2">
    <h1 class="col-12">Notes correspondantes</h1>
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
                <img class="d-none d-md-block" src="public/sources/images/icons/user<?= $note['id_user'] ?>/<?= $note['url'] ?>" class="card-img img-fluid" alt="<?= $note['description'] ?>">
            </div>
            <div class="col-md-11">
                <div class="card-body text-center">
                    <h5 class="card-title text-primary"><?= $note['title'] ?></h5>
                    <!-- <p class="card-text"><?php //BBCode2Html(summarize($note['content'], 300)) ?></p>
                    <p class="card-text"><small class="text-muted"><?php //elapsedTime($note['date_creation'], $note['date_edit']) ?></small></p> -->
                </div>
            </div>
        </div>
    </a>
</div>

<?php endforeach; ?>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
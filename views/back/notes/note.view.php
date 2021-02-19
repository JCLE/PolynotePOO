<?php 
ob_start();
?>

<div class="offset-0 col-12 offset-sm-1 col-sm-10 mt-2">
    <div class="card text-center rounded ">
        <div class="card-header  alert-primary">
            <!-- BTN mobile display -->
            <div class="position-absolute top-right m-0 p-0 row col-3 d-sm-none">
                <a class="col-12 mt-1" tabindex=-1 href="editnote&id=<?= $note['id'] ?>">
                    <button type="button" class="p-2 rounded-circle" >
                        <span class="ui-icon ui-icon-pencil"></span>
                    </button>
                </a>
                <a class="col-12 mt-1" tabindex=-1 href="deletenote&del=<?= $note['id'] ?>">
                    <button type="button" class="p-2 rounded-circle" >
                        <span class="ui-icon ui-icon-trash"></span>
                    </button>
                </a>
            </div>
            <!-- BTN other display -->
            <div class="position-absolute top-right m-0 p-0 row d-none d-sm-block">
                <a class="m-1" tabindex=-1 href="editnote&id=<?= $note['id'] ?>">
                    <button type="button" class="rounded-circle" >
                        <span class="ui-icon ui-icon-pencil"></span>
                    </button>
                </a>
                <a class="m-1" tabindex=-1 href="deletenote&del=<?= $note['id'] ?>">
                    <button type="button" class="rounded-circle" >
                        <span class="ui-icon ui-icon-trash"></span>
                    </button>
                </a>
            </div>
            <!-- Category Image and Name -->
            <div class="d-sm-block d-none"></div>
                <div>
                    <img class="" src="public/sources/images/icons/user<?= $note['id_user'] ?>/<?= $note['url'] ?>" alt="<?= $note['description'] ?>"/>
                </div>
                <?= $note['name_category'] ?>
        </div>
        <!-- Body -->
        <div class="card-body">
            <h5 class="card-title text-primary"><?= $note['title'] ?></h5>
            <?= BBCode2Html($note['content']) ?>
            <!-- <br /><br />
            <a href="#" class="btn btn-primary">future links TODO</a> -->
        </div>
        <!-- Date Creation -->
        <div class="card-footer text-muted  alert-primary">
            <?= elapsedTime($note['date_creation'], $note['date_edit']) ?>
        </div>
    </div>
</div>


<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>
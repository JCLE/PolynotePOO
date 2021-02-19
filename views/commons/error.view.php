<?php ob_start();  ?>

<div class="text-center">
    <a href="home" class="d-inline-flex text-center text-info nav-link border-info border-bottom border-left border-right">
        <h1>Erreur</h1>
    </a>
</div>
<?php
    if(isset($msg) && !empty($msg))
    { 
        echo displayAlert($msg,ALERT_DANGER, false);
    }
?>

<?php
$content = ob_get_clean();
require "views/commons/template.php"
?>

            
      
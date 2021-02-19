<?php ob_start();  ?>

<h1 class="text-center">Erreur</h1>
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

            
      
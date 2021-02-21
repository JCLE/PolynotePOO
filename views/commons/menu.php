<?php
// ************ LOGGED ************
if((isset($_SESSION['user']) && !empty($_SESSION['user'])))
{ ?>
<nav class="navbar navbar-expand-lg col-12">
  <ul class="navbar-nav text-center p-0 col-12 justify-content-center">
    <li class="nav-item dropdown col-12 col-lg-3 px-1">
      <a class="nav-link dropdown-toggle nav-link text-info border-right border-bottom border-info" href="<?= URL ?>#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        Bienvenue <?= $_SESSION['user']['pseudo'] ?>
      </a>
      <div class="dropdown-menu text-center col-12" aria-labelledby="navbarDropdown">
        <a class="dropdown-item nav-link text-info" href="<?= URL ?>#">Mon Compte</a>
        <!-- <a class="dropdown-item nav-link text-info" href="library">Bibliothèque d'images</a> -->
        <a class="dropdown-item nav-link text-info" href="<?= URL ?>logout">Déconnexion</a>
      </div>
    </li>
    <li class="nav-item col-12 col-lg-3 px-1">
      <a class="nav-link text-info border-left border-bottom border-info" href="<?= URL ?>categories">Mes notes</a>
    </li>
  </ul>
</nav>
<?php 
} 
else
{
  // ************ NO LOG ************
  ?>
  <ul class="nav justify-content-center text-center">
      <li class="nav-item col-3 p-1">
        <a class="nav-link text-info border-right border-bottom border-info" href="<?= URL ?>login">S'identifier</a>
      </li>
      <li class="nav-item col-3 p-1">
        <a class="nav-link text-info border-left border-bottom border-info" href="<?= URL ?>register">S'enregistrer</a>
      </li>
    </ul>
  <?php 
} 
 
 // ************ BREADCRUMB  ************
  if(isset($breadcrumb))
  {?>
 <nav aria-label="breadcrumb justify-content-center" 
      class="col-12 px-3 mb-1 p-sm-0 offset-sm-1 col-sm-10 offset-lg-2 col-lg-8">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a tabindex=1 class="text-decoration-none" href="<?= URL ?>home">Accueil</a></li>
      <?php 
        $i = 0;
        foreach( $breadcrumb as $link ) : 
          $i++;
          $setActive = ($i >= count($breadcrumb)) ? 'active' : '';
          // Pour les systemes de navigation, voir : aria-current
          $setAriaParent = ($i == count($breadcrumb)-1) ? 'aria-current="true"' : '';
          $setAriaCurrent = ($i == count($breadcrumb)-1) ? 'aria-current="page"' : '';
          if($i >= count($breadcrumb))
          { ?>
              <li  class="breadcrumb-item <?= $setActive ?>" $setAriaCurrent><?= $link['name'] ?></li>
         <?php 
          }
          else
          { ?>
              <li class="breadcrumb-item"><a tabindex=1 class="text-decoration-none" href="<?= URL.$link['page'] ?>" $setAriaParent><?= $link['name'] ?></a></li>
          <?php
          }
        ?>
      <?php endforeach; ?>
    </ol>
  </nav>
<?php } 


/**
 * LOG MESSAGE
 */
// If SESSION ALERT EXIST, COPY TO LOCAL VAR
if(isset($_SESSION['alert']['msg']) && !empty($_SESSION['alert']['msg']) 
  && isset($_SESSION['alert']['type']) && !empty($_SESSION['alert']['type']) )
{
  $alert['msg'] = $_SESSION['alert']['msg'];
  $alert['type'] = $_SESSION['alert']['type'];
  unset($_SESSION['alert']['msg']);
  unset($_SESSION['alert']['type']);
}
// CHECK LOCAL VAR AND DISPLAY IT
if(isset($alert['msg']) && !empty(($alert['msg'])))
{
    $type= null;
    if(isset($alert['type']) && !empty($alert['type']) )
    {
      $type = Security::secureHTML($alert['type']);
    }

      $alert = Security::secureHTML($alert['msg']); 
      echo displayAlert($alert,$type);
}

?>
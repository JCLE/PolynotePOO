<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="<?= $description?>">
    <title><?= $title?></title>
    <!-- CSS -->
    <link href="public/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="public/css/main.css" rel="stylesheet"/>
    <link href="public/jquery-ui-1.12.1/jquery-ui.min.css" rel="stylesheet"/>
    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    
    <!-- polices google -->
    <link href="https://fonts.googleapis.com/css?family=Yellowtail|Pacifico|&display=swap" rel="stylesheet">

    <!-- Jquery bootsrap a charger avant -->
    <!-- <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>     -->
    <!-- jquery standard -->
    <script type="text/javascript" language="javascript"  src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script> 
    <script type="text/javascript" language="javascript"  src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
    <!-- FileStyle to custom input file apparently -->
    <script type="text/javascript" src="public/js/bootstrap-filestyle.min.js"> </script>
    <!-- <script src="public/jquery-ui-1.12.1/jquery-ui.min.js"></script>
    <script src="public/jquery-ui-1.12.1/external/jquery/jquery.js"></script> -->
    <!-- <script src="http://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script> -->
    
</head>
<body>
    <div class='container-fluid'>
        <!-- Header du site -->
        <header class='mt-3 mb-1'>
            <?= styleSiteName("POLYNOTE", COLOR_TITLE) ?>
        </header>
        <!-- menu -->
        <div class="align-items-center mb-1">
            <?php require_once("menu.php") ?>
        </div>
        <!-- Contenu du site -->
        <div class='p-1 px-5 p-sm-0 px-sm-0'>
            <?= $content ?>
        </div>
        <!-- footer du site -->
        <footer class='text-center'>
            <!-- <p class='p-0 mb-2 fixed-bottom'>&copy; JCLE 2019 </p>    -->
        </footer>
    </div>
    <!-- Bootstrap js -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src='public/bootstrap/js/popper.min.js'> </script>
    <script src='public/bootstrap/js/bootstrap.min.js'> </script>
    <!-- <script src="public/js/jssor.utils.js"></script> -->
</body>
</html>
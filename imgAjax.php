<?php

include_once 'controllers/ajax.controller.php';

// $data = getCallAjax($_POST['search']);
// $data = 'filename = '.$_POST['filename'].' \n ';
if ( 0 < $_FILES['file']['error'] ) {
    echo 'Error: ' . $_FILES['file']['error'] . '<br>';
}
else {
    //move_uploaded_file($_FILES['file']['tmp_name'], 'uploads/' . $_FILES['file']['name']);
    // echo 'appelle la fonction pour l\'upload';
    // echo 'temp: ' . $_FILES['file']['tmp_name'] . '<br>';

    $data = getInsertImageAjax($_FILES['file']);
    echo json_encode($data);
}
// $data = $_POST['data'];

// $data = array('id','kekchose','toutça');

// $data["search"] = $_POST["search"];
// $data["search"] = Security::secureHTML( $_POST["search"] );

// echo json_encode($data);
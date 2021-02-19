<?php

include_once 'controllers/ajax.controller.php';

$data = getCallAjax($_POST['search']);

// $data = array('id','kekchose','toutça');

// $data["search"] = $_POST["search"];
// $data["search"] = Security::secureHTML( $_POST["search"] );

echo json_encode($data);
// echo JSON.parse($data);


// $my_array = [
//     ["id" => "206", "description" => "Peugeot 206"],
//     ["id" => "207", "description" => "Peugeot 207"],
//     ["id" => "208", "description" => "Peugeot 208"],
//     ["id" => "209", "description" => "Peugeot 209"],
//     ["id" => "307", "description" => "Peugeot 307"],
//     ["id" => "308", "description" => "Peugeot 308"],
//     ["id" => "309", "description" => "Peugeot 309"],
//     ["id" => "M3", "description" => "BMW M3"],
//     ["id" => "Quatro", "description" => "Audi Quatro"]
//     ];
     
// echo json_encode($my_array);



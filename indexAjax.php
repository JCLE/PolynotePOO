<?php

include_once 'controllers/ajax.controller.php';

$data = getCallAjax($_POST['search']);

echo json_encode($data);




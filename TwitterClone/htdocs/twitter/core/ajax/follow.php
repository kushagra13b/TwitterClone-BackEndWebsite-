<?php

include '../init.php';

$user_id = $_POST['user_id'];
$profileID = $_POST['profileID'];
$followID = $_POST['followID'];

$getFromF->followAction($profileID, $user_id, $followID);

?>
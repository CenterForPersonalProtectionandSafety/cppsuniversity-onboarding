<?php
/*
Set complete_wls to YES
*/

//it grabs the init file, wherever it is located relative to the parser file itself
require_once '../users/init.php';
//it instantiates the DB
$db = DB::getInstance();
$user_id = $user->data()->id;
$db->update('users',$user_id,['complete_ott'=>1]);



//a response is sent
//$response = ['success'=>true];
//echo json_encode($response);
//die;

?>

<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
$user = $_SESSION["User"];
$id = $_SESSION["ID"];
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if (isset($post_data["network"])) {
    $network = $post_data["network"];
    $job = $post_data["job"];
    $type = $post_data["type"];
    echo ($network);
    // $json[1]['dataPrice'] = $dataPrice;
}

?>
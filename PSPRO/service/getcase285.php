<?php

session_start();

if (!$_SESSION["UserID"]) {  //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if(isset( $post_data["data285Name"])){
    $ID =  $post_data["data285Name"];
    $sql = "SELECT * FROM data WHERE ID = $ID";
    $results = $conn->query($sql);
}


$i=0;
while ($Fnames = $results->fetch_assoc()) {
    $json[$i]['AT'] = $Fnames['AT'];
    $json[$i]['AT_NAME'] = $Fnames['AT_NAME'];

    $i++;
}


if(!isset($json)){
 $json = [];
}
echo json_encode($json);




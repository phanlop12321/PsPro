<?php
session_start();

if (!$_SESSION["UserID"]) {  //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if(isset( $post_data["VenderList"])){
    $id =  $post_data["VenderList"];
    $sql = "SELECT * FROM vender WHERE vdlist Like '%$id%'";

}else if(isset($post_data["FnameVender"])){
    $FnameVender =  $post_data["FnameVender"];
    $sql = "SELECT * FROM vender WHERE fname Like '%$FnameVender%'";
}
$results = $conn->query($sql);
//$employees = $results->fetch_assoc();
$i=0;
while ($venders = $results->fetch_assoc()) {
    $vender[$i]['fname'] = $venders['fname'];
    $vender[$i]['lname'] = $venders['lname'];
    $vender[$i]['address'] = $venders['address'];
    $vender[$i]['sme'] = $venders['sme'];
    $vender[$i]['vdlist'] = $venders['vdlist'];
    $vender[$i]['idtax'] = $venders['idtax'];
    $vender[$i]['smedate'] = $venders['smedate'];
    $vender[$i]['tel'] = $venders['tel'];
    $i++;
}


if(!isset($vender)){
 $vender = [];
}
echo json_encode($vender);




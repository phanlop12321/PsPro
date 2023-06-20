<?php
session_start();

if (!$_SESSION["UserID"]) {  //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if(isset( $post_data["CheckName"])){
    $Name =  $post_data["CheckName"];
    $sql = "SELECT * FROM director WHERE fname Like '%$Name%'";
    $results = $conn->query($sql);
}

//$employees = $results->fetch_assoc();
$i=0;
while ($Fnames = $results->fetch_assoc()) {
    $CheckName[$i]['fname'] = $Fnames['fname'];
    $CheckName[$i]['lname'] = $Fnames['lname'];
    $CheckName[$i]['rank'] = $Fnames['rank'];

    $i++;
}


if(!isset($CheckName)){
 $CheckName = [];
}
echo json_encode($CheckName);




<?php
session_start();

if (!$_SESSION["UserID"]) {  //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if(isset( $post_data["id"])){
    $id =  $post_data["id"];
    $sql = "SELECT * FROM employee WHERE ID Like '%$id%'";

}else if(isset($post_data["fName"])){
    $fName =  $post_data["fName"];
    $sql = "SELECT * FROM employee WHERE Fname Like '%$fName%'";
}
$results = $conn->query($sql);
//$employees = $results->fetch_assoc();
$i=0;
while ($employee = $results->fetch_assoc()) {
    $employees[$i]['ID'] = $employee['ID'];
    $employees[$i]['Fname'] = $employee['Fname'];
    $employees[$i]['Lname'] = $employee['Lname'];
    $employees[$i]['Rank'] = $employee['Rank'];
    $employees[$i]['Under'] = $employee['Under'];
    $employees[$i]['Department'] = $employee['Department'];
    $employees[$i]['pea'] = $employee['pea'];
    $employees[$i]['county'] = $employee['county'];
    $employees[$i]['phone'] = $employee['phone'];
    $i++;
}


if(!isset($employees)){
 $employees = [];
}
echo json_encode($employees);




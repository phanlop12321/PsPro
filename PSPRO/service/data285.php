<?php
session_start();

if (!$_SESSION["UserID"]) {  //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if(isset( $post_data["data285Name"])){
    $id =  $post_data["data285Name"];
    $sql = "SELECT * FROM data WHERE ID Like '%$id%' GROUP BY 'NAME'";
    $results = $conn->query($sql);

}

//$employees = $results->fetch_assoc();
$i=0;
while ($data = $results->fetch_assoc()) {
    $data285[$i]['ID'] = $data['ID'];
    $data285[$i]['NAME'] = $data['NAME'];
    $i++;
}


if(!isset($data285)){
 $data285 = [];
}
echo json_encode($data285);




<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
$user = $_SESSION["User"];
$id = $_SESSION["ID"];
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if (isset($post_data["dataID"])) {
    $AT = $post_data["dataAT"];
    $ID = $post_data["dataID"];
    $sql = "SELECT * FROM data WHERE ( AT_NAME = '$AT' ) AND ( ID = '$ID' )";
    $results = $conn->query($sql);
    // $json[1]['dataPrice'] = $dataPrice;
}

while ($Price = $results->fetch_assoc()) {
    $DataPrice = $Price['PRICE'];
    $AT_NAME = $Price['AT_NAME'];
    $json[1]['DataPrice'] = $DataPrice;
}

$sql1 = "UPDATE new285data SET price = $DataPrice ,newprice = $DataPrice ,AT ='$AT_NAME' WHERE (id = '$ID' ) AND ( user = '$user' ) AND (userid =  $id)";

$results = $conn->query($sql1);

// if (mysqli_query($conn, $sql1)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . mysqli_error($conn);
// }

if (!isset($json)) {
    $json = [];
}
echo json_encode($json);
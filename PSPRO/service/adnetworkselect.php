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
    $sql = "INSERT INTO network (id, idwork, iduser)
    VALUES ('$network', '$id', '$user')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
    $conn->close();
}

?>
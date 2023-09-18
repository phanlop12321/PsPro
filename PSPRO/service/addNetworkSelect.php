<?php
session_start();
if (!$_SESSION["UserID"]) { //check session
    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
}
$user = $_SESSION["User"];
$id = $_SESSION["ID"];
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if (isset($post_data["DataNetwork"])) {
    $Datajob = $post_data["Datajob"];
    $DataType = $post_data["DataType"];
    $DataNetwork = $post_data["DataNetwork"];
    $DataId = $post_data["DataId"];
    $DataQty = intval($post_data["DataQty"]);
    $sql = "UPDATE new285data SET network ='$DataNetwork' WHERE user = $user AND (userid = $id) AND  (id = '$DataId') AND (qty LIKE '$DataQty%') AND (type LIKE '$DataType%')AND (job LIKE '$Datajob%')";
    if ($conn->query($sql) === TRUE) {
        // $json[0] = $DataNetwork;
        // $json[1] = $user;
        // $json[2] = $id;
        // $json[3] = $DataId;
        // $json[4] = $DataQty;
    }
    $conn->close();
}
if (!isset($json)) {
    $json = [];
}
echo json_encode($json);
<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include('connection.php');
if (isset($_GET['create'])) {
    $_SESSION["ID"] = $_GET['create'];
}
$user = $_SESSION["User"];
$id = $_SESSION["ID"];

$network = $_POST['network'];
$job = $_POST['job'];
$type = $_POST['type'];

//echo $network . "+++++" . $job . "-----" . $type;
include('connection.php');
$sql = "UPDATE new285data SET network='$network' WHERE job = '$job' AND ( type = '$type' ) AND ( user = $user ) AND ( userid = $id )";

if ($conn->query($sql) === TRUE) {
} else {
    echo "Error updating record: " . $conn->error;
}

header('Location: form-wbs.php');
?>
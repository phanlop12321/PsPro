<?php
session_start();
if (!$_SESSION["UserID"]) { //check session
  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
}
$SESSIONuser = $_SESSION["User"];
$SESSIONid = $_SESSION["ID"];
include('connection.php');
$id = $_GET["id"];
$network = $_GET["network"];

if ($network == "") {
  $sql = "DELETE FROM new285data WHERE id = '$id' AND user = $SESSIONuser AND userid = $SESSIONid";
} else {
  $sql = "DELETE FROM new285data WHERE id = '$id' AND network = '$network' ";
}

if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();

header('Location: form-wbs.php');
?>
<?php
session_start();
if (!$_SESSION["UserID"]) { //check session
  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 
}
$SESSIONuser = $_SESSION["User"];
$SESSIONid = $_SESSION["ID"];
include('connection.php');

$sql = "DELETE FROM new285data WHERE user = $SESSIONuser AND userid = $SESSIONid";


if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();

header('Location: form-wbs.php');
?>
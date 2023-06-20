<?php
include('connection.php');
$id = $_GET["id"];
$user = $_GET["user"];

$sql = "DELETE FROM data285 WHERE (id = $id) AND (user = '$user') ";
$sql1 = "DELETE FROM contract WHERE (Id = $id) AND (User = '$user') ";
$sql2 = "DELETE FROM new285data WHERE (userid = $id) AND (user = '$user') ";


if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}
if ($conn->query($sql1) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}
if ($conn->query($sql2) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}
header('Location: index.php');
$conn->close();
?>
<?php
include('connection.php');
$network = $_GET["network"];

$sql = "DELETE FROM new285data WHERE  network = '$network' ";

if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();

header('Location: form-wbs.php');
?>
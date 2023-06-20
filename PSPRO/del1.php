
<?php
include('connection.php');
    $id = $_GET["id"];
    $network = $_GET["network"];
    
    $sql = "DELETE FROM new285data WHERE id = '$id' AND network = '$network' ";

if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully";
} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();

//header('Location: form-wbs.php');
    ?>
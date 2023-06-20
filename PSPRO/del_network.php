<?php
include('connection.php');
//$id = $_GET["id"];
//$NETWORK = $_GET["NETWORK"];

$id = $conn->real_escape_string($_GET['id']);
$NETWORK = $conn->real_escape_string($_GET['NETWORK']);

$sql = "DELETE FROM end_data WHERE ID_User = $id AND network = '$NETWORK' ";
$sql1 = "DELETE FROM wbs WHERE id = $id AND NETWORK = $NETWORK ";

if ($conn->query($sql) === TRUE) {
  echo "Record deleted successfully 444";
} else {
  echo "Error deleting record: " . $conn->error;
}
if ($conn->query($sql1) === TRUE) {
  echo "Record deleted successfully 5555";

} else {
  echo "Error deleting record: " . $conn->error;
}

$conn->close();
header('Location: https://utdpea.com/PSPRO/PSPRO/form-wbs.php');
?>
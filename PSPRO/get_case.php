<?php
include('connection.php');
$sql = "SELECT * FROM data WHERE ID={$_GET['case_ID']}";
$query = mysqli_query($conn, $sql);

$json = array();
while($result = mysqli_fetch_assoc($query)) {    
    array_push($json, $result);
}
echo json_encode($json);
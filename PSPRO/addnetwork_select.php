<?php
session_start();
if (!$_SESSION["UserID"]) {
    Header("Location: auth-login.html");
}
include('connection.php');
$network = $_POST['network'];
$idwork = $_POST['idwork'];
$iduser = $_POST['iduser'];
include('connection.php');
$sql = "SELECT id FROM networkdata WHERE id = '$network' AND idwork = '$idwork'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    header('Location: form-wbs2.php#');
} else {
    $sql = "INSERT INTO networkdata (id, iduser, idwork)
    VALUES ('$network', '$iduser', '$idwork')";
    if ($conn->query($sql) === TRUE) {
    }
}
$conn->close();
if ($_SESSION["Depratment"] == 02) { //check session

    header('Location: form-wbs2.php#'); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    header('Location: form-wbs.php#');
}

?>
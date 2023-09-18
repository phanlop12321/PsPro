<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
  include("connection.php");

  $User = $_SESSION["User"];
  $ID_User = $_SESSION["ID"];

  $count = $_POST['count'];
  $newprice = $_POST['newprice'];
  $id = $_POST['id'];
  $network = $_POST['network'];
  $qty = $_POST['qty'];



  for ($i = 1; $i < $count; $i++) {
    echo (" newprice[" . $i . "] = " . $newprice[$i]);
    $sql = "UPDATE new285data SET newprice = $newprice[$i] WHERE (network = '$network[$i]' ) AND ( id = $id[$i] )AND ( qty = $qty[$i] )";

    if (mysqli_query($conn, $sql)) {
    }
  }

  mysqli_close($conn);

  header('Location: https://utdpea.com/PSPRO/PSPRO/form-price.php');
}
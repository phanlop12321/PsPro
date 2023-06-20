<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $wbs = $_POST['wbs'];
    $network = $_POST['network'];
    $activity = $_POST['activity'];


    $pricework = $_POST['pricework'];


    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];

    //echo $network;


    $sql = "INSERT INTO wbs (WBS, NETWORK, ACT,pricework, id, user )
    VALUES ('$wbs', '$network', '$activity','$pricework','$id', '$User')";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    header('Location: https://utdpea.com/PSPRO/PSPRO/form-wbs.php');




    mysqli_close($conn);
}
?>
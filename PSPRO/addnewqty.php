<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $User = $_SESSION["User"];
    $ID_User = $_SESSION["ID"];

    $count = $_POST['count'];
    $qty = $_POST['qty'];
    $id = $_POST['id'];
    $network = $_POST['network'];

    $po = $_POST['po'];
    $po_date = $_POST['po_date'];

    $comment = $_POST['comment'];



    echo $po_date;

    $sql = "UPDATE data285 SET po = $po , po_date = '$po_date' , etc = '$comment' WHERE (user = '$User' ) AND ( id = '$ID_User' )";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }





    for ($i = 1; $i < $count; $i++) {
        echo "kkkk";
        echo $qty[$i];
        $sql = "UPDATE end_data SET qty = '$qty[$i]' WHERE (network = '$network[$i]' ) AND ( id = $id[$i] )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

    }

    print_r($qty);
    print_r($id);
    print_r($network);

    echo $count;

    mysqli_close($conn);

    header('Location: https://utdpea.com/PSPRO/PSPRO/form-check_forme.php');
}
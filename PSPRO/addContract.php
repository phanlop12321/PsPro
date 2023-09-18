<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $ContractNo = $_POST['ContractNo'];
    $ContractDate = $_POST['ContractDate'];
    $ContractQTY = $_POST['ContractQTY'];
    $ContractBegin = $_POST['ContractBegin'];
    $ContractFinish = $_POST['ContractFinish'];

    $NamePea = $_POST['NamePea'];
    $AddressPea = $_POST['AddressPea'];
    $ContractLend = 0;

    $ContractFname = $conn->real_escape_string($_POST['ContractFname']);
    $ContractLname = $conn->real_escape_string($_POST['ContractLname']);


    $ContractUnder = $_POST['ContractUnder'];


    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];


    $sql1 = "SELECT Id FROM contract WHERE (Id = '$id') AND ( User = '$User' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE contract SET ContractNo ='$ContractNo' ,NamePea ='$NamePea' ,AddressPea ='$AddressPea' ,ContractDate ='$ContractDate',ContractQTY ='$ContractQTY',ContractBegin ='$ContractBegin',ContractFinish ='$ContractFinish',ContractLend ='$ContractLend',ContractFname ='$ContractFname',ContractLname ='$ContractLname',ContractUnder ='$ContractUnder' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO contract (Id, User,NamePea, AddressPea, ContractNo, ContractDate, ContractQTY, ContractBegin,  ContractFinish, ContractLend, ContractFname, ContractLname, ContractUnder )
        VALUES ('$id', '$User','$NamePea', '$AddressPea', '$ContractNo', '$ContractDate', '$ContractQTY', '$ContractBegin',  '$ContractFinish', '$ContractLend', '$ContractFname', '$ContractLname', '$ContractUnder' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql = "SELECT fname FROM director WHERE fname = ('$ContractFname') AND ( lname = '$ContractLname' )";
    $result = $conn->query($sql);
    if ($result->num_rows === 0) {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$ContractFname', '$ContractLname', '$ContractUnder' )";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }


    mysqli_close($conn);
    header('Location: form-contract.php');
}
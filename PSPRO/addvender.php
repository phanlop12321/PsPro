<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $FNAME_VENDER = $_POST['FNAME_VENDER'];
    $LNAME_VENDER = $_POST['LNAME_VENDER'];
    $IDTAX = $_POST['IDTAX'];

    //$VENDER_LIST = $_POST['VENDER_LIST'];

    $VENDER_LIST = $conn->real_escape_string($_POST['VENDER_LIST']);


    $SME = $_POST['SME'];
    $DATE = $_POST['DATE'];
    $ADDRESS = $_POST['ADDRESS'];
    $TEL = $_POST['TEL'];
    $status = $_POST['status'];


    $material = $_POST['material'];
    $GL = $_POST['GL'];


    $avouch = $_POST['avouch'];

    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];






    echo "----------";

    echo $FNAME_VENDER;
    echo $LNAME_VENDER;
    echo $VENDER_LIST;
    echo $SME;
    echo $DATE;
    echo $ADDRESS;
    echo $material;
    echo "----------";
    echo $status;


    $sql1 = "SELECT * FROM vender WHERE vdlist = '$VENDER_LIST'";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET Vender_List ='$VENDER_LIST' ,material ='$material',GL ='$GL',avouch ='$avouch' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE vender SET fname ='$FNAME_VENDER' ,lname ='$LNAME_VENDER',address ='$ADDRESS',sme ='$SME',idtax ='$IDTAX',smedate ='$DATE',tel ='$TEL',status ='$status' WHERE vdlist = $VENDER_LIST ";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO vender (fname, lname, address, sme, vdlist, idtax, smedate, tel, status )
VALUES ('$FNAME_VENDER', '$LNAME_VENDER', '$ADDRESS','$SME','$VENDER_LIST','$IDTAX', '$DATE',  '$TEL', '$status')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET Vender_List ='$VENDER_LIST' ,material ='$material',GL ='$GL',avouch ='$avouch' WHERE id = $id AND ( user = '$User' )";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }
    header('Location: https://utdpea.com/PSPRO/PSPRO/form-vender.php');





    mysqli_close($conn);
}
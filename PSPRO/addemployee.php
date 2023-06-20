<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $ID_EMPLOYEE = $_POST['ID_EMPLOYEE'];
    $FNAME = $_POST['FNAME'];
    $LNAME = $_POST['LNAME'];
    $RANK = $_POST['RANK'];
    $DEPARTMENT = $_POST['DEPARTMENT'];
    $FULLNAME = $_POST['FULLNAME'];
    $pea = $_POST['pea'];
    $county = $_POST['county'];
    $TEL = $_POST['TEL'];
    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];

    $sql1 = "SELECT * FROM employee WHERE ID = $ID_EMPLOYEE";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET Employee ='$ID_EMPLOYEE' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";


        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        $sql = "UPDATE employee SET Fname ='$FNAME',Lname ='$LNAME',Rank ='$RANK',Under ='$DEPARTMENT',Department ='$FULLNAME',pea ='$pea',county ='$county',phone ='$TEL' WHERE ID = $ID_EMPLOYEE";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";


        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

    } else {
        $sql = "INSERT INTO employee (ID, Fname, Lname, Rank, Under, Department, pea,county,phone )
    VALUES ('$ID_EMPLOYEE', '$FNAME', '$LNAME','$RANK', '$DEPARTMENT','$FULLNAME','$pea','$county', '$TEL')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }

        $sql = "UPDATE data285 SET Employee ='$ID_EMPLOYEE' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";


        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }





    mysqli_close($conn);
}

header('Location: https://utdpea.com/PSPRO/PSPRO/form-employees.php');
?>
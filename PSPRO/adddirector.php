<?php
session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $center_price = $_POST['center_price'];
    $center_price_date = $_POST['center_price_date'];

    //$FName_Chairman_Center_Price = $_POST['FName_Chairman_Center_Price'];
//$Lname_Chairman_Center_Price = $_POST['Lname_Chairman_Center_Price'];

    $FName_Chairman_Center_Price = $conn->real_escape_string($_POST['FName_Chairman_Center_Price']);
    $Lname_Chairman_Center_Price = $conn->real_escape_string($_POST['Lname_Chairman_Center_Price']);
    $Rank_C_C = $_POST['Rank_C_C'];

    //$FName_Director_1 = $_POST['FName_Director_1'];
//$LName_Director_1 = $_POST['LName_Director_1'];

    $FName_Director_1 = $conn->real_escape_string($_POST['FName_Director_1']);
    $LName_Director_1 = $conn->real_escape_string($_POST['LName_Director_1']);
    $Rank_D_C1 = $_POST['Rank_D_C1'];

    //$FName_Director_2 = $_POST['FName_Director_2'];
//$LName_Director_2 = $_POST['LName_Director_2'];

    $FName_Director_2 = $conn->real_escape_string($_POST['FName_Director_2']);
    $LName_Director_2 = $conn->real_escape_string($_POST['LName_Director_2']);
    $Rank_D_C2 = $_POST['Rank_D_C2'];

    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];



    echo $FName_Chairman_Center_Price;
    echo $Lname_Chairman_Center_Price;
    echo $Rank_C_C;

    $sql = "UPDATE data285 SET Center_Price ='$center_price' ,Center_price_date ='$center_price_date' WHERE id = $id AND ( user = '$User' )";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    $sql1 = "SELECT * FROM director WHERE fname = ('$FName_Director_2') AND ( lname = '$LName_Director_2' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Director_2 ='$FName_Director_2' ,LName_Director_2 ='$LName_Director_2',Rank_D_C2 ='$Rank_D_C2' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_D_C2' WHERE (fname = '$FName_Director_2') AND (lname ='$LName_Director_2')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Director_2', '$LName_Director_2', '$Rank_D_C2' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Director_2 ='$FName_Director_2' ,LName_Director_2 ='$LName_Director_2',Rank_D_C2 ='$Rank_D_C2' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Director_1') AND ( lname = '$LName_Director_1' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Director_1 ='$FName_Director_1' ,LName_Director_1 ='$LName_Director_1',Rank_D_C1 ='$Rank_D_C1' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_D_C1' WHERE (fname = '$FName_Director_1') AND (lname ='$LName_Director_1')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Director_1', '$LName_Director_1', '$Rank_D_C1' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Director_1 ='$FName_Director_1' ,LName_Director_1 ='$LName_Director_1',Rank_D_C1 ='$Rank_D_C1' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Chairman_Center_Price') AND ( lname = '$Lname_Chairman_Center_Price' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Chairman_Center_Price ='$FName_Chairman_Center_Price' ,Lname_Chairman_Center_Price ='$Lname_Chairman_Center_Price',Rank_C_C ='$Rank_C_C' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_C_C' WHERE (fname = '$FName_Chairman_Center_Price') AND (lname ='$Lname_Chairman_Center_Price')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Chairman_Center_Price', '$Lname_Chairman_Center_Price', '$Rank_C_C' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Chairman_Center_Price ='$FName_Chairman_Center_Price' ,Lname_Chairman_Center_Price ='$Lname_Chairman_Center_Price',Rank_C_C ='$Rank_C_C' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }



    mysqli_close($conn);
}

header('Location: https://utdpea.com/PSPRO/PSPRO/form-center.php');

?>
<?php
session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    //$FName_Chairman_Check = $_POST['FName_Chairman_Check'];
//$LName_Chairman_Check = $_POST['LName_Chairman_Check'];

    $FName_Chairman_Check = $conn->real_escape_string($_POST['FName_Chairman_Check']);
    $LName_Chairman_Check = $conn->real_escape_string($_POST['LName_Chairman_Check']);
    $Rank_C_Check = $_POST['Rank_C_Check'];

    //$FName_Director_Check1 = $_POST['FName_Director_Check1'];
//$LName_Director_Check1 = $_POST['LName_Director_Check1'];

    $FName_Director_Check1 = $conn->real_escape_string($_POST['FName_Director_Check1']);
    $LName_Director_Check1 = $conn->real_escape_string($_POST['LName_Director_Check1']);
    $Rank_D_Check1 = $_POST['Rank_D_Check1'];

    //$FName_Director_Check2 = $_POST['FName_Director_Check2'];
//$LName_Director_Check2 = $_POST['LName_Director_Check2'];

    $FName_Director_Check2 = $conn->real_escape_string($_POST['FName_Director_Check2']);
    $LName_Director_Check2 = $conn->real_escape_string($_POST['LName_Director_Check2']);
    $Rank_D_Check2 = $_POST['Rank_D_Check2'];

    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];

    echo $FName_Director_Check2;
    echo "-------";
    echo $LName_Director_Check2;



    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Chairman_Check') AND ( lname = '$LName_Chairman_Check' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Chairman_Check ='$FName_Chairman_Check' ,LName_Chairman_Check ='$LName_Chairman_Check',Rank_C_Check ='$Rank_C_Check' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_C_Check' WHERE (fname = '$FName_Chairman_Check') AND (lname ='$LName_Chairman_Check')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Chairman_Check', '$LName_Chairman_Check', '$Rank_C_Check' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Chairman_Check ='$FName_Chairman_Check' ,LName_Chairman_Check ='$LName_Chairman_Check',Rank_C_Check ='$Rank_C_Check' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Director_Check1') AND ( lname = '$LName_Director_Check1' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Director_Check1 ='$FName_Director_Check1' ,LName_Director_Check1 ='$LName_Director_Check1',Rank_D_Check1 ='$Rank_D_Check1' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_D_Check1' WHERE (fname = '$FName_Director_Check1') AND (lname ='$LName_Director_Check1')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Director_Check1', '$LName_Director_Check1', '$Rank_D_Check1' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Director_Check1 ='$FName_Director_Check1' ,LName_Director_Check1 ='$LName_Director_Check1',Rank_D_Check1 ='$Rank_D_Check1' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Director_Check2') AND ( lname = '$LName_Director_Check2' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Director_Check2 ='$FName_Director_Check2' ,LName_Director_Check2 ='$LName_Director_Check2',Rank_D_Check2 ='$Rank_D_Check2' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_D_Check2' WHERE (fname = '$FName_Director_Check2') AND (lname ='$LName_Director_Check2')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Director_Check2', '$LName_Director_Check2', '$Rank_D_Check2' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Director_Check2 ='$FName_Director_Check2' ,LName_Director_Check2 ='$LName_Director_Check2',Rank_D_Check2 ='$Rank_D_Check2' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }



    mysqli_close($conn);
}

header('Location: https://utdpea.com/PSPRO/PSPRO/form-check.php');

?>
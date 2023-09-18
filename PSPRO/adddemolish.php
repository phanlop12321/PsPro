<?php
session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");

    $Demolish = $_POST['Demolish'];
    $Demolish_date = $_POST['Demolish_date'];
    $Demolish_finish = $_POST['Demolish_finish'];

    $FName_Demolish_Check = $conn->real_escape_string($_POST['FName_Demolish_Check']);
    $LName_Demolish_Check = $conn->real_escape_string($_POST['LName_Demolish_Check']);
    $Rank_Demolish_Check = $_POST['Rank_Demolish_Check'];


    $FName_Demolish_Check2 = $conn->real_escape_string($_POST['FName_Demolish_Check2']);
    $LName_Demolish_Check2 = $conn->real_escape_string($_POST['LName_Demolish_Check2']);
    $Rank_Demolish_Check2 = $_POST['Rank_Demolish_Check2'];

    $FName_Demolish_Check3 = $conn->real_escape_string($_POST['FName_Demolish_Check3']);
    $LName_Demolish_Check3 = $conn->real_escape_string($_POST['LName_Demolish_Check3']);
    $Rank_Demolish_Check3 = $_POST['Rank_Demolish_Check3'];

    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];


    $sql = "UPDATE data285 SET Demolish ='$Demolish' ,Demolish_date ='$Demolish_date' ,Demolish_finish = '$Demolish_finish' WHERE id = $id AND ( user = '$User' )";

    if (mysqli_query($conn, $sql)) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . mysqli_error($conn);
    }

    $sql1 = "SELECT * FROM director WHERE fname = ('$FName_Demolish_Check3') AND ( lname = '$LName_Demolish_Check3' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Demolish_Check3 ='$FName_Demolish_Check3' ,LName_Demolish_Check3 ='$LName_Demolish_Check3',Rank_Demolish_Check3 ='$Rank_Demolish_Check3' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_Demolish_Check3' WHERE (fname = '$FName_Demolish_Check3') AND (lname ='$LName_Demolish_Check3')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Demolish_Check3', '$LName_Demolish_Check3', '$Rank_Demolish_Check3' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Demolish_Check3 ='$FName_Demolish_Check3' ,LName_Demolish_Check3 ='$LName_Demolish_Check3',Rank_Demolish_Check3 ='$Rank_Demolish_Check3' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Demolish_Check2') AND ( lname = '$LName_Demolish_Check2' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Demolish_Check2 ='$FName_Demolish_Check2' ,LName_Demolish_Check2 ='$LName_Demolish_Check2',Rank_Demolish_Check2 ='$Rank_Demolish_Check2' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_Demolish_Check2' WHERE (fname = '$FName_Demolish_Check2') AND (lname ='$LName_Demolish_Check2')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Demolish_Check2', '$LName_Demolish_Check2', '$Rank_Demolish_Check2' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Demolish_Check2 ='$FName_Demolish_Check2' ,LName_Demolish_Check2 ='$LName_Demolish_Check2',Rank_Demolish_Check2 ='$Rank_Demolish_Check2' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }

    $sql1 = "SELECT * FROM director WHERE (fname = '$FName_Demolish_Check') AND ( lname = '$LName_Demolish_Check' )";
    $result1 = $conn->query($sql1);
    if ($result1->num_rows > 0) {
        $sql = "UPDATE data285 SET FName_Demolish_Check ='$FName_Demolish_Check' ,LName_Demolish_Check ='$LName_Demolish_Check',Rank_Demolish_Check ='$Rank_Demolish_Check' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE director SET rank ='$Rank_Demolish_Check' WHERE (fname = '$FName_Demolish_Check') AND (lname ='$LName_Demolish_Check')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    } else {
        $sql = "INSERT INTO director (fname, lname, rank )
    VALUES ('$FName_Demolish_Check', '$LName_Demolish_Check', '$Rank_Demolish_Check' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
        $sql = "UPDATE data285 SET FName_Demolish_Check ='$FName_Demolish_Check' ,LName_Demolish_Check ='$LName_Demolish_Check',Rank_Demolish_Check ='$Rank_Demolish_Check' WHERE id = $id AND ( user = '$User' )";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }
    }



    mysqli_close($conn);
}

header('Location: https://utdpea.com/PSPRO/PSPRO/form-demolish.php');

?>
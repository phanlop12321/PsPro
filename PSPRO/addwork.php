<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
    include("connection.php");


    $Name = $_POST['Name'];
    $type_budget = $_POST['type_budget'];
    $year = $_POST['year'];
    $diagram = $_POST['diagram'];
    $estimate = $_POST['estimate'];
    $estimate_date = $_POST['estimate_date'];
    $construct = $_POST['construct'];
    $construct_date = $_POST['construct_date'];
    $delivery = $_POST['delivery'];
    $event = $_POST['event'];




    $No_paper = $_POST['No_paper'];
    $No_paper_date = $_POST['No_paper_date'];

    $decide = $_POST['decide'];

    $Address = $_POST['Address'];



    $User = $_SESSION["User"];
    $id = $_SESSION["ID"];

    /*echo $Name;
    echo $type_budget;
    echo $year;
    echo $diagram;
    echo $estimate;
    echo $estimate_date;
    echo $construct;
    echo $construct_date;
    echo $User;*/

    echo $decide;

    $sql = "SELECT * FROM data285 WHERE id = $id AND ( user = '$User' ) ";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {


        $sql = "UPDATE data285 SET id='$id',Name='$Name',Type_Budget='$type_budget', year='$year', Diagram='$diagram', delivery='$delivery',event='$event', Estimate='$estimate', Estimate_Date='$estimate_date', Construct='$construct', Construct_Date='$construct_date', 	Nopaper='$No_paper', Nopaperdate='$No_paper_date', decide='$decide', Address='$Address'  WHERE id = $id AND ( user = '$User' )";
        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
            header('Location: https://utdpea.com/PSPRO/PSPRO/form-wbs.php');


        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }




    } else {

        $sql = "INSERT INTO data285 (id,Name, Type_Budget, year, Diagram, delivery, event, Estimate, Estimate_Date, Construct, Construct_Date, Nopaper, Nopaperdate, decide,Address,user )
VALUES ('$id','$Name', '$type_budget', '$year','$diagram','$delivery','$event', '$estimate','$estimate_date', '$construct','$construct_date','$No_paper','$No_paper_date','$decide','$Address','$User')";

        if (mysqli_query($conn, $sql)) {
            echo "New record created successfully";
            header('Location: https://utdpea.com/PSPRO/PSPRO/form-wbs.php');


        } else {
            echo "Error: " . $sql . "<br>" . mysqli_error($conn);
        }


    }

    mysqli_close($conn);
}
header('Location: https://utdpea.com/PSPRO/PSPRO/form-wbs.php');

?>
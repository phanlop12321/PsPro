<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
$user = $_SESSION["User"];
$id = $_SESSION["ID"];
include("../connection.php");
$post_data = json_decode(file_get_contents('php://input'), true); // 
if (isset($post_data["dataID"])) {
    $AT = $post_data["dataAT"];
    $ID = $post_data["dataID"];
    $TYPE = $post_data["dataTYPE"];
    $QTY = $post_data["dataQTY"];
    $sql = "SELECT * FROM data WHERE ( AT_NAME = '$AT' ) AND ( ID = '$ID' )";
    $results = $conn->query($sql);
    // $json[1]['dataPrice'] = $dataPrice;
}

while ($Price = $results->fetch_assoc()) {
    $price2 = $Price["PRICE"];
    $AT = $Price["AT"];

    if ($JOB == 'แผนกรื้อถอน') {
        $price2 = $price2 / 2;
    }
    if ($AT == "T5") {
        $price2 = $price2 / 3;
    }

    $st = substr($AT, 0, -1);

    if ($st == "D" || $st == "E" || $st == "F" || $st == "G" || $st == "H" || $st == "I" || $st == "J" || $st == "K" || $st == "L") {
        $price2 = round($price2 / 1000, 2, PHP_ROUND_HALF_DOWN);



    }
    if ($st == "M") {
        $price2 = round($price2 / 1000, 2, PHP_ROUND_HALF_DOWN);

    }




    $DataPrice = $price2;
    $AT_NAME = $Price['AT_NAME'];
    $json[1]['DataPrice'] = $DataPrice;
}



if ($TYPE == "Rem") {
    $DataPrice = round($price2 / 2, 2);
} else {
    $DataPrice = round($price2, 2);
}



$sql1 = "UPDATE new285data SET price = $DataPrice ,newprice = $DataPrice ,AT ='$AT_NAME' WHERE (id = '$ID' ) AND ( user = '$user' ) AND (userid =  $id) AND (qty = $QTY ) AND (type LIKE '$TYPE%')";

$results = $conn->query($sql1);

// if (mysqli_query($conn, $sql1)) {
//     echo "New record created successfully";
// } else {
//     echo "Error: " . $sql . "<br>" . mysqli_error($conn);
// }

if (!isset($json)) {
    $json = [];
}
echo json_encode($json);
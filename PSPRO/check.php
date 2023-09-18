<?php


session_start();

if (!$_SESSION["UserID"]) { //check session

  Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
  include('connection.php');

  //$ID = $_GET['CODE'];
//$CASE = $_GET['case'];

  $ID = $conn->real_escape_string($_GET['CODE']);
  $CASE = $conn->real_escape_string($_GET['case']);

  $JOB = $_GET['job'];

  $NETWORK = $_GET['NETWORK'];

  $User = $_SESSION["User"];
  $ID_User = $_SESSION["ID"];



  $QUANTITY = $_GET['quantity'];
  $AT;
  $NAME;
  $PRICE;
  $PRICE_N_V;
  $VAT;
  $PRICE_A_V;

  echo $NETWORK;

  $sql = "SELECT * FROM data WHERE ID = $ID AND AT_NAME = '$CASE'";
  $result = $conn->query($sql);

  if ($result->num_rows > 0) {
    echo "<table><tr><th>ID</th><th>Name</th><th>UNIT</th><th>DIG</th><th>POLE</th><th>PRICE</th></tr>";
    // output data of each row
    while ($row = $result->fetch_assoc()) {
      $price2 = $row["PRICE"];
      $AT = $row["AT"];

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



      $UNIT = $row["UNIT"];
      $NAME = $row["NAME"];
      $PRICE = $price2;
      echo $price2;
      echo "------";
      echo $PRICE;
      echo "------";
      $PRICE_N_V = $price2 * $QUANTITY;
      $VAT = $PRICE_N_V * 0.07;
      $PRICE_A_V = $PRICE_N_V + $VAT;
      echo $PRICE_N_V;
      echo "------";
      echo $VAT;
      echo "------";
      echo $PRICE_A_V;




      echo "<tr><td>" . $row["AT"] . "</td><td>" . $row["NAME"] . "</td><td>" . $row["UNIT"] . "</td><td>" . $row["DIG"] . "</td><td>" . $row["POLE"] . "</td><td>" . $row["PRICE"] . "</td></tr>";
    }
    echo "</table>";
  } else {
    echo "0 results";
  }

  $sql = "INSERT INTO end_data (id, ata, name, job, quantity, price, price_no_v, vat, price_and_v,unit,network,User,ID_User)
VALUES ('$ID', '$AT ', '$NAME', '$JOB', '$QUANTITY', '$PRICE', '$PRICE_N_V', '$VAT', '$PRICE_A_V','$UNIT','$NETWORK','$User','$ID_User')";

  if ($conn->query($sql) === TRUE) {
    echo "New record created successfully";
  } else {
    echo "Error: " . $sql . "<br>" . $conn->error;
  }


  $conn->close();
  header('Location: https://utdpea.com/PSPRO/PSPRO/form-wbs.php');
}
?>
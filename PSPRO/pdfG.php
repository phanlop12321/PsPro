<?php


session_start();

if (!$_SESSION["UserID"]) {  //check session

  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {
  
function Convert($amount_number)
{
  $amount_number = number_format($amount_number, 2, ".", "");
  $pt = strpos($amount_number, ".");
  $number = $fraction = "";
  if ($pt === false)
    $number = $amount_number;
  else {
    $number = substr($amount_number, 0, $pt);
    $fraction = substr($amount_number, $pt + 1);
  }

  $ret = "";
  $baht = ReadNumber($number);
  if ($baht != "")
    $ret .= $baht . "บาท";

  $satang = ReadNumber($fraction);
  if ($satang != "")
    $ret .=  $satang . "สตางค์";
  else
    $ret .= "ถ้วน";
  return $ret;
}

function ReadNumber($number)
{
  $position_call = array("แสน", "หมื่น", "พัน", "ร้อย", "สิบ", "");
  $number_call = array("", "หนึ่ง", "สอง", "สาม", "สี่", "ห้า", "หก", "เจ็ด", "แปด", "เก้า");
  $number = $number + 0;
  $ret = "";
  if ($number == 0) return $ret;
  if ($number > 1000000) {
    $ret .= ReadNumber(intval($number / 1000000)) . "ล้าน";
    $number = intval(fmod($number, 1000000));
  }

  $divider = 100000;
  $pos = 0;
  while ($number > 0) {
    $d = intval($number / $divider);
    $ret .= (($divider == 10) && ($d == 2)) ? "ยี่" : ((($divider == 10) && ($d == 1)) ? "" : ((($divider == 1) && ($d == 1) && ($ret != "")) ? "เอ็ด" : $number_call[$d]));
    $ret .= ($d ? $position_call[$pos] : "");
    $number = $number % $divider;
    $divider = $divider / 10;
    $pos++;
  }
  return $ret;
}
  include('connection.php');



$User = $_SESSION["User"];
//$id = $_SESSION["ID"];
$id = $_GET["create"];

$sqlc = "SELECT * FROM contract WHERE  id = $id AND ( user = '$User' )";
$resultc = $conn->query($sqlc);
$rowc = $resultc->fetch_assoc();

$sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$User' )";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();

$ID_employee = $row3["Employee"];
$ID_vdlist = $row3["Vender_List"];

$sql = "SELECT * FROM end_data WHERE ID_User = $id AND ( User = '$User' )";
$result = $conn->query($sql);

$sql1 = "SELECT * FROM employee WHERE ID=$ID_employee";
$result1 = $conn->query($sql1);
$row1 = $result1->fetch_assoc();

$sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

$price = 0;
while ($row = $result->fetch_assoc()) {

  $price = $price + ($row["newprice"]*$row["quantity"]);
}


require_once __DIR__ . '/vendor/autoload.php';

$defaultConfig = (new Mpdf\Config\ConfigVariables())->getDefaults();
$fontDirs = $defaultConfig['fontDir'];

$defaultFontConfig = (new Mpdf\Config\FontVariables())->getDefaults();
$fontData = $defaultFontConfig['fontdata'];

$mpdf = new \Mpdf\Mpdf([
  'fontDir' => array_merge($fontDirs, [
    'default_font_size' => 16,
    __DIR__ . 'tmp',
  ]),
  'fontdata' => $fontData + [
    'sarabun' => [
      'R' => 'THSarabunNew.ttf',
      'I' => 'THSarabunNew.ttf',
      'B' => 'THSarabunNew Bold.ttf',
      'BI' => 'THSarabunNew BoldItalic.ttf',
    ]
  ],
  'default_font' => 'sarabun'
]);
ob_start();
$mpdf->defaultheaderline = 0;
$mpdf->SetHeader('||หน้าที่ {PAGENO}');


?>
<!doctype html>
<html lang="en">

<head>
  <style type="text/css">

  </style>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Sarabun', sans-serif;
    }
  </style>

</head>

<body style="font-size:16pt; line-height: normal; padding: 0em;" }>
  <img src="img/pea.jpg" width="120" height="120">
  <p style=" position: absolute; top: 49mm; left: 100mm; width: auto;">ถึง&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?><br>วันที่&nbsp;</p>
  <p>จาก&nbsp;&nbsp;&nbsp;<?= $row1["Rank"]; ?>&nbsp;ผ<?= $row1["Under"]; ?>.<?= $row1["pea"]; ?>
    <br>เลขที่&nbsp;&nbsp;&nbsp;<?= $row1["county"]; ?>&nbsp;<?= $row1["pea"]; ?>(&nbsp;&nbsp;&nbsp;)
    <br>เรื่อง&nbsp;&nbsp;&nbsp;ขออนุมัติวางเงินประกันจ้างเหมาเอกชนช่วยงานก่อสร้างระบบจำหน่ายไฟฟ้า
    <br>เรียน&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?>
    <?php

    if ($row1["Under"] == $row1["pea"]) {
      echo ' ';
    } else {
      echo 'ผ่าน หผ.';
      echo $row1["Under"];
    }
    echo ".";
    echo $row1["pea"]; 
    ?>
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามรายงานขอจ้างเหมาเอกชนช่วยงานก่อสร้างฯ&nbsp;ตามอนุมัติที่&nbsp;<?= $row1["county"]; ?>&nbsp;<?= $row1["pea"]; ?>(&nbsp;&nbsp;&nbsp;)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลว.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
    <br>ตามที่ แผนกก่อสร้างได้จ้างเหมา&nbsp;<?= $row2["fname"]; ?>&nbsp;<?= $row2["lname"]; ?>&nbsp;Vender&nbsp;List&nbsp;<?= $ID_vdlist; ?>&nbsp;เลขประจำตัวผู้เสียภาษี&nbsp;<?= $row2["idtax"]; ?> ช่วยงานก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า&nbsp;บริเวณ&nbsp;<?= $row3["Address"]; ?>&nbsp;เป็นจำนวนเงิน&nbsp;<?php echo number_format($price, 2); ?>&nbsp;บาท&nbsp;(&nbsp;<?= Convert($price); ?>&nbsp;)&nbsp;รับประกันงาน&nbsp;<?= $row3["avouch"]; ?>&nbsp;วัน&nbsp;นับตั้งแต่วันที่ ส่งมอบงานจ้างถูกต้องเรียบร้อยแล้ว&nbsp;เพื่อเป็นหลักประกันการปฏิบัติงานตามเงื่อนไข ของใบสั่งจ้าง&nbsp;ผ<?= $row1["Under"]; ?>.<?= $row1["pea"]; ?>&nbsp;จึงขอแจ้งให้ <?=$row2["fname"];?>&nbsp;<?=$row2["lname"];?> วางเงินประกันสัญญาจ้างในอัตราร้อยละ 5 ของวงเงินการจ้างเหมาทั้งสิ้น คิดเป็นเงินประกัน&nbsp;<?php echo number_format($rowc["ContractMoney"], 2); ?>&nbsp;บาท
    <br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ
    <br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $row1["Fname"]; ?>&nbsp;<?= $row1["Lname"]; ?>
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<?= $row1["Rank"]; ?>&nbsp;ผ<?= $row1["Under"]; ?>.<?= $row1["pea"]; ?>&nbsp;)
</p>



  <?php
  $html = ob_get_contents();
  $mpdf->WriteHTML($html);
  $mpdf->output("MyreportG.pdf");
  ob_end_flush();
  ?>
  <a href="MyreportG.pdf">Download</a>

  <script type='text/javascript'>
      window.location.href = "MyreportG.pdf";
    </script>

  <!-- Optional JavaScript; choose one of the two! -->

  <!-- Option 1: Bootstrap Bundle with Popper -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

  <!-- Option 2: Separate Popper and Bootstrap JS -->
  <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
</body>

</html>

<?php } ?>
<?php

session_start();

if (!$_SESSION["UserID"]) {  //check session

  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {

  $dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
  $monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
  $monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];


  function thai_date_fullmonth($time)
  {   // 19 ธันวาคม 2556
    global $dayTH, $monthTH;
    $thai_date_return = date("j", $time);
    $thai_date_return .= " " . $monthTH[date("n", $time)];
    $thai_date_return .= " " . (date("Y", $time) + 543);
    return $thai_date_return;
  }


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

  /*$num1 = '3500'; 
$num2 = '120000.50'; 
echo  Convert($num1),"<br>"; 
echo  $num2  . "&nbsp;=&nbsp;" .Convert($num2),"<br>"; 
*/



  $price = 0;
  $vat = 0;
  $price_abd_vat = 0;
  $all_price = 0;
  $all_vat = 0;
  $All_price_abd_vat = 0;
  $WBS;
  $count = 0;



  $User = $_SESSION["User"];
  //$id = $_SESSION["ID"];
  $id = $_GET["create"];

  $sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$User' )";
  $result3 = $conn->query($sql3);
  $row3 = $result3->fetch_assoc();

  $ID_employee = $row3["Employee"];
  $ID_vdlist = $row3["Vender_List"];



  $sql4 = "SELECT * FROM wbs WHERE id = $id AND ( User = '$User' )";
  $result4 = $conn->query($sql4);

  while ($row4 = $result4->fetch_assoc()) {
    $WBS[$count] = $row4["WBS"];
    $count++;
  }

  $sql1 = "SELECT * FROM employee WHERE ID=$ID_employee";
  $result1 = $conn->query($sql1);
  $row1 = $result1->fetch_assoc();

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
    <p style=" position: absolute; top: 49mm; left: 100mm; width: auto;">ถึง&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?><br>วันที่&nbsp;<?php if ($row3["Nopaperdate"] != '') {
                                                                                                                                        echo thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
                                                                                                                                      } ?></p>
    <p>จาก&nbsp;&nbsp;&nbsp;<?= $row1["Rank"]; ?>&nbsp;ผ<?= $row1["Under"];  ?>.<?= $row1["pea"]; ?>
      <br>เลขที่&nbsp;&nbsp;&nbsp;<?php if ($row3["Nopaper"] == '') {
                                    echo $row1["county"]; ?>&nbsp;<?php echo $row1["pea"];
                                                                                                  echo "(&nbsp;&nbsp;&nbsp;)";
                                                                                                } else {
                                                                                                  echo $row3["Nopaper"];
                                                                                                } ?>
      <br>เรื่อง&nbsp;&nbsp;&nbsp;รายงานขอจ้าง
      <br>เรียน&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?>
      <?php

      if ($row1["Under"] == "") {
        echo ' ';
      } else {
        echo 'ผ่าน หผ.';
        echo $row1["Under"];
        echo ".";
        echo $row1["pea"];
      }

      ?>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ด้วย&nbsp;ผ<?php if ($row1["Under"] != '') {
                                                                            echo $row1["Under"];
                                                                            echo ".";
                                                                          } ?><?= $row1["pea"]; ?>&nbsp;มีความประสงค์จ้างเหมาเอกชน(เฉพาะค่าเเรงงาน)ช่วยงานก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า
      <?= $row3["Type_Budget"]; ?>&nbsp;ปี&nbsp;<?= $row3["year"]; ?>&nbsp;ซึ่งมีรายละเอียดดังต่อไปนี้
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.&nbsp;เหตุผลความจำเป็นที่ต้องขอจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1&nbsp;ตามอนุมัติงานก่อสร้างเลขที่&nbsp;<?= $row3["Construct"]; ?>&nbsp;&nbsp;ลว. <?php echo thai_date_fullmonth(strtotime($row3["Construct_Date"])); ?>
      <br>ให้ดำเนินการก่อสร้างงานขยายเขตระบบจำหน่ายไฟฟ้า&nbsp;บริเวณ&nbsp;<?= $row3["Address"]; ?>&nbsp;อนุมัติประมาณการเลขที่&nbsp;<?= $row3["Estimate"]; ?>&nbsp;&nbsp;ลว. <?php echo thai_date_fullmonth(strtotime($row3["Estimate_Date"])); ?>&nbsp;WBS.&nbsp;<?php for($B = 0; $B < $count; $B++){  echo $WBS[$B]; if($B < ($count-1)){ echo ",";}}   ?>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.2&nbsp;เนื่องจาก&nbsp;ผ<?php if ($row1["Under"] != '') {
                                                                                                                        echo $row1["Under"];
                                                                                                                        echo ".";
                                                                                                                      } ?><?= $row1["pea"]; ?>&nbsp;มีบุคลากรและยานพาหนะไม่เพียงพอในการก่อสร้างงานขยายเขต&nbsp;<?= $row3["Type_Budget"]; ?>&nbsp;
      <br>ปี&nbsp;<?= $row3["year"]; ?>และเพื่อให้การดำเนินงานแล้วเสร็จตามวัตถุประสงค์ของลูกค้า&nbsp;และรองรับนโยบายโครงการ&nbsp;อย่างมีประสิทธิภาพ
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.&nbsp;รายละเอียดของพัสดุที่จ้าง</span>
      
    </p>

    <?php


    $result5 = $conn->query($sql4);
    $res = $conn->query($sql4);
    $rest = mysqli_num_rows($res);

    $x = 0;
    while ($row5 = $result5->fetch_assoc()) {
      $x++;
      $rest--;
    ?>


      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.<?= $x ?>&nbsp;หมายเลขงาน&nbsp;<?= $row5["WBS"]; ?>&nbsp;โครงข่าย&nbsp;<?= $row5["NETWORK"]; ?>&nbsp;กิจกรรม&nbsp;<?= $row5["ACT"]; ?></p>


      <table style="border-collapse: collapse;">


        <tr style="border: 1px solid black;">
          <th style="border: 1px solid black; width:5%; text-align: center;"  >ที่</th>
          <th style="border: 1px solid black; text-align: center;" >แผนก</th>
          <th style="border: 1px solid black; width:80%; text-align: center;" >รายละเอียด</th>
          <th style="border: 1px solid black; text-align: center;"  >จำนวน</th>

        </tr>

        <?php


        $sql = "SELECT * FROM end_data WHERE network = {$row5['NETWORK']}";
        $result = $conn->query($sql);
        // output data of each row
        $i = 1;
        while ($row = $result->fetch_assoc()) {

        ?>
          <tr style="border: 1px solid black;">
            <td style="border: 1px solid black; text-align: center;"><?= $i ?></td>
            <td style="border: 1px solid black;"><?= $row["job"]; ?></td>
            <td style="border: 1px solid black;"><?= $row["name"]; ?></td>
            <td style="border: 1px solid black; text-align: right;"><?= $row["quantity"];echo " ";echo $row["unit"] ; ?></td>

          </tr>
        <?php
          $i = $i + 1;
          $price = $price + $row["price_no_v"];
          $vat = $vat + $row["vat"];
          $price_abd_vat = $price_abd_vat + $row["price_and_v"];

          $all_price = $all_price + $row["price_no_v"];
          $all_vat = $all_vat + $row["vat"];
          $All_price_abd_vat = $All_price_abd_vat + $row["price_and_v"];
        }


        ?>




      </table>

    <?php } ?>

    <p>
    <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.&nbsp;ราคากลางของงานที่จะจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตามบันทึกกำหนดราคากลางในการจ้างเหมาก่อสร้างปรับปรุงระบบไฟฟ้า&nbsp;กรณีงานจ้างเหมาเฉพาะค่าแรง
      <br>ตามอนุมัติที่&nbsp;<?= $row3["Center_Price"]; ?>&nbsp;ลว. <?php echo thai_date_fullmonth(strtotime($row3["Center_Price_Date"])); ?>(&nbsp;ตามเอกสารแนบ&nbsp;1&nbsp;)&nbsp;มีรายละเอียดดังต่อไปนี้
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;4.&nbsp;วงเงินที่จะจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เงินงบประมาณเบิกจาก<?= $row3["Type_Budget"]; ?>จากค่าเเรงในงานก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า
      <br>บริเวณ&nbsp;<?= $row3["Address"]; ?>
      <br>งบประมาณ&nbsp;<?= "";
                        echo number_format($all_price, 2); ?>&nbsp;บาท&nbsp;ภาษีมูลค่าเพิ่ม&nbsp;<?= "";
                                                                                                  echo number_format($all_vat, 2); ?>&nbsp;บาท&nbsp;วงเงินรวมภาษีมูลค่าเพิ่ม&nbsp;<?= "";
                                                                                                                                                                            echo number_format($All_price_abd_vat, 2); ?>&nbsp;บาท
      <br>(&nbsp;<?= Convert($All_price_abd_vat); ?>&nbsp;)
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.&nbsp;กำหนดเวลาที่ต้องการใช้พัสดุ</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;กำหนดส่งมอบงานแล้วเสร็จ&nbsp;<?= $row3["delivery"] ?>&nbsp;นับจากวันลงนามในสัญญา
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6.&nbsp;วิธีที่จะจ้างและเหตุผลที่จะต้องจ้างวิธีนั้น</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6.1&nbsp;พิจารณาเห็นสมควรดำเนินการจัดจ้างโดยวิธีเฉพาะเจาะจง&nbsp;ตามพระราชบัญญัติการจัดซื้อจัดจ้างและ
      <br>การบริหารพัสดุภาครัฐ&nbsp;พ.ศ.2560&nbsp;ตามมาตรา&nbsp;56(2)&nbsp;(ข)&nbsp;เนื่องจากการจัดจ้างครั้งนี้มีราคาไม่เกิน&nbsp;500,000.-&nbsp;บาท
      <br>และดำเนินการตามระเบียบกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้าง&nbsp;และการบริหารพัสดุภาครัฐ&nbsp;พ.ศ.2560&nbsp;ข้อ&nbsp;79
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;6.2&nbsp;พิจารณาเห็นสมควรดำเนินการจัดจ้าง&nbsp;ตามกฏกระทรวง&nbsp;กำหนดพัสดุและวิธีการจัดซื้อจัดจ้างพัสดุที่รัฐ
      <br>ต้องการส่งเสริมหรือสนับสนุน&nbsp;(&nbsp;ฉบับที่&nbsp;2&nbsp;)&nbsp;พ.ศ.&nbsp;2563&nbsp;ข้อ&nbsp;7&nbsp;(2)&nbsp;(ก)&nbsp;และ&nbsp;หมวด&nbsp;7/1&nbsp;พัสดุส่งเสริมการผลิตภายในประเทศ
      <br>ข้อ&nbsp;27/3&nbsp;(3)&nbsp;การจัดจ้างที่มิใช่คนก่อสร้าง
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;7.&nbsp;หลักเกณฑ์การพิจารณาคัดเลือกขอเสนอ</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;
      <?php

      $c = $row3["decide"];
      if ($c == 1) {
        echo '/';
      }
      ?>&nbsp;)&nbsp;พิจารณาจากราคารวม&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;
      <?php

      if ($c == 2) {
        echo '/';
      }
      ?>&nbsp;)&nbsp;พิจารณาจากราคาต่อรายการ&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;
      <?php

      if ($c == 3) {
        echo '/';
      }
      ?>&nbsp;)&nbsp;พิจารณาจากราคาต่อหน่วย
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.&nbsp;ข้อเสนออื่นๆ</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.1&nbsp;เห็นควรให้เจ้าหน้าที่พัสดุ&nbsp;โดย&nbsp;<?= $row1["Fname"]; ?>&nbsp;<?= $row1["Lname"]; ?>&nbsp;ตำแหน่ง&nbsp;<?= $row1["Rank"]; ?>&nbsp;ผ<?= $row1["Under"]; ?>.<?= $row1["pea"]; ?>
      <br>เป็นผู้ติดต่อตกลงกับผู้รับจ้างโดยตรง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.2&nbsp;แต่งตั้งคณะกรรมการกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.2.1&nbsp;<?= $row3["FName_Chairman_Center_Price"]; ?>&nbsp;<?= $row3["Lname_Chairman_Center_Price"]; ?>&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;<?= $row3["Rank_C_C"]; ?>&nbsp;&nbsp;ประธานกรรมการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.2.2&nbsp;<?= $row3["FName_Director_1"]; ?>&nbsp;<?= $row3["LName_Director_1"]; ?>&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;<?= $row3["Rank_D_C1"]; ?>&nbsp;&nbsp;กรรมการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.2.3&nbsp;<?= $row3["FName_Director_2"]; ?>&nbsp;<?= $row3["LName_Director_2"]; ?>&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;<?= $row3["Rank_D_C2"]; ?>&nbsp;&nbsp;กรรมการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.3&nbsp;แต่งตั้งคณะกรรมกาตรวจรับพัสดุ/ผู้ตรวจรับพัสดุ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.3.1&nbsp;<?= $row3["FName_Chairman_Check"]; ?>&nbsp;<?= $row3["LName_Chairman_Check"]; ?>&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;<?= $row3["Rank_C_Check"]; ?>&nbsp;&nbsp;ประธานกรรมการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.3.2&nbsp;<?= $row3["FName_Director_Check1"]; ?>&nbsp;<?= $row3["LName_Director_Check1"]; ?>&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;<?= $row3["Rank_D_Check1"]; ?>&nbsp;&nbsp;กรรมการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;8.3.3&nbsp;<?= $row3["FName_Director_Check2"]; ?>&nbsp;<?= $row3["LName_Director_Check2"]; ?>&nbsp;&nbsp;ตำแหน่ง&nbsp;&nbsp;<?= $row3["Rank_D_Check2"]; ?>&nbsp;&nbsp;กรรมการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพิื่อโปรดพิจารณา&nbsp;หากเห็นชอบขอได้โปรดอนุมัติให้ดำเนินการจัดซื้อโดยวิธีเฉพาะเจาะจง
      <br>ตามมาตรา&nbsp;52(2)&nbsp;(ข)&nbsp;ตามรายละเอียดในรายงานขอจ้างดังกล่าวข้างต้น
    </p>
    <table>
      <tbody>



        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td style=" text-align: right; ">ลงชื่อ</td>
          <td>__________________________</td>
          <td></td>
          <td>&nbsp;&nbsp;&nbsp;เจ้าหน้าที่</td>
        </tr>


        <tr>
          <td></td>
          <td style=" text-align: right; ">(</td>
          <td style=" text-align: center;"><?= $row1["Fname"]; ?>&nbsp;<?= $row1["Lname"]; ?></td>
          <td>)</td>
          <td></td>
        </tr>
        <tr>
          <td></td>
          <td style=" text-align: right; ">ตำแหน่ง</td>
          <td style=" text-align: center;"><?= $row1["Rank"]; ?>&nbsp;ผ<?= $row1["Under"]; ?>.<?= $row1["pea"]; ?></td>
          <td></td>
          <td></td>
        </tr>



      </tbody>

    </table>
    <br><br>

    <div style="border: 1px solid black; width: 300px;">
      <table>
        <tbody>
          <tr>
            <td style="text-align: center; " colspan="3">เห็นชอบและอนุมัติตามเสนอ</td>
          </tr>
          <tr>
            <td>&nbsp;</td>
          </tr>
          <tr>
            <td style=" text-align: right; ">ลงชื่อ</td>
            <td>_______________________________</td>
            <td></td>
          </tr>
          <tr>
            <td style=" text-align: right; ">(</td>
            <td>_______________________________</td>
            <td style=" text-align: left; ">)</td>
            <td></td>
          </tr>
          <tr>
            <td style=" text-align: right; ">ตำแหน่ง</td>
            <td>_______________________________</td>
            <td></td>
          </tr>
          <tr>
            <td style=" text-align: right; ">วันที่</td>
            <td>_______________________________</td>
            <td></td>
          </tr>
        </tbody>
      </table>
    </div>
    <div style="position: absolute; top: auto; left: 97mm; width: auto; font-size: 12px;">
      หมายเหตุ<br>
      -หนังสือรายงานขอซื้อใช้สำหรับวิธีเฉพาะเจาะจง ที่มีวงเงินงบประมาณจัดซื้อแต่หละครั้งไม่เกิน 100,000.-บาท(รวมภาษีมูลค่าเพิ่ม)<br>
      -หากมีข้อมูล หรือรายละเอียดมากกว่าที่กำหนด ให้ระบุได้ตามสมควร หรือเอกสารแนบเพิ่มเติมได้
    </div>




    <?php
    $html = ob_get_contents();
    $mpdf->WriteHTML($html);
    $mpdf->output("MyreportB.pdf");
    ob_end_flush();

    ?>
    <a href="MyreportB.pdf">Download</a>

    <script type='text/javascript'>
      window.location.href = "MyreportB.pdf";
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
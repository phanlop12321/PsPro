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


  $User = $_SESSION["User"];
  //$id = $_SESSION["ID"];
  $id = $_GET["create"];

  $i = 1;
  $price = 0;
  $vat = 0;
  $price_abd_vat = 0;
  $all_price = 0;
  $all_vat = 0;
  $All_price_abd_vat = 0;
  $WBS;
  $count = 0;
  $year;


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

  if (isset($row3["Employee"])) {
    $ID_employee = $row3["Employee"];
    $sql1 = "SELECT * FROM employee WHERE ID=$ID_employee";
    $result1 = $conn->query($sql1);
    $row1 = $result1->fetch_assoc();
  }
  if (isset($row3["Vender_List"])) {

    $sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
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
    <p style=" position: absolute; top: 49mm; left: 100mm; width: auto;">ถึง&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?><br>วันที่&nbsp;<?php if ($row3["Nopaperdate"] != '') {
                                                                                                                                        echo thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
                                                                                                                                      } ?></p>
    <p>จาก&nbsp;&nbsp;&nbsp;คณะกรรมการกำหนดราคากลาง
      <br>เลขที่&nbsp;&nbsp;&nbsp;<?php if ($row3["Nopaper"] == '') {
                                    echo $row1["county"]; ?>&nbsp;<?php echo $row1["pea"];
                                                                  echo "(&nbsp;&nbsp;&nbsp;)";
                                                                } else {
                                                                  echo $row3["Nopaper"];
                                                                } ?>
      <br>เรื่อง ขออนุมัติกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า (เฉพาะค่าเเรง)
      <br>เรียน&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?>
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.&nbsp;เรื่องเดิม</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1.1&nbsp;ตามบันทึกที่&nbsp;<?= $row3["Center_Price"]; ?>&nbsp;ลว.&nbsp;<?php echo thai_date_fullmonth(strtotime($row3["Center_Price_Date"])); ?> ได้อนุมัติแต่งตั้งผู้มีรายนามข้างท้ายนี้<br>เป็นคณะกรรมการกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า ประจำปี&nbsp;<?php echo substr($row3["Center_Price_Date"], 0, 4) + 543; ?>&nbsp;นั้น
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.&nbsp;ข้อเท็จจริง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1&nbsp;ตามรายงานขอจ้างเลขที่&nbsp;<?php if ($row3["Nopaper"] == '') {
                                                                                                                                    echo $row1["county"]; ?>&nbsp;<?php echo $row1["pea"];
                                                                                                                                                                  echo "(&nbsp;&nbsp;&nbsp;)&nbsp;ลว.&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                                                                                                                                                                } else {
                                                                                                                                                                  echo $row3["Nopaper"];
                                                                                                                                                                  echo "&nbsp;ลว.&nbsp;";
                                                                                                                                                                  echo thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
                                                                                                                                                                } ?>&nbsp;ได้ขออนุมัติให้ คณะกรรมการกำหนดราคา งานจ้างเหมาเฉพาะค่าเเรงงาน&nbsp;บริเวณ&nbsp;<?= $row3["Address"]; ?>&nbsp;ในหมายเลข&nbsp;WBS&nbsp;<?php for ($B = 0; $B < $count; $B++) {
                                                                                                                                                                                                                                                                                                                                                  echo $WBS[$B];
                                                                                                                                                                                                                                                                                                                                                  if ($B < ($count - 1)) {
                                                                                                                                                                                                                                                                                                                                                    echo ",";
                                                                                                                                                                                                                                                                                                                                                  }
                                                                                                                                                                                                                                                                                                                                                }   ?> ตามอนุมัติประมาณการเลขที่&nbsp;<?= $row3["Estimate"]; ?>&nbsp;ลว.&nbsp;<?php echo thai_date_fullmonth(strtotime($row3["Estimate_Date"])); ?> (ค่าเเรง<?= $row3["event"]; ?>) นั้น
      <br><span style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.&nbsp;ข้อพิจารณา</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.1&nbsp;จากรายละเอียด&nbsp;เรื่องเดิม&nbsp;ข้อเท็จจริง&nbsp;เพื่อให้เป็นไปตามหลักเกณฑ์ของ&nbsp;กฟภ.&nbsp;ในการคำนวณราคากลางจ้างเหมาก่อสร้างระบบไฟฟ้า&nbsp;และให้การจ้างเหมาเอกชนช่วยงานก่อสร้างระบบไฟฟ้าของ&nbsp;<?= $row1["pea"]; ?>ในสังกัด&nbsp;กฟน.2&nbsp;มีราคากลางในการจ้างเหมาฯ&nbsp;ที่เหมาะสมเป็นปัจจุบัน&nbsp;ดังนั้นเพื่อให้เกิดความคล่องตัวในการดำเนินการ&nbsp;คณะกรรมการฯ&nbsp;ตรวจสอบและ พิจารณางานแล้ว&nbsp;จึงขออนุมัติกำหนดราคากลางงานข้างต้น&nbsp;ดังต่อไปนี้
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
      <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;3.2&nbsp;หมายเลขงาน&nbsp;<?= $row5["WBS"]; ?>&nbsp;โครงข่าย&nbsp;<?= $row5["NETWORK"]; ?>&nbsp;กิจกรรม&nbsp;<?= $row5["ACT"]; ?></p>


      <table style="border-collapse: collapse;">


        <tr style="border: 1px solid black;">
          <th style="border: 1px solid black; width:5%; text-align: center;" width="5px" rowspan="2">ที่</th>
          <th style="border: 1px solid black; text-align: center;" rowspan="2">แผนก</th>
          <th style="border: 1px solid black; text-align: center;" rowspan="2">รายละเอียด</th>
          <th style="border: 1px solid black; width:9%; text-align: center;" width="5px" rowspan="2">จำนวน</th>
          <th style="border: 1px solid black; text-align: center;" width="5px" rowspan="2">ราคาต่อหน่วย ไม่รวมภาษี</th>
          <th style="text-align: center;" colspan="3">รวมราคาที่ตกลงจ้าง(บาท)</th>


        </tr>

        <tr style="border: 1px solid black;">


          <td style="border: 1px solid black;" width="5px">ราคาตกลงจ้าง</td>
          <td style="border: 1px solid black;" width="5px">ภาษีฯ 7 %</td>
          <td style="border: 1px solid black;" width="5px">ราคารวมทั้งสิ้น</td>

        </tr>
        <?php

        $sql = "SELECT * FROM end_data WHERE network = {$row5['NETWORK']}";
        $result = $conn->query($sql);



        // output data of each row
        while ($row = $result->fetch_assoc()) {


        ?>
          <tr style="border: 1px solid black;">
            <td style="border: 1px solid black; text-align: center;"><?= $i ?></td>
            <td style="border: 1px solid black;"><?= $row["job"]; ?></td>
            <td style="border: 1px solid black;"><?= $row["name"]; ?></td>
            <td style="border: 1px solid black; text-align: center;"><?= $row["quantity"];echo " ";echo $row["unit"] ; ?></td>
            <td style="border: 1px solid black; text-align: right; "><?php echo number_format($row["price"], 2); ?></td>
            <td style="border: 1px solid black; text-align: right; "><?php echo number_format($row["price_no_v"], 2); ?></td>
            <td style="border: 1px solid black; text-align: right; "><?php echo number_format($row["vat"], 2); ?></td>
            <td style="border: 1px solid black; text-align: right; "><?php echo number_format($row["price_and_v"], 2); ?></td>

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
        <tr style="border: 1px solid black; ">
          <td></td>
          <td></td>
          <td>ราคารวม (บาท)</td>
          <td></td>
          <td></td>
          <td style="border: 1px solid black; text-align: right; ">
            <?php echo number_format($price, 2);
            $price = 0; ?>
          </td>
          <td style="border: 1px solid black; text-align: right; ">
            <?php echo number_format($vat, 2);
            $vat = 0; ?>
          </td>
          <td style="border: 1px solid black; text-align: right; ">
            <?php echo number_format($price_abd_vat, 2);
            $price_abd_vat = 0;
            $i = 1; ?>
          </td>
        </tr>
        <?php if ($rest == 0) { ?>

          <tr style="border: 1px solid black;  ">
            <td></td>
            <td></td>
            <td>ราคารวมทั้งสิ้น (บาท)</td>
            <td></td>
            <td></td>
            <td style="border: 1px solid black; text-align: right; ">
              <?= "";
              echo number_format($all_price, 2);
              ?>
            </td>
            <td style="border: 1px solid black; text-align: right; ">
              <?= "";
              echo number_format($all_vat, 2);

              ?>
            </td>
            <td style="border: 1px solid black; text-align: right; ">
              <?= "";
              echo number_format($All_price_abd_vat, 2);
              ?>
            </td>
          </tr>
        <?php } ?>

      </table>

    <?php } ?>

    <p><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;จึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ</p>
    <table>
      <tbody>



        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td style=" text-align: right; ">ลงชื่อ</td>
          <td>__________________________</td>
          <td></td>
          <td>&nbsp;&nbsp;&nbsp;ประธานกรรมการ</td>
        </tr>


        <tr>
          <td></td>
          <td style=" text-align: right; ">(</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;<?= $row3["FName_Chairman_Center_Price"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $row3["Lname_Chairman_Center_Price"]; ?></td>
          <td>)</td>
          <td>&nbsp;&nbsp;&nbsp;<?= $row3["Rank_C_C"]; ?></td>
        </tr>

        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td></td>
        </tr>

        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td style=" text-align: right; ">ลงชื่อ</td>
          <td>__________________________</td>
          <td></td>
          <td>&nbsp;&nbsp;&nbsp;กรรมการ</td>
        </tr>


        <tr>
          <td></td>
          <td style=" text-align: right; ">(</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;<?= $row3["FName_Director_1"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $row3["LName_Director_1"]; ?></td>
          <td>)</td>
          <td>&nbsp;&nbsp;<?= $row3["Rank_D_C1"]; ?></td>
        </tr>

        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td></td>
        </tr>

        <tr>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
          <td style=" text-align: right; ">ลงชื่อ</td>
          <td>__________________________</td>
          <td></td>
          <td>&nbsp;&nbsp;&nbsp;กรรมการ</td>
        </tr>


        <tr>
          <td></td>
          <td style=" text-align: right; ">(</td>
          <td>&nbsp;&nbsp;&nbsp;&nbsp;<?= $row3["FName_Director_2"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;<?= $row3["LName_Director_2"]; ?></td>
          <td>)</td>
          <td>&nbsp;&nbsp;<?= $row3["Rank_D_C2"]; ?></td>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;</td>

        </tr>
        </tr>
        <tr>
          <td>&nbsp;&nbsp;&nbsp;</td>

        </tr>




      </tbody>

    </table>

    <div style="position: absolute; top: auto; left: 110mm; width: auto;">
      <table style="border: 1px solid black;">

        <tr>
          <td style="text-align: center; " colspan="4">อนุมัติตามเสนอ</td>
          <td></td>
          <td></td>
        </tr>

        <tr>
          <td>&nbsp;&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td style=" text-align: right; ">ลงชื่อ</td>
          <td>__________________________</td>
          <td></td>
          <td>&nbsp;&nbsp;</td>
        </tr>
        <tr>
          <td style=" text-align: right; ">(</td>
          <td>__________________________</td>
          <td style=" text-align: left; ">)</td>
        </tr>
        <tr>
          <td style=" text-align: right; ">ตำแหน่ง</td>
          <td>__________________________</td>
        </tr>
        <tr>
          <td style=" text-align: right; ">วันที่</td>
          <td>__________________________</td>
        </tr>

      </table>
    </div>


    <?php
    $html = ob_get_contents();
    $mpdf->WriteHTML($html);
    $mpdf->output("MyreportC.pdf");
    ob_end_flush();
    ?>
    <a href="MyreportC.pdf">Download</a>
    <script type='text/javascript'>
      window.location.href = "MyreportC.pdf";
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
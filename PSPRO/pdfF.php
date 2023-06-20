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

  include('connection.php');
  $i = 1;
  $price = 0;
  $e = 0;
  $z = 0;
  $count = 0;
  $wbs;
  $newprice = 0;

  $status = 0;

  $User = $_SESSION["User"];
  //$id = $_SESSION["ID"];
  $id = $_GET["create"];

  $sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$User' )";
  $result3 = $conn->query($sql3);
  $row3 = $result3->fetch_assoc();

  $ID_employee = $row3["Employee"];
  $ID_vdlist = $row3["Vender_List"];

  $sql4 = "SELECT * FROM end_data WHERE ID_User = $id AND ( User = '$User' )";
  $result4 = $conn->query($sql4);



  $sql1 = "SELECT * FROM employee WHERE ID=$ID_employee";
  $result1 = $conn->query($sql1);
  $row1 = $result1->fetch_assoc();

  $sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
  $result2 = $conn->query($sql2);
  $row2 = $result2->fetch_assoc();

  $status = $row2['status'];

  $sql5 = "SELECT * FROM wbs WHERE id = $id AND ( User = '$User' )";
  $result5 = $conn->query($sql5);

$row5 = $result5->fetch_assoc();

  $e = mysqli_num_rows($result4);

  if ($e / 5 >= 1) {
    $z = $e / 5;
    $z = intval($z);
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
    'default_font' => 'sarabun',
    'format' => 'A4-L',
    'margin_left' => 0,
    'margin_right' => 0,
    'margin_top' => 0,
    'margin_bottom' => 0,
    'margin_header' => 0,
    'margin_footer' => 0
  ]);

  $r = 1;
  for ($q = 0; $q <= $z; $q++) {
    $r = $q * 5;

    ob_start();
    $sql = "SELECT * FROM end_data WHERE ID_User = $id AND ( User = '$User' ) ORDER BY network  limit {$r} , 5";
    $result = $conn->query($sql);
  


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

    <body style="font-size:16pt">

      <div style="position: absolute; bottom: 5mm;  ">
        <table>
          <tr>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>
              ผู้ขอเสนอซื้อ/จ้าง..........................................</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>ผู้อนุมัติ..........................................</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>ผู้บันทึก..........................................</th>
          </tr>

          <tr>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ตำแหน่ง..........................................</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>ตำแหน่ง..........................................</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>ตำแหน่ง..........................................</th>
          </tr>
          <tr>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วันที่........./....................../............</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>วันที่........./....................../............</th>
            <th>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</th>
            <th>วันที่........./....................../............</th>
          </tr>
        </table>
      </div>
      <div>
        <table style="border-collapse: collapse; border: 1px solid black;" width="100%">
          <tr style="border: 1px solid black;">
            <th style=" text-align: lef" rowspan="2"><img src="img/pea.jpg" width="100" height="100"></th>
            <th style=" text-align: center" rowspan="2">การไฟฟ้าส่วนภูมิภาค<br>
              ใบขอเสนอซื้อ/จ้าง (Purchase Requistion)<br>
              ประเภทเอกสาร (Document Type)</th>
            <th style="border: 1px solid black; text-align: lef">หน่วยงานผู้ขอซื้อ/จ้าง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
              วันที่ส่งมอบ</th>
            <th style="border: 1px solid black; text-align: lef" colspan="2">รหัสกลุ่มจัดของผู้ซื้อ/ผู้ว่าจ้าง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
              คลัง/สถานที่รับบริการ(รง.)</th>
            <th style="border: 1px solid black; text-align: lef" colspan="2">เลขที่ใบขอเสนอซื้อ/จ้าง &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
              เลขที่ติดตาม (Tracking No.)</th>
          </tr>
          <tr>
            <th style="border: 1px solid black; text-align: lef">หมวดรายการ(Item Category :I)</th>
            <th style="border: 1px solid black; text-align: center" colspan="4">หมวดการกำหนดบัญชี (Account Assignment Category : A)</th>
          </tr>
          <tr>
            <th style="font-size:12pt; text-align: lef">&#9634; PR มาตรฐาน(ZNB1)<br>
              &#9634; PR จาก WRP(ZNB2)
            </th>
            <th style="font-size:12pt; text-align: lef">&#9634; PR จากการปฏิบัติงาน (ZNB3)<br>
              &#9634; PR สัญญาล่วงหน้า(ZRV1)
            </th>
            <th style="border: 1px solid black; text-align: lef">&#9634; มาตรฐาน ( )<br>
              &#9634; การรับช่วง(L)
            </th>
            <th style="font-size:14pt; text-align: lef">&#9634; พัสดุสำรองคลัง ( )<br>
              &#9634;พัสดุโครงการที่มีแผน ( )
            </th>
            <th style="font-size:14pt; text-align: lef">&#9634; คชจ.เข้าหน้างาน ( )<br>
              &#9634;คชจ.เข้าใบสั่งซ่อม/งานบริการ(F)
            </th>
            <th style="font-size:14pt;  text-align: lef ">&#9634; ทรัพย์สินถาวรพร้อมใช้(Z)<br>&nbsp;</th>
            <th style="font-size:14pt; text-align: lef">&#9634; งานจ้างเหมาเบ็ดเสร็จ(P)<br>
              &#9634;งานจ้างเหมาบางส่วน(N)
            </th>
          </tr>

        </table>
        <table style="border-collapse: collapse; border: 1px solid black;" width="100%">
          <tr>
            <th align="left">บันทึกส่วนหัว (Header Note) :&nbsp;<?= $row3["Address"]; ?>&nbsp;อนุมัติที่&nbsp;<?= $row3["Estimate"]; ?>&nbsp;ลว. <?php echo thai_date_fullmonth(strtotime($row3["Estimate_Date"])); ?>&nbsp;WBS.&nbsp;<?php echo $row5["WBS"];  ?><br> Vender List : &nbsp;<?= $ID_vdlist; ?></th>
          </tr>
        </table>

        <table style="border-collapse: collapse; border: 1px solid black;" width="100%">
          <tr>
            <th style="border: 1px solid black;" align="left" rowspan="2">ลำดับที่</th>
            <th style="border: 1px solid black;" align="left" rowspan="2">แผนก</th>
            <th style="border: 1px solid black;" align="center" rowspan="2">รหัสพัสดุ/ข้อความ</th>
            <th style="border: 1px solid black;" align="left" rowspan="2">ปริมาณ</th>
            <th style="border: 1px solid black;" align="left" rowspan="2">หน่วย</th>
            <th align="center" colspan="3">วงเงินงบประมาณ</th>
            <th style="border: 1px solid black;" align="left" rowspan="2">กลุ่มวัสดุ</th>
            <th style="border: 1px solid black;" align="left" rowspan="2">รหัสบัญชีGL</th>
            <th style="border: 1px solid black;" align="left" rowspan="2">เงินทุน</th>
            <th align="center" colspan="2">หมวดการกำหนดบัญชี</th>
          </tr>
          <tr>
            <th style="border: 1px solid black;" align="left">ต่อหน่วย</th>
            <th style="border: 1px solid black;" align="center">ราคารวม</th>
            <th style="border: 1px solid black;" align="right">สกุลเงิน</th>
            <th style="border: 1px solid black;">ศูนย์ต้นทุน/องค์ประกอบWBS</th>
            <th style="border: 1px solid black;">งานจ้างเหมาบางส่วน</th>
          </tr>
          <tr>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;">รายการ(ข้อความแบบสั้น)</th>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;" align="center" colspan="3">ข้อความรายการ</th>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;"></th>
            <th style="border: 1px solid black;">แหล่งเงินกู้</th>
            <th style="border: 1px solid black;">เลขที่สัญญากู้</th>
          </tr>
          <?php

          // output data of each row

          while ($row = $result->fetch_assoc()) {

            $net = $row["network"];

            $sql99 = "SELECT * FROM wbs WHERE NETWORK = '{$row["network"]}'";
            $result99 = $conn->query($sql99);
            $row99 = $result99->fetch_assoc();

            if ($status == 2){
              $newprice = $row["newprice"] + ($row["newprice"] * 0.07);
            }else{
              $newprice = $row["newprice"] ;
            }



          ?>
            <tr style="border: 1px solid black;">
              <td style="text-align: center;"><?= $i ?></td>
              <td><?= $row["job"]; ?></td>
              <td><?= $row["name"]; ?></td>
              <td style="text-align: center; "><?= $row["quantity"]; ?></td>
              <td><?= $row["unit"]; ?></td>
              <td style="text-align: right; "><?php echo number_format($newprice, 2); ?></td>
              <td style="text-align: right; "><?php echo number_format($newprice * $row["quantity"], 2); ?></td>
              <td style="text-align: center; ">THB</td>
              <td style="text-align: center; "><?= $row3["material"]; ?></td>
              <td style="text-align: center; "><?= $row3["GL"]; ?></td>
              <td style="text-align: center; ">THB</td>
              <td style="text-align: center; "><?= $row["network"]; ?></td>
              <td style="text-align: center; "><?= $row99["ACT"]; ?></td>
            </tr>
          <?php

            $i = $i + 1;
            $price = $price + ($newprice * $row["quantity"]);
          }
          if ($q == $z) {
          ?>

            <tr>
              <td></td>
              <td>รวมรายการ</td>
              <td style="text-align: center;"><?= $i - 1; ?></td>
              <td>รายการ</td>
              <td>รวมมูลค่า</td>
              <td></td>
              <td><?php echo number_format($price, 2); ?></td>
              <td></td>
              <td>บาท</td>
            </tr>

          <?php } ?>
        </table>
      </div>



    <?php
    $html[$z] = ob_get_contents();
    $mpdf->WriteHTML($html[$z]);
    if ($q < $z) {
      $mpdf->AddPage();
    }
  }
  $mpdf->output("MyreportF.pdf");
  ob_end_flush();
    ?>

<a href="MyreportF.pdf">Download</a>

<script type='text/javascript'>
      window.location.href = "MyreportF.pdf";
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
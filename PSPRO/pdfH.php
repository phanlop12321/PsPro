<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

} else {

  $dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
  $monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
  $monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];


  function thai_date_fullmonth($time)
  { // 19 ธันวาคม 2556
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
      $ret .= $satang . "สตางค์";
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
    if ($number == 0)
      return $ret;
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


  $i = 1;
  $price = 0;
  $vat = 0;
  $price_abd_vat = 0;
  $status = 0;



  $User = $_SESSION["User"];
  //$id = $_SESSION["ID"];
  $id = $_GET["create"];

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

  $status = $row2['status'];

  $sql4 = "SELECT * FROM contract WHERE  Id = $id AND ( User = '$User' )";
  $result4 = $conn->query($sql4);
  $row4 = $result4->fetch_assoc();
  $endprice = 0;
  $delendprice = 0;
  $sql5 = "SELECT * FROM wbs WHERE id = $id AND ( User = '$User' )";
  $result5 = $conn->query($sql5);

  while ($row5 = $result5->fetch_assoc()) {
    $sql6 = "SELECT * FROM end_data WHERE network = {$row5['NETWORK']}";
    $result6 = $conn->query($sql6);
    while ($row6 = $result6->fetch_assoc()) {
      $endprice = $endprice + ($row6["newprice"] * $row6["quantity"]);
      echo "--";
      echo $row6["newprice"];
    }
  }

  $delendprice = $endprice * 0.002;
  echo $delendprice;
  echo "---";
  echo $endprice;

  $newprice = ($endprice * 0.07);


  $addday = $row4['ContractBegin'];

  $addday = $addday - 1;

  $day = $row4['ContractDate'];

  $date_adday = date('Y-m-d', strtotime($day . '+ ' . $addday . ' days'));
  //echo $date_adday;

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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
      integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@100&display=swap" rel="stylesheet">
    <style>
      body {
        font-family: 'Sarabun', sans-serif;
      }
    </style>

  </head>


  <body style="font-size:16pt; line-height: normal; padding: 0em;">
    <div style=" position: absolute; top:10mm; left: 90mm; width: auto;"> <img src="img/pea.jpg" width="120" height="120">
    </div>
    <p style=" position: absolute; top: 37mm; left: 65mm; width: auto; font-size: 16pt; font-weight: bold;">
      สัญญาจ้างเหมาช่วยงานก่อสร้างขยายเขตระบบไฟฟ้า</p>
    <p style=" position: absolute; top: 43mm; left: 75mm; width: auto; font-size: 16pt; font-weight: bold;">
      <?php echo $row4['NamePea']; ?>
    </p>
    <p style=" position: absolute; top: 49mm; left: 70mm; width: auto; font-size: 16pt; font-weight: bold;">เลขที่&nbsp;
      <?php echo $row4['ContractNo']; ?>
    </p>
    <p>
      <br><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สัญญาฉบับนี้ทำขึ้น&nbsp;&nbsp;ณ&nbsp;&nbsp;
      <?php echo $row4['NamePea']; ?>
      <?php echo $row4['AddressPea']; ?> เมื่อ วันที่
      <?php echo thai_date_fullmonth(strtotime($row4["ContractDate"])); ?> ระหว่าง การไฟฟ้าส่วนภูมิภาค โดย
      <?php echo $row4['ContractFname']; ?>&nbsp;&nbsp;
      <?php echo $row4['ContractLname']; ?>&nbsp;ตำแหน่ง&nbsp;
      <?php echo $row4['ContractUnder']; ?>ผจก.
      <?php echo $row1['pea']; ?> สำนักงานตั้งอยู่
      <?php echo $row4['AddressPea']; ?> ซึ่งต่อไปนี้ในสัญญาเรียก "ผู้ว่าจ้าง"
      ฝ่ายหนึ่ง กับ
      <?php echo $row2['fname']; ?>
      <?php echo $row2['lname']; ?> อยู่บ้านเลขที่
      <?php echo $row2['address']; ?> ผู้ถือบัตรประจำตัวประชาชน&nbsp;เลขที่
      <?php echo $row2['idtax']; ?>
      แนบท้ายสัญญานี้ ซึ่งต่อไปนี้ในสัญญาเรียกว่า "ผู้รับจ้าง" อีกฝ่ายหนึ่ง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;คู่สัญญาได้ตกลงกันมีข้อความดังต่อไปนี้
      <br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 1.
        ข้อตกลงว่าจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างตกลงจ้าง&nbsp;และผู้รับจ้างตกลงรับจ้างทำงาน&nbsp;(เฉพาะค่าแรงงาน)&nbsp;ช่วยก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า
      บริเวณ
      <?php echo $row3['Address']; ?>
      ตามข้อกำหนดและ เงื่อนไขแห่งสัญญานี้รวมทั้งเอกสารแนบท้ายสัญญา
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างตกลงที่จะจัดหาแรงงาน&nbsp;และวัสดุเครื่องมือเครื่องใช้&nbsp;ตลอดจนอุปกรณ์ต่างๆ&nbsp;ชนิดดีเพื่อใช้ในงานจ้างตามสัญญานี้&nbsp;โดยผู้ว่าจ้างตกลง
      ที่จะจัดหาวัสดุอุปกรณ์ต่างๆ&nbsp;ที่ใช้ในการดำเนินการตามสัญญา&nbsp;ข้อ&nbsp;17
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 2.
        เอกสารอันเป็นส่วนหนึ่งของสัญญา</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เอกสารแนบท้ายสัญญาดังต่อไปนี้ให้ถือเป็นส่วนหนึ่งของสัญญานี้
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.1
      เอกสารการจ้างโดยวิธีเฉพาะเจาะจง&nbsp;(เอกสารแนบท้าย)
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.2
      รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง&nbsp;(เอกสารแนบท้าย)
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2.3
      แบบแผนผังก่อสร้าง เลขที่
      <?php echo $row3['Diagram']; ?>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ความใดในเอกสารแนบท้ายสัญญาที่ขัดหรือแย้งกับข้อความในสัญญานี้&nbsp;ให้ใช้ข้อความในสัญญานี้บังคับและ
      ในกรณีที่เอกสารแนบท้ายสัญญาขัดแย้งกันเอง&nbsp;ผู้รับจ้างจะต้องปฏิบัติตามคำวินิจฉัยของผู้ว่าจ้าง&nbsp;คำวินิจฉัยของผู้ว่าจ้างให้ถือเป็นที่สิ้นสุด&nbsp;และผู้รับจ้างไม่มีสิทธิเรียกร้องค่าจ้าง&nbsp;หรือค่าเสียหายหรือค่าใช้จ่ายใดๆ&nbsp;เพิ่มเติมจากผู้ว่าจ้างทั้งสิ้น
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 3.
        หลักประกันการปฏิบัติตามสัญญา</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในขณะทำสัญญานี้ผู้รับจ้างได้นำหลักประกันเป็น
      เงินสด เป็นจำนวน
      <?php echo $row4['ContractMoney']; ?>&nbsp;บาท&nbsp;(&nbsp;
      <?= Convert($row4['ContractMoney']); ?>&nbsp;) ไม่น้อยกว่าร้อยละ 5 (ห้า) ของราคาจ้างตามสัญญา
      มามอบให้แก่ผู้ว่าจ้างเพื่อเป็นหลักประกันการปฏิบัติตามสัญญานี้
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;กรณีผู้รับจ้างใช้หนังสือค้ำประกันมาเป็นหลักประกันการปฏิบัติบัติตามสัญญา&nbsp;หนังสือค้ำประกันดังกล่าว
      จะต้องออกโดยธนาคารที่ประกอบกิจการในประเทศไทย&nbsp;หรือโดยบริษัทเงินทุนหรือบริษัทเงินทุนหลักทรัพย์ที่ได้รับอนุญาต
      ให้ประกอบกิจการเงินทุนเพื่อการพาณิชย์และประกอบธุรกิจค้ำประกันตามประกาศของธนาคารแห่งประเทศไทยตามรายชื่อ
      บริษัทเงินทุนที่ธนาคารแห่งประเทศไทยแจ้งเวียนให้ทราบตามแบบที่คณะกรรมการนโยบายการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐกำหนดหรืออาจเป็นหนังสือค้ำประกันอิเล็คทรอนิกส์ตามวิธีการที่กรมบัญชีกลางกำหนดก็ได้&nbsp;และจะต้องมีอายุ
      การค้ำประกันตลอดไปจนกว่าผู้รับจ้างพ้นตามสัญญานี้
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หลักประกันที่ผู้รับจ้างนำมามอบให้ตามวรรคหนึ่ง&nbsp;จะต้องมีอายุครอบคลุมความรับผิดทั้งปวงของผู้รับจ้าง
      ตลอดอายุสัญญา&nbsp;ถ้าหลักประกันที่ผู้รับจ้างนำมามอบให้ดังกล่าวลดลงหรือเสื่อมค่าลง&nbsp;หรือมีอายุไม่ครอบคลุมถึงความรับผิด
      ของผู้รับจ้างตลอดอายุสัญญา&nbsp;ไม่ว่าด้วยเหตุใดๆ&nbsp;ก็ตามรวมถึงกรณี&nbsp;ผู้รับจ้างส่งมอบงานล่าช้าเป็นเหตุให้ระยะเวลาแล้วเสร็จ
      หรือวันครบกำหนดความรับผิดในความชำรุดบกพร่องตามสัญญาเปลี่ยนแปลงไป&nbsp;ไม่ว่าจะเกิดขึ้นคราวใด&nbsp;ผู้รับจ้างต้องหาหลักประกันใหม่หรือหลักประกันเพิ่มเติมให้มีจำนวนครบถ้วนตามวรรคหนึ่งมามอบให้แก่ผู้ว่าจ้างภายใน&nbsp;7&nbsp;(เจ็ด)&nbsp;วัน&nbsp;นับถัดจาก
      วันที่ได้รับแจ้งเป็นหนังสือจากผู้ว่าจ้าง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หลักประกันที่ผู้รับจ้างนำมามอบไว้ตามข้อตกลงนี้&nbsp;ผู้ว่าจ้างจะคืนให้แก่ผู้รับจ้าง&nbsp;โดยไม่มีดอกเบี้ย&nbsp;เมื่อผู้รับ
      จ้างพ้นจากข้อผูกพัน&nbsp;และความรับผิดทั้งปวงตามสัญญานี้แล้ว
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 4.
        ค่าจ้างและการจ่ายเงิน </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างตกลงจ่ายและผู้รับจ้างตกลงรับค่าจ้างจำนวนเงิน&nbsp;
      <?php echo number_format($endprice, 2); ?>&nbsp;บาท&nbsp;(&nbsp;
      <?= Convert($endprice); ?>&nbsp;)&nbsp;ซึ่งได้รวมภาษีมูลค่าเพิ่ม&nbsp;จำนวน&nbsp;
      <?php if ($status == 2) {
        echo number_format($newprice, 2);
        ;
      } else {
        echo "-";
      } ?>&nbsp;บาท&nbsp;ตลอดจนภาษีอากรอื่นๆ&nbsp;และค่าใช้จ่ายทั้งปวงด้วยแล้วเมื่อผู้รับจ้างได้ปฏิบัติงาน
      ทั้งหมดให้แล้วเสร็จเรียบร้อยตามสัญญาและผู้ว่าจ้างได้ตรวจรับงานจ้างตาม&nbsp;ข้อ&nbsp;13&nbsp;ไว้โดยครบถ้วนแล้ว
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 5.
        เงินค่าจ้างล่วงหน้า</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างตกลงจ่ายเงินค่าจ้างล่วงหน้าให้แก่ผู้รับจ้างเป็นจำนวนเงิน..........บาท&nbsp;ซึ่งเท่ากับร้อยละ..........ของ
      ราคา&nbsp;ค่าจ้างตามสัญญาที่ระบุไว้ในข้อ&nbsp;4
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เงินค่าจ้างล่วงหน้าดังกล่าวจะจ่ายให้ภายหลังจากที่ผู้รับจ้างได้วางหลักประกันการรับเงินค่าจ้างล่วงหน้าเป็น.............เต็มตามจำนวนเงินค่าจ้างล่วงหน้านั้นให้แก่ผู้ว่าจ้าง&nbsp;ผู้รับจ้างจะต้องออกใบเสร็จรับเงินค่าจ้างล่วงหน้า&nbsp;ตามแบบที่<br>ผู้ว่าจ้างกำหนดให้&nbsp;และผู้รับจ้างตกลงที่จะกระทำตามเงื่อนไขอันเกี่ยวกับการใช้จ่ายและการใช้คืนเงินค่าจ้างล่วงหน้านั้น<br>ดังต่อไปนี้
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.1&nbsp;ผู้รับจ้างจะใช้เงินค่าจ้างล่วงหน้านั้นเพื่อเป็นค่า&nbsp;ใช้จ่ายในการปฏิบัติงานตามสัญญาเท่านั้น&nbsp;หากผู้รับจ้างใช้จ่ายเงินค่าจ่างล่วงหน้า&nbsp;หรือส่วนใดส่วนหนึ่งของเงินค่าจ้าง&nbsp;ล่วงหน้านั้นในทางอื่น&nbsp;ผู้ว่าจ้างอาจจะเรียกเงินค่าจ้างล่วง
      หน้านั้นคืนจากผู้รับจ้างหรือบังคับเอาจากหลักประกันการรับเงิน ค่าจ้างล่วงหน้าได้ทันที
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.2&nbsp;เมื่อผู้ว่าจ้างเรียกร้อง&nbsp;ผู้รับจ้างต้องแสดงหลักฐานการใช้จ่ายเงินค่าจ้างล่วงหน้าเพื่อพิสูจน์ว่าได้เป็น
      ไปตาม&nbsp;ข้อ&nbsp;5.1&nbsp;ภายในกำหนด&nbsp;15&nbsp;(สิบห้า)&nbsp;วัน&nbsp;ผู้ว่าจ้างอาจเรียกเงินค่าจ้างล่วงหน้านั้นคืนจากผู้รับจ้าง&nbsp;หรือบังคับเอาจาก
      หลักประกันการรับเงินค่าจ้างล่วงหน้าได้ทันที
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.3&nbsp;ในการใช้จ่ายเงินค่าจ้างให้แก่ผู้รับจ้างตาม&nbsp;ข้อ&nbsp;4&nbsp;ผู้ว่าจ้างจะหักคืนเงินค่าจ้างล่วงหน้าในแต่ละงวดเพื่อ
      ชดใช้คืนเงินค่าจ้างล่วงหน้าไว้จำนวนร้อยละ&nbsp;-&nbsp;ของจำนวนเงินค่าจ้างในแต่ละงวดจนกว่าจำนวนเงินที่หักไว้จะครบตามจำนวน
      เงินที่หักค่าจ้างล่วงหน้าที่ผู้รับจ้างได้รับไปแล้ว&nbsp;ยกเว้นค่าจ้างงวดสุดท้ายจะหักไว้เป็นจำนวนเท่ากับจำนวนเงินค่าจ้างล่วงหน้าที่เหลือทั้งหมด
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.4&nbsp;เงินจำนวนใดๆ&nbsp;ก็ตามที่ผู้รับจ้างจะต้องจ่ายให้แก่ผู้ว่าจ้างเพื่อชำระหนี้หรือเพื่อชดใช้ความรับผิดต่างๆ
      ตามสัญญา&nbsp;ผู้ว่าจ้างจะหักเอาจากเงินค่าจ้างงวดที่จะจ่ายให้แก่ผู้รับจ้างก่อนที่จะหักชดใช้คืนเงินค่าจ้างล่วงหน้า
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.5&nbsp;ในกรณีที่มีการบอกเลิกสัญญา&nbsp;หากเงินค่าจ้างล่วงหน้าที่เหลือเกินกว่าจำนวนเงินที่ผู้รับจ้างจะได้รับหลังจากหักชดใช้ในกรณีอื่นแล้ว&nbsp;ผู้รับจ้างจะต้องจ่ายคืนเงินจำนวนที่เหลือนั้นให้แก่ผู้ว่าจ้างภายใน&nbsp;7&nbsp;(เจ็ด)&nbsp;วัน&nbsp;นับถัดจากวันที่ได้รับแจ้งเป็นหนังสือจากผู้ว่าจ้าง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;5.6&nbsp;ผู้ว่าจ้างจะคืนหลักประกันเงินค่าจ้างล่วงหน้าให้แก่ผู้รับจ้างต่อเมื่อผู้ว่าจ้างได้หักเงินค่าจ้างไว้ครบ
      จำนวนเงินค่าจ้าล่วงหน้าตาม&nbsp;ข้อ&nbsp;5.3
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 6.
        การหักเงินประกันผลงาน</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในการจ่ายเงินค่าจ้างให้แก่ผู้รับจ้างแต่ละงวด&nbsp;ผู้ว่าจ้างจะหักเงินจำนวนร้อยละ&nbsp;(......)&nbsp;ของเงินที่ต้องจ่ายในแต่ละงวดนั้นเพื่อเป็นประกันผลงาน&nbsp;ในกรณีที่เงินประกันผลงานถูกหักไว้แล้วเป็นจำนวนเงินไม่ต่ำกว่า.......บาท&nbsp;(&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;)&nbsp;ผู้รับจ้างมีสิทธิที่จะของเงินประกันผลงานคืน&nbsp;โดยนำหนังสือค้ำประกันของธนาคารหรือหนังสือค้ำประกันอิเล็กทรอนิกส์
      ซึ่งออกโดยธนาคารภายในประเทศมามอบให้ผู้ว่าจ้างเพื่อเป็นหลักประกันแทนก็ได้
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างจะคืนเงินประกันผลงาน&nbsp;และ/หรือหนังสือค้ำประกันของธนาคารดังกล่าวตามวรรคหนึ่งโดยไม่มีดอกเบี้ย
      ให้แก่ผู้รับจ้างพร้อมกับการจ่ายเงินงวดสุดท้าย
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 7.
        กำหนดเวลาแล้วเสร็จและสิทธิของผู้ว่าจ้างในการบอกเลิกสัญญา</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างสัญญาว่าจะทำงานจ้าง&nbsp;ณ&nbsp;สถานที่ที่กำหนดให้แล้วเสร็จบริบูรณ์&nbsp;ภายใน&nbsp;
      <?php echo $row4['ContractBegin']; ?>&nbsp;วัน&nbsp;โดยผู้รับจ้างต้องเริ่มทำงานที่รับจ้างภายในวันที่&nbsp;
      <?php echo thai_date_fullmonth(strtotime($row4["ContractDate"])); ?>&nbsp;และจะต้องทำงานให้แล้วเสร็จบริบูรณ์&nbsp;ภายในวันที่&nbsp;
      <?php echo thai_date_fullmonth(strtotime($date_adday)); ?>
      ถ้าผู้รับจ้างมิได้ลงมือทำภายในกำหนดเวลา&nbsp;หรือไม่สามารถทำงานให้แล้วเสร็จ&nbsp;ตามกำหนดเวลาหรือมีเหตุให้เชื่อว่าผู้รับจ้าง
      ไม่สามารถทำงานให้แล้วเสร็จภายในกำหนดเวลา&nbsp;หรือแล้วเสร็จล่าช้าเกินกว่ากำหนดเวลาหรือผู้รับจ้างทำผิดสัญญาข้อใด<br>ข้อหนึ่ง&nbsp;หรือตกเป็นผู้ถูกพิทักษ์ทรัพย์เด็ดขาดหรือตกเป็นผู้ล้มละลาย&nbsp;หรือเพิกเฉยไม่ปฏิบัติตามคำสั่งของคณะกรรมการตรวจรับพัสดุ&nbsp;ผู้ว่าจ้างมีสิทธิที่จะบอกเลิกสํญญานี้ได้&nbsp;และมีสิทธิจ้างผู้รับจ้างรายใหม่เข้าทำงานของผู้รับจ้างให้ลุล่วงไปได้ด้วยการใช้
      สิทธิบอกเลิกสัญญานั้นไม่กระทบสิทธิของผู้ว่าจ้างที่จะเรียกร้องค่าเสียหายจากผู้รับจ้าง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;การที่ผู้ว่าจ้างไม่ใช้สิทธิเลิกสัญญาดังกล่าวข้างต้นนั้น&nbsp;ไม่เป็นเหตุให้ผู้รับจ้างพ้นจากความรับผิดชอบ
      ตามสัญญา
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 8.
        ความรับผิดชอบในความชำรุดบกพร่องของงานจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เมื่องานแล้วเสร็จบริบูรณ์&nbsp;และผู้ว่าจ้างได้รับมอบงานจากผู้รับจ้างรายใหม่&nbsp;ในกรณีที่มีการบอกยกเลิกสัญญาตามข้อ&nbsp;6&nbsp;หากมีเหตุชำรุดบกพร่องหรือเสียหายเกิดขึ้นจากการจ้างนี้&nbsp;ภายในกำหนด&nbsp;1&nbsp;(หนึ่ง)&nbsp;เดือนนับถัดจากวันที่ได้รับมอบงานดังกล่าว&nbsp;ซึ่งความชำรุดบกพร่องหรือเสียหายนั้นเกิดจากความบกพร่องของผู้รับจ้างอันเกิดจากการใช้วัสดุที่ไม่ถูกต้องหรือทำไว้ไม่เรียบร้อย&nbsp;หรือทำไม่ถูกต้องตามมาตรฐานแห่งหลักวิชา&nbsp;ผู้รับจ้างจะต้องรีบทำการแก้ไข&nbsp;ให้เป็นที่เรียบร้อยโดยไม่ชักช้า&nbsp;โดยผู้ว่าจ้างไม่ต้องออกเงินใดๆ&nbsp;ในการนี้ทั้งสิ้น&nbsp;หากผู้รับจ้างไม่กระทำการดังกล่าวภายในกำหนด&nbsp;15&nbsp;(สิบห้า)&nbsp;วันนับถัดจาก<br>วันที่ได้รับแจ้งเป็นหนังสือจากผู้ว่าจ้างหรือไม่ทำการแก้ไขให้ถูกต้องเรียบร้อยภายในเวลาที่ผู้ว่าจ้างกำหนดให้ผู้ว่าจ้างมีสิทธิที่จะทำการนั้นเองหรือจ้างผู้อื่นให้ทำงานนั้น
      โดยผู้รับจ้างต้องเป็นผู้ออกค่าใช้จ่ายเองทั้งสิ้น
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีเร่งด่วนจำเป็นต้องรีบแก้ไขเหตุชำรุดบกพร่องหรือเสียหายโดยเร็ว&nbsp;และไม่อาจรอให้ผู้รับจ้างแก้ไขใน
      ระยะเวลาที่กำหนดไว้ตามวรรคหนึ่งได้&nbsp;ผู้ว่าจ้างมีสิทธิเข้าจัดการแก้ไขเหตุชำรุดบกพร่องหรือเสียหายนั้นเอง&nbsp;หรือจ้างผู้อื่นให้
      ซ่อมแซมความชำรุดบกพร่องหรือเสียหาย&nbsp;โดยผู้รับจ้าง&nbsp;ต้องรับผิดชอบชำระค่าใช้จ่ายทั้งหมด
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 9.
        การจ้างช่วง </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องไม่เอางานทั้งหมดหรือแต่บางส่วนแห่งสัญญานี้ไปจ้างช่วงอีกทอดหนึ่ง&nbsp;เว้นแต่การจ้างช่วงงานแต่บางส่วนที่ได้รับอนุญาตเป็นหนังสือจากผู้ว่าจ้างแล้ว&nbsp;การที่ผู้ว่าจ้างได้อนุญาตให้จ้างช่วงงานแต่บางส่วนดังกล่าวนั้น&nbsp;ไม่เป็นเหตุให้ผู้รับจ้างหลุดพ้นจากความรับผิดหรือพันธะหน้าที่ตามสัญญานี้&nbsp;และผู้รับจ้างจะยังคงต้องรับผิดในความผิดและความ
      ประมาทเลินเล่อของผู้รับจ้างช่วง&nbsp;หรือของตัวแทนหรือลูกจ้างของผู้รับจ้างช่วงนั้นทุกประการ
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;กรณีผู้รับจ้างไปจ้างช่วงงานแต่บางส่วนโดยฝ่าฝืนความในวรรคหนึ่ง&nbsp;ผู้รับจ้างต้องชำระค่าปรับให้แก่ผู้ว่าจ้าง
      เป็นจำนวนเงินในอัตราร้อยละ - ของวงเงินของงานที่จ้างช่วงตามสัญญา ทั้งนี้ ไม่ตัดสิทธิผู้ว่าจ้างในการบอกเลิกสัญญา
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 10.
        การควบคุมงานของผู้รับจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องควบคุมงานที่รับจ้างอย่างเอาใจใส่ด้วยประสิทธิภาพ&nbsp;และความชำนาญและในระหว่างทำงานที่รับจ้างจะต้องจัดให้มีผู้แทน&nbsp;ซึ่งทำงานเต็มเวลาเป็นผู้ควบคุมงาน&nbsp;ผู้ควบคุมงานตังกล่าวจะต้องเป็นผู้แทนได้รับมอบอำนาจ
      จากผู้รับจ้าง&nbsp;คำสั่งหรือคำแนะนำต่าง&nbsp;ๆ&nbsp;ที่ได้แจ้งแก่ผู้แทนผู้ได้รับมอบอำนาจนั้นให้ถือว่าเป็นคำสั่ง&nbsp;หรือคำแนะนำที่ได้แจ้งแก่ผู้รับจ้าง&nbsp;ผู้แทนดังกล่าวจะต้องได้รับความเห็นชอบจากผู้ว่าจ้าง&nbsp;การเปลี่ยนตัวหรือแต่งตั้งผู้ควบคุมงานใหม่จะทำมิได้หาก
      ไม่ได้รับความเห็นชอบจากผู้ว่าจ้างก่อน
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างมีสิทธิที่จะขอให้ผู้รับจ้างเปลี่ยนตัวผู้แทน
      ซึ่งได้รับมอบอำนาจจากผู้รับจ้างดังกล่าวนั้น และผู้รับจ้าง
      จะต้องทำการเปลี่ยนตัวโดยพลัน โดยไม่คิดราคาเพิ่ม หรืออ้างเป็นเหตุเพื่อขยายอายุสัญญาอันเนื่องจากเหตุนี้
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 11.
        ความรับผิดชอบของผู้รับจ้าง </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องรับผิดต่ออุบัติเหตุ&nbsp;ความเสียหาย&nbsp;หรือภยันตรายใดๆ&nbsp;อันเกิดจากการปฏิบัติงานของผู้รับจ้าง<br>และจะต้องรับผิดต่อความเสียหายจากการกระทำของลูกจ้างหรือตัวแทนของผู้รับจ้าง&nbsp;และจากการปฏิบัติงานของผู้รับจ้าง<br>ช่วงด้วย&nbsp;(ถ้ามี)
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ความเสียหายใดๆ&nbsp;อันเกิดแก่งานที่ผู้รับจ้างได้ทำขึ้น&nbsp;แม้จะเกิดขึ้นเพราะเหตุสุดวิสัยก็ตาม&nbsp;ผู้รับจ้างจะต้องรับผิดชอบโดยซ่อมแซมให้คืนดีหรือเปลี่ยนให้ใหม่โดยค่าใช้จ่ายของผู้รับจ้างเอง&nbsp;เว้นแต่ความเสียหายนั้นเกิดจากความผิดของผู้
      ว่าจ้าง&nbsp;ทั้งนี้&nbsp;ความรับผิดของผู้รับจ้างดังกล่าวในข้อนี้จะสิ้นสุดลงเมื่อผู้ว่าจ้างได้รับมอบงานครั้งสุดท้าย&nbsp;ซึ่งหลังจากนั้นผู้รับจ้าง
      คงต้องรับผิดเพียงในกรณีชำรุดบกพร่อง&nbsp;หรือความเสียหายดังกล่าวในข้อ&nbsp;7&nbsp;เท่านั้น
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องรับผิดต่อบุคคลภายนอกในความเสียหายใดๆ&nbsp;อันเกิดจากการปฏิบัติงานของผู้รับจ้าง&nbsp;หรือลูกจ้าง&nbsp;หรือตัวแทนของผู้รับจ้าง&nbsp;รวมถึงผู้รับจ้างช่วง&nbsp;(ถ้ามี)&nbsp;ตามสัญญานี้&nbsp;หากผู้ว่าจ้างถูกเรียกร้องหรือฟ้องร้องหรือต้องชดใช้
      ค่าเสียหายให้แก่บุคคลภายนอกไปแล้ว&nbsp;ผู้รับจ้างจะต้องคำเนินการใดๆ&nbsp;เพื่อให้มีการว่าต่างแก้ต่างให้แก่ผู้ว่าจ้างโดยค่าใช้จ่าย
      ของผู้รับจ้างเอง&nbsp;รวมทั้งผู้รับจ้างจะต้องชดใช้ค่าเสียหายนั้นๆ&nbsp;ตลอดจนค่าใช้จ่ายใดๆ&nbsp;อันเกิดจากการถูกเรียกร้องหรือถูกฟ้อง
      ร้องให้แก่ผู้ว่าจ้างทันที
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 12.
        การจ่ายเงินแก่ลูกจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องจ่ายเงินแก่ลูกจ้างที่ผู้รับจ้างได้จ้างมาในอัตราและตามกำหนดเวลาที่ผู้รับจ้างได้ตกลงหรือ<br>ทำสัญญาไว้ต่อลูกจ้างดังกล่าว
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ถ้าผู้รับจ้างไม่จ่ายเงินค่าจ้างหรือค่าทดแทนอื่นใดแก่ลูกจ้างดังกล่าวในวรรคหนึ่ง&nbsp;ผู้ว่าจ้างมีสิทธิที่จะเอาเงิน
      ค่าจ้างที่จะต้องจ่ายแก่ผู้รับจ้างมาจ่ายให้แก่ลูกจ้างของผู้รับจ้างดังกล่าว&nbsp;และให้ถือว่าผู้ว่าจ้างได้จ่ายเงินจำนวนนั้นเป็นค่าจ้าง
      ให้แก่ผู้รับจ้างตามสัญญาแล้ว
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องจัดให้มีประกันภัยสำหรับลูกจ้างทุกคนที่จ้างมาทำงาน&nbsp;โดยให้ครอบคลุมถึงความรับผิดทั้ง
      ปวงของผู้รับจ้าง&nbsp;รวมทั้งผู้รับจ้างช่วง&nbsp;(ถ้ามี)&nbsp;ในกรณีความเสียหายที่คิดค่าสินไหมทดแทนได้ตามกฎหมาย&nbsp;ซึ่งเกิดจากอุบัติเหตุหรือภยันตรายใดๆ&nbsp;ต่อลูกจ้างหรือบุคคลอื่นที่ผู้รับจ้างหรือผู้รับจ้างช่วงจ้างมาทำงาน&nbsp;ผู้รับจ้างจะต้องส่งมอบกรมธรรม์ประกันภัยดังกล่าวพร้อมทั้งหลักฐานการชำระเบี้ยประกันให้แก่ผู้ว่าจ้างเมื่อผู้ว่าจ้างเรียกร้อง
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 13.
        การตรวจรับงานจ้า</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;เมื่อผู้ว่าจ้างได้ตรวจรับงานจ้างที่ส่งมอบและเห็นว่าถูกต้องครบถ้วนตามสัญญาแล้ว&nbsp;ผู้ว่าจ้างจะออกหลักฐานการรับมอบเป็นหนังสือไว้ให้&nbsp;เพื่อผู้รับจ้างนำมาเป็นหลักฐานประกอบการขอรับเงินค่างานจ้างนั้น
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ถ้าผลของการตรวจรับงานจ้างปรากฎว่างานจ้างที่ผู้รับจ้างส่งมอบไม่ตรงตามสัญญา&nbsp;ผู้ว่าจ้างทรงไว้ซึ่งสิทธิที่จะไม่รับงานจ้างนั้น&nbsp;ในกรณีเช่นว่านี้&nbsp;ผู้รับจ้างต้องทำการแก้ไขให้ถูกต้องตามสัญญาด้วยค่าใช้จ่ายของผู้รับจ้างเอง&nbsp;และระยะ
      เวลาที่เสียไปเพราะเหตุดังกล่าวผู้รับจ้างจะนำมาอ้างเป็นเหตุขอขยายเวลาส่งมอบงานจ้างตามสัญญาหรือของดหรือลดค่า
      ปรับไม่ได้
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 14.
        รายละเอียดงานจ้างคลาดเคลื่อน</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างรับรองว่าได้ตรวจสอบและทำความเข้าใจในรายละเอียดของงานจ้างโดยถี่ถ้วนแล้ว&nbsp;หากปรากฎว่า
      รายละเอียดของานจ้างนั้นผิดพลาดหรือคลาดเคลื่อนไปจากหลักการทางวิศวกรรมหรือทางเทคนิค&nbsp;ผู้รับจ้างตกลงที่จะปฏิบัติ
      ตามคำวินิจฉัยของผู้ว่าจ้าง&nbsp;คณะกรรมการตรวจรับพัสดุ&nbsp;เพื่อให้งานแล้วเสร็จบริบูรณ์&nbsp;คำวินิจฉัยดังกล่าวให้ถือเป็นที่สิ้นสุด<br>โดยผู้รับจ้างจะคิดค่าจ้าง&nbsp;ค่าเสียหาย&nbsp;หรือค่าใช้จ่ายใดๆ&nbsp;เพิ่มขึ้นจากผู้ว่าจ้าง&nbsp;หรือขอขยายอายุสัญญาไม่ได้
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 15.
        การควบคุมงานโดยผู้ว่าจ้าง</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างตกลงว่าคณะกรรมการตรวจรับพัสดุ&nbsp;ผู้ควบคุมงาน&nbsp;หรือบริษัทที่ปรักษาที่ผู้ว่าจ้างแต่งตั้ง&nbsp;มีอำนาจที่
      จะตรวจสอบและควบคุมเพื่อให้เป็นไปตามสัญญานี้และมีอำนาจที่จะสั่งให้แก้ไขเปลี่ยนแปลงเพิ่มเติ่ม&nbsp;หรือตัดทอนซึ่งงานตามสัญญานี้หากผู้รับจ้างขัดขืนไม่ปฏิบัติตาม&nbsp;ผู่ว่าจ้างคณะกรรมการตรวจรับพัสดุ&nbsp;ผู้ควบคุมงาน&nbsp;หรือบริษัทที่ปรึกษา&nbsp;มีอำนาจที่
      จะสั่งให้หยุดการนั้นชั่วคราวได้ความล่าช้าในกรณีเช่นนี้&nbsp;ผู้รับจ้างจะถือเป็นเหตุขอขยายระยะเวลาปฏิบัติงานตามสัญญาหรือ
      เรียกร้องค่าเสียหายใดๆ&nbsp;ไม่ได้ทั้งสิ้น
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 16.
        งานพิเศษและการแก้ไขงาน </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างมีสิทธิที่จะสั่งเป็นหนังสือให้ผู้รับจ้างทำงานพิเศษซึ่งไม่ได้แสดงไว้หรือรวมอยู่ในเอกสารสัญญานี้<br>หากงานพิเศษนั้นๆ&nbsp;อยู่ในขอบข่ายทั่วไปแห่งวัตถุประสงค์ของสัญญานี้&nbsp;นอกจากนี้&nbsp;ผู้ว่าจ้างยังมีสิทธิสั่งให้เปลี่ยนแปลงหรือ<br>แก้ไข
      แบบรูปและข้อกำหนดต่างๆ&nbsp;ในเอกสารสัญญานี้ด้วย
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;อัตราค่าจ้างหรือราคาที่กำหนดไว้ในสัญญานี้&nbsp;ให้กำหนดใช้สำหรับงานพิเศษ&nbsp;หรืองานที่เพิ่มเติมขึ้น&nbsp;หรือตัด
      ทอนลงทั้งปวงตามคำสั่งของผู้ว่าจ้าง&nbsp;หากในสัญญาไม่ใต้กำหนดไว้ถึงอัตราค่าจ้าง&nbsp;หรือราคาใดๆ&nbsp;ที่จะนำมาใช้สำหรับ
      งานพิเศษหรืองานที่เพิ่มขึ้นหรือลดลงดังกล่าว&nbsp;ผู้ว่าจ้างและผู้รับจ้างจะได้ตกลงกันที่จะกำหนดอัตราค่จ้างหรือราคาที่เพิ่มขึ้น
      หรือลดลง&nbsp;รวมทั้งการขยายระยะเวลา&nbsp;(ถ้ามี)&nbsp;กันใหม่เพื่อความเหมาะสม&nbsp;ในกรณีที่ตกลงกันไม่ได้&nbsp;ผู้ว่าจ้างจะกำหนดอัตราจ้างหรือราคาตามแต่ผู้ว่าจ้างจะเห็นว่าเหมาะสมและถูกต้อง&nbsp;ซึ่งผู้รับจ้างจะต้องปฏิบัติงานตามคำสั่งของผู้ว่าจ้างไปก่อน&nbsp;เพื่อมิให้
      เกิดความเสียหายแก่งานที่จ้าง
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 17.
        วัสดุอุปกรณ์ที่ผู้ว่าจ้างจัดหา</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ว่าจ้างจะเป็นผู้ดำเนินการจัดหาววัสดุอุปกรณ์ตามที่กำหนดในขอบเขตซองงานซึ่งเป็นเอกสารแนบท้าย
      สัญญา&nbsp;และเป็นส่วนหนึ่งของสัญญาให้แก่ผู้รับจ้าง&nbsp;โดยให้ผู้รับจ้างขอรับมอบวัสดุอุปกรณ์ได้จากคลังพัสดุของการไฟฟ้าส่วน<br>ภูมิภาคในพื้นที่งานนั้นๆ&nbsp;หรือตามสถานที่ที่ผู้ว่าจ้างกำหนดโดยผู้รับจ้างจะต้องแจ้งรายชื่อผู้แทนหรือผู้ที่ได้รับมอบหมายใน<br>การรับมอบวัสดุอุปกรณ์ด้วยพร้อมทั้งจัดทำหนังสือมอบอำนาจ&nbsp;(ถ้ามี)
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างจะต้องจัดทำแผนการดำเนินการช่วงระยะเวลาและปริมาณวัสดุอุปกรณ์ที่จะขอเบิกให้ผู้ควบคุมงานของผู้ว่าจ้างตรวจสอบก่อนใช้วัสดุนั้นๆ&nbsp;ไม่น้อยกว่า&nbsp;15&nbsp;(สิบห้า)&nbsp;วัน&nbsp;พร้อมทั้งจัดหาพาหนะไปรับวัสดุอุปกรณ์และถือว่า
      การรับมอบนั้นผู้รับจ้างได้รับมอบถูกต้องแล้ว
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้รับจ้างต้องจัดเก็บวัสดุอุปกรณ์ที่เบิกไปไว้ในสถานที่ที่ปลอตภัย&nbsp;และมีเจ้าหน้าที่ดูแลทั้งนี้ผู้ว่าจ้างสงวนสิทธิ์ที่จะพิจารณาให้เบิกวัสดุอุปกรณ์สำหรับใช้งานได้ไม่เกิน&nbsp;30&nbsp;(สามสิบ)&nbsp;วัน&nbsp;ในกรณีที่มีวัสดุอุปกรณ์ที่เบิกไปเหลือจากการใช้
      งานผู้รับจ้างจะต้องนำส่งคืนคลังพัสดุของผู้ว่าจ้างก่อนการส่งมอบงานงวดสุดท้ายในสภาพที่สมบูรณ์
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หากวัสดุอุปกรณ์ที่ผู้รับจ้างเบิกไปชำรุดสูญหาย&nbsp;ผู้รับจ้างจะต้องชดใช้ค่าวัสดุให้ผู้ว่าจ้างจนครบถ้วน&nbsp;หากผู้รับจ้างไม่ชดใช้ให้ถูกต้องครบถ้วนภายในกำหนด&nbsp;วัน&nbsp;นับถัดจากวันที่ได้รับแจ้งเป็นหนังสือจากผู้ว่าจ้าง&nbsp;ให้ผู้ว่าจ้างมีสิทธิที่จะหัก
      เอาจากจำนวนเงินค่าจ้างที่ต้องชำระ&nbsp;หรือจากเงินประกันผลงานของผู้รับจ้าง&nbsp;หรือบังคับจากหลักประกันการปฏิบัติตาม
      สัญญาได้ทันที
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ห้ามมีให้ผู้รับจ้างนำวัสดุอุปกรณ์ที่ผู้ว่าจ้างจัดหาให้ทั้งหมดหรือบางส่วน&nbsp;ไปใช้ในงานอื่นหรือนำไปหาผล<br>ประโยชน์ส่วนตน&nbsp;หรือจำหน่ายจ่ายแจกให้กับบุคคลอื่นเป็นอันขาด
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หากผู้รับจ้างไม่กระทำการตามวรรคสาม&nbsp;วรรคสี่&nbsp;และวรรคห้า&nbsp;เป็นเหตุให้ผู้ว่าจ้างเสียหาย&nbsp;ผู้ว่าจ้างมีสิทธิ
      ดำเนินการฟ้องร้องดำเนินคดีและเรียกร้องค่าเสียหายกับผู้รับจ้างได้
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 18.
        ค่าปรับ </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หากผู้รับจ้างไม่สามารถทำงานให้แล้วเสร็จภายในเวลาที่กำหนดไว้&nbsp;ในสัญญาและผู้ว่าจ้างยังมิได้บอกเลิก
      สัญญาผู้รับจ้างจะต้องชำระค่าปรับให้แก่ผู้ว่าจ้างในอัตราร้อยละ&nbsp;0.2%&nbsp;ของราคาค่าจ้างตามสัญญา&nbsp;ถูกปรับเป็นเงินวันละ&nbsp;<br>
      <?php echo number_format($delendprice, 2); ?>&nbsp;บาท&nbsp;(&nbsp;
      <?= Convert($delendprice); ?>&nbsp;)&nbsp;นับถัดจากวันที่ครบกำหนด&nbsp;เวลาแล้วเสร็จของงานตามตามสัญญาหรือวันที่ผู้ว่าจ้างได้ขยายเวลาทำงานให้&nbsp;จนถึงวันที่ทำงานแล้วเสร็จจริง&nbsp;นอกจากนี้&nbsp;ผู้รับจ้างยอมให้ผู้ว่าจ้างเรียกค่าเสียหายอันเกิดขึ้นจากการที่ผู้รับจ้างทำงานล่าช้าเฉพาะส่วนที่เกินกว่าจำนวนค่าปรับดังกล่าวได้อีกด้วย
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในระหว่างที่ผู้ว่าจ้างยังมิได้บอกเลิกสัญญานั้น&nbsp;หากผู้ว่าจ้างเห็นว่าผู้รับจ้างจะไม่สามารถปฏิบัติตามสัญญาต่อไปได้&nbsp;ผู้ว่าจ้างจะใช้สิทธิบอกเลิกสัญญาและใช้สิทธิตามข้อ&nbsp;14&nbsp;ก็ได้&nbsp;และถ้าผู้ว่าจ้างได้แจ้งข้อเรียกร้องไปยังผู้รับจ้างเมื่อครบ
      กำหนดเวลาแล้วเสร็จของงานขอให้ชำระค่าปรับแล้ว&nbsp;ผู้ว่าจ้างมีสิทธิที่จะปรับผู้รับจ้างจนถึงวันบอกเลิกสัญญาได้อีกด้วย
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 19.
        สิทธิของผู้ว่าจ้าง </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีที่ผู้ว่าจ้างบอกเลิกสัญญา&nbsp;ผู้ว่าจ้างอาจทำงานนั้นเองหรือว่าจ้างผู้อื่นให้ทำงานนั้นต่อจนแล้วเสร็จก็ได้&nbsp;และในกรณีดังกล่าว&nbsp;ผู้ว่าจ้างมีสิทธิริบหรือบังคับจากหลักประกันการปฏิบัติตามสัญญาทั้งหมดหรือบางส่วนตามแต่จะเห็นสมควรนอกจากนั้น&nbsp;ผู้รับจ้างจะต้องรับผิดชอบในค่าเสียหายซึ่งเป็นจำนวนเกินกว่าหลักประกันการปฏิบัติตามสัญญา&nbsp;รวมทั้ง
      ค่าใช้จ่ายที่เพิ่มขึ้นในการทำงานนั้นต่อให้แล้วเสร็จตามสัญญา&nbsp;ซึ่งผู้ว่าจ้างจะหักเอาจากจำนวนเงินใดๆ&nbsp;ที่จะจ่ายให้แก่ผู้รับจ้าง
      ก็ได้
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 20.
        การบังคับค่าปรับ ค่าเสียหาย และค่าใช้จ่าย</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีที่ผู้รับจ้างไม่ปฏิบัติตามสัญญาข้อใดข้อหนึ่งด้วยเหตุใดๆ&nbsp;ก็ตาม&nbsp;จนเป็นเหตุให้เกิดค่าปรับ&nbsp;ค่าเสียหายหรือค่าใช้จ่ายแก่ผู้ว่าจ้าง&nbsp;ผู้รับจ้างต้องชดใช้ค่าปรับ&nbsp;ค่าเสียหาย&nbsp;หรือค่าใช้จ่ายดังกล่าวให้แก่ผู้ว่าจ้างโดยสิ้นเชิงภายในกำหนด
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;วัน&nbsp;นับถัดจากวันที่ได้รับแจ้งเป็นหนังสือจากผู้ว่าจ้าง&nbsp;หากผู้รับจ้างไม่ชดใช้ให้ถูกต้องครบถ้วนภายในระยะ
      เวลาดังกล่าวให้ผู้ว่าจ้างมีสิทธิที่จะหักเอาจากจำนวนเงินค่าจ้างที่ต้องชำระ&nbsp;หรือบังคับจากหลักประกันการปฏิบัติตามสัญญา
      ได้ทันที
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หากค่าปรับ&nbsp;ค่าเสียหาย&nbsp;หรือค่าใช้จ่ายที่บังคับจากเงินค่าจ้างที่ต้องชำระ&nbsp;หรือหลักประกันการปฏิบัติตาม
      สัญญาแล้วยังไม่เพียงพอ&nbsp;ผู้รับจ้างยินยอมชำระส่วนที่เหลือ&nbsp;ที่ยังขาดอยู่จนครบถ้วนตามจำนวนค่าปรับ&nbsp;ค่าเสียหาย&nbsp;หรือค่าใช้จ่ายนั้นภายในกำหนด&nbsp;7&nbsp;วัน&nbsp;นับถัดจากวันที่ได้รับแจ้งเป็นหนังสือจากผู้ว่าจ้าง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;หากมีเงินค่าจ้างตามสัญญาที่หักไว้จ่ายเป็นค่าปรับ&nbsp;ค่าเสียหาย&nbsp;หรือค่าใช้จ่ายแล้วยังเหลืออยู่อีกเท่าใด
      ผู้ว่าจ้างจะคืนให้แก่ผู้รับจ้างทั้งหมด
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 21.
        การทำบริเวณก่อสร้างให้เรียบร้อย</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ถ้าผู้รับจ้างหรือบริวารของผู้รับจ้างได้ก่อสร้างโรงงานหรือโรงเก็บของหรือสิ่งปลูกสร้างใด&nbsp;ๆ&nbsp;ลงในบริเวณ
      ที่รับจ้างก็ดีหรือทำเป็นหลุมเป็นบ่อก็ดี&nbsp;ผู้รับจ้างจะต้องระวังรักษาความสะอาดเก็บขยะมูลฝอยและเศษอาหารซึ่งคนงานที่
      ผู้รับจ้างได้ทำทุกวัน&nbsp;และจะต้องรื้อถอนสิ่งปลูกสร้างและกลบเกลี่ยพื้นที่ดิน&nbsp;ซึ่งได้ทำการก่อสร้างรายนี้ให้เรียบร้อย&nbsp;เศษอิฐ&nbsp;ไม้
      ปูน&nbsp;ทรายโรงเรือนและล้วม&nbsp;จะต้องขนไปให้พันบริเวณที่รับจ้างพร้อมทั้งทำความสะอาดบริเวณที่รับจ้างและสิ่งปลูกสร้างให้
      เรียบร้อยอยู่ในสภาพที่ผู้ว่าจ้างจะใช้งานได้ทันที
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 22.
        การงดหรือลดค่าปรับ หรือการขยายเวลาปฏิบัติงานตามสัญญา</span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีที่มีเหตุเกิดจากความผิดหรือความบกพร่องของฝ่ายผู้ว่าจ้าง&nbsp;หรือเหตุสุดวิสัย&nbsp;หรือเกิดจากพฤติการณ์อันหนึ่งอันใดที่ผู้รับจ้างไม่ต้องรับผิดตามกฎหมาย&nbsp;หรือเหตุอื่นตามที่กำหนดในกฎกระทรวง&nbsp;ซึ่งออกตามความในกฎหมายว่า
      ด้วยการจัดซื้อจัดจ้างและการบริหารพัสดุภาครัฐ&nbsp;ทำให้ผู้รับจ้างไม่สามารถทำงานให้แล้วเสร็จตามเงื่อนไขและกำหนดเวลา
      แห่งสัญญานี้ได้&nbsp;ผู้รับจ้างจะต้องแจ้งเหตุหรือพฤติการณ์ดังกล่าวพร้อมหลักฐานเป็นหนังสือให้ผู้ว่าจ้างทราบ&nbsp;เพื่อของดหรือลดค่าปรับหรือขยายเวลาทำงานออกไปภายใน&nbsp;15&nbsp;(สิบห้า)&nbsp;วันนับถัดจากวันที่เหตุนั้นสิ้นสุดลง&nbsp;หรือตามที่กำหนดใน
      กฎกระทรวงดังกล่าวแล้วแต่กรณี
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ถ้าผู้รับจ้างไม่ปฏิบัติให้เป็นไปตามความในวรรคหนึ่ง&nbsp;ให้ถือว่าผู้รับจ้างได้สละสิทธิเรียกร้อง&nbsp;ในการที่จะของดหรือลดค่าปรับ&nbsp;หรือขยายเวลาทำงานออกไปโดยไม่มีเงื่อนไขใดๆ&nbsp;ทั้งสิ้น&nbsp;เวันแต่&nbsp;กรณีเหตุเกิดจากความผิดหรือความบกพร่องของฝ่ายผู้ว่าจ้าง&nbsp;ซึ่งมีหลักฐานชัดแจ้ง&nbsp;หรือผู้ว่าจ้างทราบดี&nbsp;อยู่แล้วตั้งแต่ต้น
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;การงดหรือลดค่าปรับ&nbsp;หรือชยายกำหนดเวลาทำงานตามวรรคหนึ่ง&nbsp;อยู่ในดุลพินิจของผู้ว่าจ้างที่จะพิจารณา
      ตามที่เห็นสมควร
      <br><br><span
        style="font-size: 16pt; font-weight: bold;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ข้อ 23.
        การใช้เรือไทย </span>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในการปฏิบัติตามสัญญานี้&nbsp;หากผู้รับจ้างจะต้องสั่งหรือนำของเข้ามาจากต่างประเทศรวมทั้งเครื่องมือและ
      อุปกรณ์ที่ต้องนำเข้ามาเพื่อปฏิบัติงานตามสัญญา&nbsp;ไม่ว่าผู้รับจ้างจะเป็นผู้ที่นำของเข้ามาเองหรือนำเข้ามาโดยผ่านตัวแทนหรือบุคลอื่นใดถ้าสิ่งของนั้นต้องนำเข้ามาโดยทางเรือในเส้นทางเดินเรือที่มีเรือไทยเดินอยู่และสามารถให้บริการรับขนได้ตามที่รัฐมนตรีว่าการกระทรวงคมนาคมประกาศกำหนด&nbsp;ผู้รับจ้างต้องจัดการให้สิ่งของดังกล่าวบรรทุกโดยเรือไทยหรือเรือที่มีสิทธิเช่น
      เดียวกับเรือไทยจากต่างประเทศมายังประเทศไทยเว้น&nbsp;แต่จะได้รับอนุญาตจากกรมเจ้าท่าก่อนบรรทุกของนั้นลงเรืออื่นที่
      มิใช่เรือไทยหรือเป็นของที่รัฐมนตรีว่าการกระทรวงคมนาคมประกาศยกเว้นให้บรรทุกโดยเรืออื่นได้&nbsp;ทั้งนี้ไม่ว่าการสั่งหรือ
      นำเข้าสิ่งของดังกล่าวจากต่างประเทศจะเป็นแบบใด
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในการส่งมอบงานตามสัญญาให้แก่ผู้ว่าจ้าง&nbsp;ถ้างานนั้นมีสิ่งของตามวรรคหนึ่ง&nbsp;ผู้รับจ้างจะต้องส่งมอบใบ
      ตราส่ง&nbsp;(Bill&nbsp;of&nbsp;lading)&nbsp;หรือสำเนาใบตราส่งสำหรับของนั้น&nbsp;ซึ่งแสดงว่ได้บรรทุกมาโดยเรือไทยหรือเรือที่มีสิทธิเช่นเดียวกับ
      เรือไทยให้แก่ไทยให้แก่ผู้ว่าจ้างพร้อมกับการส่งมอบงานด้วย
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีที่สิ่งของดังกล่าวไม่ได้บรรทุกจากต่างประเทศมายังประเทศไทยโดยเรือไทยหรือเรือที่มีสิทธิเช่นเดียวกับเรือไทย&nbsp;ผู้รับจ้างต้องส่งมอบหลักฐานซึ่งแสดงว่าได้รับอนุญาตจากกรมเจ้าท่า&nbsp;ให้บรรทุกของโดยเรืออื่นได้&nbsp;หรือหลักฐาน&nbsp;
      ซึ่งแสดงว่าได้ชำระค่าธรรมเนียมพิเศษเนื่องจากการไม่บรรทุกของโดยเรืไทยตามกฎหมายว่าด้วยการส่งเสริมการพาณิชยนาวี
      แล้วอย่างใดอย่างหนึ่งแก่ผู้ว่าจ้างด้วย
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ในกรณีที่ผู้รับจ้างไม่ส่งมอบหลักฐานอย่างใดอย่างหนึ่งดังกล่าวในวรรคสองและวรรคสามให้แก่ผู้ว่าจ้างแต่จะขอส่งมอบงานดังกล่าวให้ผู้ว่าจ้างก่อนโดยยังไม่รับชำระเงินค่าจ้าง&nbsp;ผู้ว่าจ้างมีสิทธิรับงานตังกล่าวไว้ก่อน&nbsp;และชำระเงินค่าจ้าง
      เมื่อผู้รับจ้างได้ปฏิบัติถูกต้องครบถ้วนดังกล่าวแล้วได้
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;สัญญานี้ทำขึ้นเป็นหนึ่งฉบับ&nbsp;คู่สัญญาได้อ่านและเข้าใจข้อความ&nbsp;โดยละเอียดตลอด&nbsp;แล้วจึงได้ลงลายมือชื่อ&nbsp;<br>พร้อมทั้งประทับตรา&nbsp;(ถ้ามี)&nbsp;ไว้เป็นสำคัญต่อหน้าพยาน&nbsp;และคู่สัญญาได้ยึดถือไว้ฝ่ายละหนึ่งฉบับ
    </p>
    <br>
    <p>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ................................................................ผู้ว่าจ้าง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?php echo $row4['ContractFname']; ?>&nbsp;&nbsp;
      <?php echo $row4['ContractLname']; ?>
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      <?php echo $row4['ContractUnder']; ?>
    </p>
    <p>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ................................................................ผู้รับจ้าง
      <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(
      <?php echo $row2['fname']; ?>
      <?php echo $row2['lname']; ?>)
    </p>

    <p>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ................................................................พยาน
    </p>
    <p>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(................................................................)
    </p>
    <p>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ลงชื่อ................................................................พยาน
    </p>
    <p>
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(................................................................)
    </p>















    <?php
    $html = ob_get_contents();
    $mpdf->WriteHTML($html);
    $mpdf->output("MyreportH.pdf");
    ob_end_flush();

    ?>
    <a href="MyreportH.pdf">Download</a>


    <script type='text/javascript'>
      window.location.href = "MyreportH.pdf";
    </script>




    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
      crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>

  </html>

<?php } ?>
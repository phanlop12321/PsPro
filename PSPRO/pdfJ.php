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
  $xyz = 0;
  $check = 0;

  $status = 0;



  $User = $_SESSION["User"];
  $id = $_SESSION["ID"];
  //$id = $_GET["create"];

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

  $sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
  $result2 = $conn->query($sql2);
  $row2 = $result2->fetch_assoc();

  $sql5 = "SELECT * FROM contract WHERE Id = $id AND ( User = '$User' )";
  $result5 = $conn->query($sql5);
  $row5 = $result5->fetch_assoc();

  $status = $row2['status'];

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


  <body style="font-size:16pt; line-height: normal; padding: 0em;" >
    <p style=" position: absolute; top: 2mm; left: 80mm; width: auto;">&nbsp;การไฟฟ้าส่วนภูมิภาค&nbsp;
    <br>รายงานผลการตรวจรับ</p>
    <div style=" position: absolute; top: 15mm; left: 140mm; width: auto;">
    <table style=" border-collapse: collapse; border: 1px solid black;">
    <tr>
      <th>
      <td>&nbsp;WBS.&nbsp;<?= $WBS[0]; ?></td>
      </th>
    </tr>
    <tr>
      <th>
      <td>&nbsp;สำหรับหนวยงาน&nbsp;<?= $row1["pea"]; ?></td>
      </th>
    </tr>
    </table>
    </div>
    <p style=" position: absolute; top: 15mm; left: 15mm; width: auto;">&nbsp;สัญญาจ้างเลขที่&nbsp;&nbsp;&nbsp;<?= $row5["ContractNo"]; ?>&nbsp;&nbsp;&nbsp;ลว.&nbsp;&nbsp;&nbsp;<?php  echo thai_date_fullmonth(strtotime($row5["ContractDate"]));?>&nbsp;
    <br>วิธีเฉพาะเจาะจงเลขที่&nbsp;&nbsp;&nbsp;<?php if ($row3["Nopaper"] == '') {
                                    echo $row1["county"]; ?>&nbsp;<?php echo $row1["pea"];
                                                                                                  echo "(&nbsp;&nbsp;&nbsp;)";
                                                                                                } else {
                                                                                                  echo $row3["Nopaper"];
                                                                                                } ?>&nbsp;&nbsp;&nbsp;ลว.&nbsp;<?php if ($row3["Nopaperdate"] != '') {
                                                                                                  echo thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
                                                                                                } ?>
    <br>คู่สัญญา&nbsp;&nbsp;&nbsp;<?= $row2["fname"]; ?>&nbsp;&nbsp;<?= $row2["lname"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;ผู้ผลิต/ประเทศ&nbsp;&nbsp;&nbsp;<?= $row3["Address"]; ?></p>
    <br><br><br>
    <hr>
    <p style=" position: absolute; top: 40mm; left: 15mm; width: auto;">เรียน&nbsp;&nbsp;&nbsp;ผจก.<?= $row1["pea"]; ?>
    <br>คู่สัญญา&nbsp;ได้ส่งพัสดุที่&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&#9634;คลัง&nbsp;&nbsp;&nbsp;<?= $row1["pea"]; ?>&nbsp;&nbsp;&nbsp;&#9634;อื่นๆ__________เมื่อวันที่_______________
    <br>ใบกำกับภาษีเลขที่________ลงวันที่____________ใบฝากพัสดุเลขที่____________ลงวันที่________________
    <br>คณะกรรมการฯ ได้ทำการตรวจรับเมื่อวันที่____________________________รายละเอียดดังนี้</p>
    <br><br><br><br><br>
    
    <table style="border-collapse: collapse;">


<tr style="border: 1px solid black;">
  <th style="border: 1px solid black; width:5%; text-align: center;"  >ที่</th>
  <th style="border: 1px solid black; text-align: center;" >แผนก</th>
  <th style="border: 1px solid black; width:50%;text-align: center;" >รายละเอียด</th>
  <th style="border: 1px solid black; text-align: center;"  >จำนวน(ชิ้น)</th>
  <th style="border: 1px solid black; text-align: center;"  >หน่วยละ(บาท)</th>
  <th style="border: 1px solid black; text-align: center;"  >จำนวนเงิน(บาท)</th>
</tr>
    <?php


    $result5 = $conn->query($sql4);
    

    $x = 0;
    $i = 1;
    while ($row5 = $result5->fetch_assoc()) {
      $x++;
    ?>



        <?php


        $sql = "SELECT * FROM end_data WHERE network = {$row5['NETWORK']}";
        $result = $conn->query($sql);
        // output data of each row


        while ($row = $result->fetch_assoc()) {
          echo $xyz;
          echo "-----";
          
          $xyz = ($row["newprice"]* $row["qty"]) + $xyz;



        ?>
          <tr style="border: 1px solid black;">
          <?php if ($row["quantity"] == $row["qty"]){?>
            <td style="border: 1px solid black; text-align: center;"><?= $i ?></td>
            <td style="border: 1px solid black;"><?= $row["job"]; ?></td>
            <td style="border: 1px solid black;"><?= $row["name"]; ?></td>
            <td style="border: 1px solid black; text-align: right;"><?= $row["qty"];echo " ";echo $row["unit"] ; ?></td>
            <td style="border: 1px solid black; text-align: right;"><?php echo number_format($row["newprice"], 2); ?></td>
            <td style="border: 1px solid black; text-align: right; "><?php echo number_format($row["newprice"] * $row["qty"], 2); ?></td>
          <?php }else{ $check = $check + 1;?>
            <td style="border: 1px solid black; text-align: center; background-color: yellow;"><?= $i ?></td>
            <td style="border: 1px solid black; background-color: yellow;"><?= $row["job"]; ?></td>
            <td style="border: 1px solid black; background-color: yellow;"><?= $row["name"]; ?></td>
            <td style="border: 1px solid black; text-align: right; background-color: yellow;"><?= $row["qty"];echo " ";echo $row["unit"] ; ?></td>
            <td style="border: 1px solid black; text-align: right; background-color: yellow;"><?php echo number_format($row["newprice"], 2); ?></td>
            <td style="border: 1px solid black; text-align: right; background-color: yellow;"><?php echo number_format($row["newprice"] * $row["qty"], 2); ?></td>
          <?php } ?>
          </tr>
        <?php
          $i = $i + 1;
        }
 } ?>
           <tr style="border: 1px solid black;">
            <td></td>
            <td></td>
            <td style="text-align: center;"  >รวม</td>
            <td></td>
            <td></td>
            <td style="border: 1px solid black; text-align: right;"  ><?php echo number_format($xyz, 2); ?></td>
          </tr>
          <tr style="border: 1px solid black;">
            <td></td>
            <td></td>
            <td style="text-align: center;"  >ภาษี 7 %</td>
            <td></td>
            <td></td>
            <td style="border: 1px solid black; text-align: right;"  ><?php if ($status == 2) { echo number_format($xyz*0.07, 2);} else { echo "-"; } ?></td>
          </tr>
          <tr style="border: 1px solid black;">
            <td></td>
            <td></td>
            <td style="text-align: center;"  >ราคารวมภาษีมูลค่าเพิ่ม</td>
            <td></td>
            <td></td>
            <td style="border: 1px solid black; text-align: right;"  ><?php if ($status == 2) { echo number_format(($xyz*0.07)+$xyz, 2);}else{ echo number_format($xyz, 2); }  ?></td>
          </tr>

    </table>
    
    <?php if ($check !==0){ ?>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. คณะกรรมการการตรวจรับ ได้ตรวจสภาพหน้างานและจำนวนพัสดุที่แจ้งขอส่งมอบงานแล้ว ปรากฏว่า ผู้รับจ้างได้ดำเนินการตามใบสั่งจ้างเลขที่&nbsp;&nbsp;<?= $row3["po"]; ?>&nbsp;&nbsp;ลว.&nbsp;&nbsp;<?php echo thai_date_fullmonth(strtotime($row3["po_date"])); ?>&nbsp;&nbsp;แต่จำนวนไม่ครบถ้วนตามสัญญา เนื่องจาก&nbsp;&nbsp;<?= $row3["etc"]; ?>&nbsp;&nbsp;โดยปรับลดจำนวน&nbsp;&nbsp;&nbsp;<?= $check; ?>&nbsp;&nbsp;&nbsp;รายการ โดยมีรายละเอียดตามตารางข้างต้น 
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. ตามรายงานการทดสอบคุณภาพของ________เลขที่____________ลงวันที่____________ปรากฏว่า ของมีรายละเอียดถูกต้องตามสัญญา คณะกรรมการตรวจรับพิจารณาแล้ว เห็นควรรับของไว้ใช้งาน
    </p>
    <?php }else{ ?>
    <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;1. คณะกรรมการการตรวจรับ ได้ตรวจสภาพหน้างานและจำนวนพัสดุที่แจ้งขอส่งมอบงานแล้ว ปรากฏว่า ผู้รับจ้างได้ดำเนินการตามใบสั่งจ้างเลขที่&nbsp;&nbsp;<?= $row3["po"]; ?>&nbsp;&nbsp;ลว.&nbsp;&nbsp;<?php echo thai_date_fullmonth(strtotime($row3["po_date"])); ?>&nbsp;&nbsp;เสร็จเป็นที่เรียบร้อยแล้วจึงขออนุมัติเบิกค่าจ้างให้ผู้รับจ้างต่อไป
    <br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;2. ตามรายงานการทดสอบคุณภาพของ________เลขที่____________ลงวันที่____________ปรากฏว่า ของมีรายละเอียดถูกต้องตามสัญญา คณะกรรมการตรวจรับพิจารณาแล้ว เห็นควรรับของไว้ใช้งาน
    </p>
    <?php } ?>

      <table stlye=" border-collapse: collapse;">
      <tr >
            <td style="border: 1px solid black;"  >ได้รับพัสดุจากคณะกรรมการตรวจรับไว้เป็นที่เรียบร้อย
                                          <br>แล้ว ตั้งแต่วันที่___________________________
                                        <br><br><br>
                                          <br>(ลงชื่อ)&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<?= $row1["Fname"]; ?>&nbsp;&nbsp;<?= $row1["Fname"];?>&nbsp;)
                                          <br>ตำแหน่ง&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?= $row1["Rank"]; ?>.ผ<?= $row1["Under"];?>.<?= $row1["pea"];?>&nbsp;
                                          <br>วันที่____________________________________</td>
            <td>&nbsp;&nbsp;&nbsp;(ลงชื่อ)__________________ประธานกรรมการ
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<?= $row3["FName_Chairman_Check"]; ?>&nbsp;&nbsp;<?= $row3["LName_Chairman_Check"];?>&nbsp;)&nbsp;&nbsp;&nbsp;<?= $row3["Rank_C_Check"];?>
            <br>
              <br>&nbsp;&nbsp;&nbsp;(ลงชื่อ)__________________กรรมการ
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<?= $row3["FName_Director_Check1"]; ?>&nbsp;&nbsp;<?= $row3["LName_Director_Check1"];?>&nbsp;)&nbsp;&nbsp;&nbsp;<?= $row3["Rank_D_Check1"];?>
              <br>
              <br>&nbsp;&nbsp;&nbsp;(ลงชื่อ)__________________กรรมการ
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(&nbsp;<?= $row3["FName_Director_Check2"]; ?>&nbsp;&nbsp;<?= $row3["LName_Director_Check2"];?>&nbsp;)&nbsp;&nbsp;&nbsp;<?= $row3["Rank_D_Check2"];?>

            </td>
      </tr>
      </table>
      <p>เรียน หผ.บห,หผ.บป 
        <br>          อนุมัติตามเสนอจำนวนเงิน&nbsp;&nbsp;<?php if ($status == 2) { echo number_format(($xyz*0.07)+$xyz, 2);}else{ echo number_format($xyz, 2); }  ?>&nbsp;&nbsp;บาท
      </p>

    

    <?php
    $html = ob_get_contents();
    $mpdf->WriteHTML($html);
    $mpdf->output("Myreportsfg.pdf");
    ob_end_flush();

    ?>
    <a href="Myreportsfg.pdf">Download</a>

    
    <script type='text/javascript'>
      window.location.href = "Myreportsfg.pdf";
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
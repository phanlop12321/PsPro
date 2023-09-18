<?php
session_start();

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





$all_price = 0;
$All_price_abd_vat = 0;
$All_price_abd_vat1 = 0;
$Under = '';
$Nopaperdate = '';

$dataname = '';
$dataunit = '';

$User = $_SESSION["User"];
//$User = 500306;
//echo $User;
//$id = $_SESSION["ID"];
$id = $_GET["create"];
//$id = 1;

$sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$User' )";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();

$ID_employee = $row3["Employee"];
$ID_vdlist = $row3["Vender_List"];


$sql1 = "SELECT * FROM employee WHERE ID=$ID_employee";
$result1 = $conn->query($sql1);
$row1 = $result1->fetch_assoc();

require_once 'bootstrap.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$fontStyleName1 = 'oneUserDefinedStyle1';
$phpWord->addFontStyle(
  $fontStyleName1,
  array('name' => 'TH SarabunIT๙', 'size' => 16, 'color' => '1B2232', 'bold' => false, 'spaceBefore' => 0, 'spaceAfter' => 0)
);

$fontStyleName2 = 'oneUserDefinedStyle2';
$phpWord->addFontStyle(
  $fontStyleName2,
  array('name' => 'TH SarabunIT๙', 'size' => 16, 'color' => '1B2232', 'bold' => true, 'spaceBefore' => 4, 'spaceAfter' => 4)
);

$fontStyleName3 = 'oneUserDefinedStyle3';
$phpWord->addFontStyle(
  $fontStyleName3,
  array('name' => 'TH SarabunIT๙', 'size' => 8, 'color' => '1B2232', 'bold' => false, 'spaceBefore' => 0, 'spaceAfter' => 0)
);


$cellHCentered2 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1);
//$cellHCentered2 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'boat');

$section = $phpWord->addSection(['marginTop' => 100, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 100]);
$section->addImage('img/pea.jpg', ['width' => 100, 'height' => 100]);
$table = $section->addTable();
$table->addRow();
$table->addCell(5000)->addText("จาก " . $row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"], $fontStyleName1, $cellHCentered2);
$table->addCell(2500)->addText("ถึง ผจก." . $row1["pea"], $fontStyleName1, $cellHCentered2);
$table->addRow();
$table->addCell(5000)->addText("เลขที่ ", $fontStyleName1, $cellHCentered2);
if ($row3["Nopaperdate"] != '') {
  $Nopaperdate = thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}

$table->addCell(2500)->addText("วันที่ " . $Nopaperdate, $fontStyleName1, $cellHCentered2);
$section->addText('เรื่อง  รายงานขอจ้าง', $fontStyleName1, $cellHCentered2);
if ($row1["Under"] == "") {
  $Under = '';
} else {
  $Under = "ผ่าน หผ." . $row1["Under"] . "." . $row1["pea"];
}
$section->addText('เรียน  ผจก.' . $row1["pea"] . ' ' . $Under, $fontStyleName1, $cellHCentered2);
if ($row1["Under"] != "") {
  $Under = 'ผ' . $row1["Under"] . '.' . $row1["pea"];
}
$section->addText(htmlspecialchars("\tด้วย " . $Under . " มีความประสงค์จ้างเหมาเอกชน (เฉพาะค่าเเรงงาน) ช่วยงานก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า " . $row3["Type_Budget"] . " ปี " . $row3["year"] . " ซึ่งมีรายละเอียดดังต่อไปนี้"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t1. เหตุผลความจำเป็นที่ต้องขอจ้าง"), $fontStyleName2, $cellHCentered2);
$text = thai_date_fullmonth(strtotime($row3["Construct_Date"]));
$text2 = thai_date_fullmonth(strtotime($row3["Estimate_Date"]));
$section->addText(htmlspecialchars("\t   1.1  ตามอนุมัติงานก่อสร้างเลขที่ " . $row3["Construct"] . " ลว. " . $text . " ให้ดำเนินการก่อสร้างงานขยายเขตระบบจำหน่ายไฟฟ้า บริเวณ " . $row3["Address"] . " อนุมัติประมาณการเลขที่ " . $row3["Estimate"] . "ลว. " . $text2), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t   1.2  เนื่องจาก " . $Under . " มีบุคลากรและยานพาหนะไม่เพียงพอในการก่อสร้างงานขยายเขต " . $row3["Type_Budget"] . " ปี " . $row3["year"] . " และเพื่อให้การดำเนินงานแล้วเสร็จตามวัตถุประสงค์ของลูกค้า และรองรับนโยบายโครงการ อย่างมีประสิทธิภาพ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t2. รายละเอียดของพัสดุที่จ้าง"), $fontStyleName2, $cellHCentered2);
//$section->addText(htmlspecialchars("\tรายละเอียดของพัสดุที่จ้าง (ตามเอกสารแนบ 1)"), $fontStyleName1, 'multipleTab');
$sql1234 = "SELECT * FROM new285data WHERE    user = $User  AND ( userid = $id  ) GROUP BY network";
$result1234 = $conn->query($sql1234);

$x = 0;
while ($row5 = $result1234->fetch_assoc()) {
  $x++;
  $section->addText(htmlspecialchars("\t   2." . $x . " หมายเลขงาน " . $row5["wbs"] . " โครงข่าย " . $row5["network"]), $fontStyleName1, $cellHCentered2);

  $styleTable = array('borderSize' => 6, 'cellMargin' => 80);
  $styleFirstRow = array('borderBottomSize' => 18);
  $styleCell = array('valign' => 'center');
  $styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
  $fontStyle = array('bold' => true);
  $fontStyleAlign = array('cellMargin' => 80, 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'align' => 'center');
  $fontStyleAlign1 = array('cellMargin' => 80, 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0);
  $phpWord->addTableStyle('Fancy Table', $styleTable);
  $table = $section->addTable('Fancy Table');


  $table->addRow();
  $table->addCell(500, $styleCell)->addText(htmlspecialchars('ที่'), $fontStyle, $fontStyleAlign);
  $table->addCell(2500, $styleCell)->addText(htmlspecialchars('แผนก'), $fontStyle, $fontStyleAlign);
  $table->addCell(2500, $styleCell)->addText(htmlspecialchars('ประเภทงาน'), $fontStyle, $fontStyleAlign);
  $table->addCell(5000, $styleCell)->addText(htmlspecialchars('รายละเอียด'), $fontStyle, $fontStyleAlign);
  $table->addCell(1500, $styleCell)->addText(htmlspecialchars('จำนวน'), $fontStyle, $fontStyleAlign);

  $sql = "SELECT * FROM new285data WHERE network = {$row5["network"]} AND ( user = $User )  AND ( userid = $id  )";
  $result = $conn->query($sql);
  // output data of each row
  $i = 1;
  while ($row = $result->fetch_assoc()) {
    $data = $row["id"];
    $sqldata = "SELECT * FROM data WHERE ID = $data";
    if ($resultdata = $conn->query($sqldata)) {
      $rowdata = $resultdata->fetch_assoc();
    }

    if (isset($rowdata["NAME"])) {
      $dataname = $rowdata["NAME"];
      $dataunit = $rowdata["UNIT"];
    } else {
      $dataname = $row["name"];
      $dataunit = $row["unit"];
      if ($dataunit == 'EA') {
        $dataunit = 'ชิ้น';
      }
    }
    if ($row["price"] != 0) {
      $table->addRow();
      $table->addCell()->addText(htmlspecialchars($i), null, $fontStyleAlign);
      $table->addCell()->addText(htmlspecialchars($row['job']), null, $fontStyleAlign);
      $table->addCell()->addText(htmlspecialchars($row['type']), null, $fontStyleAlign);
      $table->addCell()->addText(htmlspecialchars($dataname), null, $fontStyleAlign1);
      $table->addCell()->addText(htmlspecialchars("{$row['qty']}  {$dataunit}"), null, $fontStyleAlign1);

      $i = $i + 1;
      $all_price = $all_price + $row["price"];

    }
  }
}

$All_price_abd_vat = $all_price * 0.07;
$All_price_abd_vat1 = $All_price_abd_vat + $all_price;

$section->addText(htmlspecialchars("\t3. ราคากลางของงานที่จะจ้าง"), $fontStyleName2, $cellHCentered2);
$text = thai_date_fullmonth(strtotime($row3["Center_Price_Date"]));
$section->addText(htmlspecialchars("\t   ตามบันทึกกำหนดราคากลางในการจ้างเหมาก่อสร้างปรับปรุงระบบไฟฟ้า กรณีงานจ้างเหมาเฉพาะค่าแรง ตามอนุมัติที่ " . $row3["Center_Price"] . " ลว. " . $text . " ตามเอกสารแนบ (1) มีรายละเอียดดังต่อไปนี้"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t4. วงเงินที่จะจ้าง"), $fontStyleName2, $cellHCentered2);
$text2 = number_format($all_price, 2);
$text3 = number_format($All_price_abd_vat, 2);
$text5 = number_format($All_price_abd_vat1, 2);
$text4 = Convert($All_price_abd_vat + $all_price);
$section->addText(htmlspecialchars("\t   เงินงบประมาณเบิกจาก " . $row3["Type_Budget"] . " จากค่าเเรงในงานก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า บริเวณ " . $row3["Address"] . " งบประมาณ " . $text2 . "บาท ภาษีมูลค่าเพิ่ม " . $text3 . " บาท วงเงินรวมภาษีมูลค่าเพิ่ม " . $text5 . "  ( " . $text4 . "บาท  )"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t5. กำหนดเวลาที่ต้องการใช้พัสดุ"), $fontStyleName2, $cellHCentered2);
$section->addText(htmlspecialchars("\t   กำหนดส่งมอบงานแล้วเสร็จ " . $row3["delivery"] . " วัน นับจากวันลงนามในสัญญา"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t6. วิธีที่จะจ้างและเหตุผลที่จะต้องจ้างวิธีนั้น"), $fontStyleName2, $cellHCentered2);
$section->addText(htmlspecialchars("\t   6.1 พิจารณาเห็นสมควรดำเนินการจัดจ้างโดยวิธีเฉพาะเจาะจง ตามพระราชบัญญัติการจัดซื้อจัดจ้างและ การบริหารพัสดุภาครัฐ พ.ศ.2560 ตามมาตรา 56(2) (ข) เนื่องจากการจัดจ้างครั้งนี้มีราคาไม่เกิน 500,000.- บาท และดำเนินการตามระเบียบกระทรวงการคลังว่าด้วยการจัดซื้อจัดจ้าง และการบริหารพัสดุภาครัฐ พ.ศ.2560 ข้อ 79"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t   6.2 พิจารณาเห็นสมควรดำเนินการจัดจ้าง ตามกฏกระทรวง กำหนดพัสดุและวิธีการจัดซื้อจัดจ้างพัสดุที่รัฐ ต้องการส่งเสริมหรือสนับสนุน ( ฉบับที่ 2 ) พ.ศ. 2563 ข้อ 7 (2) (ก) และ หมวด 7/1 พัสดุส่งเสริมการผลิตภายในประเทศ ข้อ 27/3 (3) การจัดจ้างที่มิใช่คนก่อสร้าง"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t7. หลักเกณฑ์การพิจารณาคัดเลือกขอเสนอ"), $fontStyleName2, $cellHCentered2);
$section->addText(htmlspecialchars("\t   ( ) พิจารณาจากราคารวม ( ) พิจารณาจากราคาต่อรายการ ( / ) พิจารณาจากราคาต่อหน่วย"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t8. ข้อเสนออื่นๆ"), $fontStyleName2, $cellHCentered2);
$section->addText(htmlspecialchars("\t   8.1 เห็นควรให้เจ้าหน้าที่พัสดุ โดย " . $row1["Fname"] . " " . $row1["Lname"] . " ตำแหน่ง " . $row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"] . "  เป็นผู้ติดต่อตกลงกับผู้รับจ้างโดยตรง"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t   8.2 แต่งตั้งคณะกรรมการกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t       8.2.1 " . $row3["FName_Chairman_Center_Price"] . " " . $row3["Lname_Chairman_Center_Price"] . " ตำแหน่ง " . $row3["Rank_C_C"] . " ประธานกรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t       8.2.2 " . $row3["FName_Director_1"] . " " . $row3["LName_Director_1"] . " ตำแหน่ง " . $row3["Rank_D_C1"] . " กรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t       8.2.3 " . $row3["FName_Director_2"] . " " . $row3["LName_Director_2"] . " ตำแหน่ง " . $row3["Rank_D_C2"] . " กรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t   8.3 แต่งตั้งคณะกรรมกาตรวจรับพัสดุ/ผู้ตรวจรับพัสดุ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t       8.3.1 " . $row3["FName_Chairman_Check"] . " " . $row3["LName_Chairman_Check"] . " ตำแหน่ง " . $row3["Rank_C_Check"] . " ประธานกรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t       8.3.2 " . $row3["FName_Director_Check1"] . " " . $row3["LName_Director_Check1"] . " ตำแหน่ง " . $row3["Rank_D_Check1"] . " กรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t       8.3.3 " . $row3["FName_Director_Check2"] . " " . $row3["LName_Director_Check2"] . " ตำแหน่ง " . $row3["Rank_D_Check2"] . " กรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t   จึงเรียนมาเพื่อโปรดพิจารณา หากเห็นชอบขอได้โปรดอนุมัติให้ดำเนินการจัดซื้อโดยวิธีเฉพาะเจาะจง ตามมาตรา 52(2) (ข) ตามรายละเอียดในรายงานขอจ้างดังกล่าวข้างต้น"), $fontStyleName1, $cellHCentered2);
$section->addTextBreak(2);
$section->addText(htmlspecialchars("\t\t\tลงชื่อ __________________________ เจ้าหน้าที่"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t                ( " . $row1["Fname"] . " " . $row1["Lname"] . " )"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\tตำแหน่ง        " . $row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"]), $fontStyleName1, $cellHCentered2);

$section->addTextBreak(1);
$table1 = $section->addTable('Fancy Table');
$table1->addRow(3000);
$table1->addCell(4000, $styleCell)->addText(htmlspecialchars("\tเห็นชอบและอนุมัติตามเสนอ      \t\t\t\t\t\t           ลงชื่อ ________________________            ( ___________________________ )       ตำแหน่ง ______________________       วันที่ ________________________"), null, $fontStyleAlign, $cellHCentered2);
$section->addText(htmlspecialchars("*หมายเหตุ"), $fontStyleName3, $cellHCentered2);
$section->addText(htmlspecialchars("-หนังสือรายงานขอซื้อใช้สำหรับวิธีเฉพาะเจาะจง ที่มีวงเงินงบประมาณจัดซื้อแต่หละครั้งไม่เกิน 100,000.-บาท(รวมภาษีมูลค่าเพิ่ม)"), $fontStyleName3, $cellHCentered2);
$section->addText(htmlspecialchars("-หากมีข้อมูล หรือรายละเอียดมากกว่าที่กำหนด ให้ระบุได้ตามสมควร หรือเอกสารแนบเพิมได้"), $fontStyleName3, $cellHCentered2);

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('รายงานขอจ้าง.docx');

echo "<script type='text/javascript'>window.location.href = 'รายงานขอจ้าง.docx';</script>";
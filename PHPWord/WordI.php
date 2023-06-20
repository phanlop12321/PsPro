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



$price = 0;
$vat = 0;
$price_abd_vat = 0;
$all_price = 0;
$all_vat = 0;
$All_price_abd_vat = 0;
$WBS;
$count = 0;
$Under = '';
$Nopaperdate = '';

$priceAll = 0;

$All_price_no_vat = 0;

$pricev = 0;

$percent = 0;
$percent_v = 0;




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

$sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

$status = $row2['status'];

$sql1 = "SELECT * FROM employee WHERE ID=$ID_employee";
$result1 = $conn->query($sql1);
$row1 = $result1->fetch_assoc();

$sql6 = "SELECT * FROM new285data WHERE ( user = $User )  AND ( userid = $id  )";
$result6 = $conn->query($sql6);


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
$cellHCentered1 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'center');

$section = $phpWord->addSection(['marginTop' => 100, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 100]);
$table = $section->addTable();
$table->addRow(1100);
$table->addCell()->addImage('img/pea.jpg', ['width' => 50, 'height' => 50]);
$table->addCell(8000, ['valign' => 'center'])->addText("\tแบบฟอร์มตรวจสอบมาตรฐานงานก่อสร้างและปรับปรุงระบบจำหน่าย ของ กฟภ.", $fontStyleName1, $cellHCentered2);

$styleTable = array('cellMargin' => 0);
$phpWord->addTableStyle('Fancy Table', $styleTable);
$table = $section->addTable('Fancy Table');
$table->addRow();
$table->addCell(8000, ['cellMargin' => 0, 'borderTopSize' => 6, 'borderLeftSize' => 6])->addText("ชื่องาน " . $row3["Name"], $fontStyleName1, $cellHCentered2);
$table->addCell(3000, ['borderTopSize' => 6, 'borderRightSize' => 6])->addText("□ กฟภ.ดำเนินการ", $fontStyleName1, $cellHCentered2);
$table->addRow();
$table->addCell(8000, ['borderLeftSize' => 6])->addText("อนุมัติเลขที่__________ลว.________หมายเลขงาน_______________", $fontStyleName1, $cellHCentered2);
$table->addCell(3000, ['borderRightSize' => 6])->addText("□ งานจ้างฯบริษัท__________", $fontStyleName1, $cellHCentered2);
$table1 = $section->addTable('Fancy Table');
$table1->addRow();
$table1->addCell(11000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText(" ● ปริมาณงานแรงสูง________________วงจร-กม. จำนวนเสา_______ต้น", $fontStyleName1, $cellHCentered2);
$table1->addRow();
$table1->addCell(11000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   รับไฟจากสถานี________________ฟีดเดอร์________เฟสที่ต่อ_______หม้อแปลงรวม_______kVA.", $fontStyleName1, $cellHCentered2);
$table1->addRow();
$table1->addCell(11000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText(" ● ปริมาณงานแรงต่ำ________________วงจร-กม. จำนวนเสา_______ต้น", $fontStyleName1, $cellHCentered2);
$table1->addRow();
$table1->addCell(11000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("ผู้ควบคุมงาน__________________ตำแหน่ง____________สังกัด_____________", $fontStyleName1, $cellHCentered2);
$table1->addRow();
$table1->addCell(11000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("วัน/เดือน/ปี ที่ดำเนินการตรวจ__________________", $fontStyleName1, $cellHCentered2);
$table1->addRow();
$table1->addCell(11000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("ผลการตรวจ ให้ทำเครื่องหมาย ✔ หมายถึงถูกต้อง หรือ ✖ หมายถึง ต้องแก้ไข หรือ ━ หมายถึงไม่มีการตรวจ", $fontStyleName1, $cellHCentered2);
$table2 = $section->addTable('Fancy Table');
$table2->addRow();
$table2->addCell(5000, ['borderSize' => 6])->addText("รายการ", $fontStyleName1, $cellHCentered1);
$table2->addCell(1500, ['borderSize' => 6])->addText("ผลการตรวจ", $fontStyleName1, $cellHCentered1);
$table2->addCell(3000, ['borderSize' => 6])->addText("รายละเอียดที่ต้องแก้ไข", $fontStyleName1, $cellHCentered1);
$table2->addCell(1500, ['borderSize' => 6])->addText("หมายเหตุ", $fontStyleName1, $cellHCentered1);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.ระบบจำหน่ายแรงสูง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.1 การปักเสา,เสาตอม่อ (ความลึก,แนวเสา,หน้าเสา)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.2 การติดตั้งคอน ลูกถ้วย และประกอบบอนด์ไวร์", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.3 การติดตั้งเหล็กรับสายล่าฟ้า (เหล็กฉาก,เหล็กรูปรางน้ำ)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.4 การฝังสมอบก และประกอบยึดโยงระบบจำหน่าย", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.5 การฝังสมอบก และประกอบยึดโยงสายล่าฟ้า", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.6 การพาดสายไฟ ระยะหน่อนยาน", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.7  การพาดสายล่อฟ้า ระยะหย่อนยาน", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.8 ระยะห่างความสูงของสายไฟ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ข้ามถนน > 6.1 ม.,ข้ามทางหลวง 22 kV. > 7.5 ม.", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ข้ามทางหลวง 33 kV. > 9 ม.", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ข้ามทางรถไฟ > 9 ม.", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ระยะห่างสายด้านข้ากับสิ่งก่อสร้างต่างๆ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ระยะห่างระบบจำหน่ายแรงสูง กับสายส่ง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ระยะห่างระบบจำหน่ายแรงสูง กับสายแรงต่ำ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.9 การพันและผูกลูกถ้วย", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.10 การต่อสาย พันเทป (สายหุ้มฉนวน)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.11 การเชื่อมสาย,สายแยก พันเทป(สายหุ้มฉนวน)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.12 การเข้าปลายสาย", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.13 การตัดต้นไม้", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.14 การทาสีเสา", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.15 การพ่นสี หมายเลขเสา", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.16 การยึดโยง (storm guy,line guy,fix guy,etc.)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.17 การต่อลงดิน", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("    - ค่าความต้านทานดินต่อจุด___(โอห์ม),ระบบ___โอห์ม", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.18 การติดตั้งกับดักแรงสูง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("1.19 อื่นๆ_________________________________", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.ระบบจำหน่ายแรงต่ำ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.1 การปักเสา,เสาตอม่อ (ความลึก,แนวเสา,หน้าเสา)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.2 การติดตั้งคอน แร็ค", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.3 การฝังสมอบก และประกอบยึดโยง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.4 การพาดสายไฟ ระยะหย่อนยาน", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.5 ระยะห่างความสูงของสายไฟ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ข้ามถนน > 5.5 ม.,ข้ามทางหลวง > 6.0 ม.", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ข้ามทางรถไฟ > 7 ม.", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ระยะห่างสายด้านข้างกับสิ่งก่อสร้างต่างๆ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.6 การผูกสายไฟกับลูกรอกแรงต่ำ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.7 การต่อสาน พันเทป", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.8 การเชื่อมสาย,แยกสาย พันเทป", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.9 การเข้าปลายสาย พันเทป", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.10 การติดตั้งกับดักเสิร์จเเรงต่ำ พันเทป", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.11 การทาสีเสา", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.12 การพ่นสีหมายเลขเสา", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.13 การยึดโยง (storm guy,line guy,fix guy,etc.)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.14 การต่อลงดิน", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.15 ค่าความต้านทานดินรวม____โอห์ม", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("2.16 อื่นๆ_________________________________", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.การติดตั้งหม้อแปลง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("TR_______O______kVA", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText(" □ แขวนเสา □ นั่งร้าน", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.1 การติดตั้งหม้อแปลง(ระยะความสูง,ทิศทาง)", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.2 การติดตั้งคอน ลูกถ้วย และประกอบบอนด์ไวร์", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.3 การพาดสายแรงสูงเข้าหม้อแหลง และลำดับเฟส", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.4 การผูกสายไฟกับลูกถ้วย", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.5 การติดตั้งกับดักเสิร์จแรงสูง, หางปลา", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.6 การติดตั้งดร็อปเอาต์, พินเทอร์มินอล และฟิวส์ลิงก์", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.7 การติดตั้งดอนสปัน 3,200 มม. ระยะความสูง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.8 การเข้าสายบุชชิ่ง, หางปลา, ฉนวนครอบบุชชิ่ง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.9 การติดตั้งสายแรงต่ำ และลำดับเฟส", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.10 การติดตั้งกับดักเสิร์จแรงต่ำ พันเทป", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.11 การติดตั้งคอนสำหรับ LT, LT สวิตช์ และฟิวส์แรงต่ำ ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.12 การติดตั้งที่จับขอบถัง, เหล็กแขวน ท่อร้อยสายแรงต่ำ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.13 เทคอนกรีดที่คาน, โคนเสา", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.14 การต่อลงท่อ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - ตัวถังหม้อแปลง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - สายกราวด์ด้านแรงสูง", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("   - สายกราวด์ด้านแรงต่ำ", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.15 ค่าความต้านทานดินต่อจุด__(โอห์ม),ระบบ__โอห์ม", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("3.16 อื่นๆ_________________________________", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addRow();
$table2->addCell(5000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(3000, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table2->addCell(1500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table3 = $section->addTable('Fancy Table');
$table3->addRow();
$table3->addCell(11000, ['borderSize' => 6])->addText("ผลการตรวจสอบ", $fontStyleName1, $cellHCentered1);
$table4 = $section->addTable('Fancy Table');
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("  □ ตรวจสอบแล้ว ถูกต้องตามมาตรฐาน กฟภ.", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderSize' => 6])->addText("กรณีมีการแก้ไข", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("  □ ตรวจสอบแล้ว เห็นควรแก้ไขให้ถูกต้องตามรายการข้างต้น", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("ได้แก้ไขให้ถูกต้องตามมาตรฐานทุกรายการแล้ว", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText(" ผู้ตรวจสอบ", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("ผู้ควบคุมงาน___________________ลว____________", $fontStyleName1, $cellHCentered2);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText(" ", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("(_________________________)", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("1.____________________________ตำแหน่ง_________________", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("  (____________________________)", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("ผู้ควบคุมงาน___________________ลว____________", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("  รับรองผลการแก้ไข", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("2.____________________________ตำแหน่ง_________________", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("  (____________________________)", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("        หผ. ___________________ลว____________", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("(_________________________)", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("3.____________________________ตำแหน่ง_________________", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderLeftSize' => 6])->addText("  (____________________________)", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered1);
$table4->addRow();
$table4->addCell(6500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);
$table4->addCell(4500, ['borderBottomSize' => 6, 'borderLeftSize' => 6, 'borderRightSize' => 6])->addText("", $fontStyleName1, $cellHCentered2);



// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน.docx');

echo "<script type='text/javascript'>window.location.href = 'ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน.docx';</script>";
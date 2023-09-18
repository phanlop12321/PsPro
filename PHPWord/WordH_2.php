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

$sql6 = "SELECT * FROM new285data WHERE ( user = $User )  AND ( userid = $id  ) AND ( type = 'Remove(งานรื้อถอน)') GROUP BY network";
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
$cellHCentered1 = array('align' => 'center', 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1);

$section = $phpWord->addSection(['marginTop' => 100, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 100]);
$section->addImage('img/pea.jpg', ['width' => 100, 'height' => 100]);
$table = $section->addTable();
$table->addRow();
$table->addCell(4000)->addText("จาก  " . $row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"], $fontStyleName1, $cellHCentered2);
$table->addCell(2500)->addText("ถึง ผจก." . $row1["pea"], $fontStyleName1, $cellHCentered2);
$table->addRow();
$table->addCell(4000)->addText("เลขที่  " . $row1["county"] . " " . $row1["pea"] . " (  )", $fontStyleName1, $cellHCentered2);
if ($row3["Nopaperdate"] != '') {
  $Nopaperdate = thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}

$table->addCell(2500)->addText("วันที่ " . $Nopaperdate, $fontStyleName1, $cellHCentered2);
$section->addText('เรื่อง  ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน', $fontStyleName1, $cellHCentered2);
if ($row1["Under"] == "") {
  $Under = '';
} else {
  $Under = "ผ่าน หผ." . $row1["Under"] . "." . $row1["pea"];
}
$section->addText('เรียน  ผจก.' . $row1["pea"] . ' ' . $Under, $fontStyleName1, $cellHCentered2);
if ($row1["Under"] != "") {
  $Under = 'ผ' . $row1["Under"] . '.' . $row1["pea"];
}

if ($row3["Nopaper"] == '') {
  echo $row1["county"]; ?>&nbsp;
  <?php echo $row1["pea"];
  $text3 = "(    ) ลว.                            ";
} else {
  $text3 = $row3["Nopaper"] . " ลว. " . thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}
$monney = 0;
// $row6 = $result6->fetch_assoc();


$section->addText(htmlspecialchars("\tตามที่อ้างถึงให้สำรวจทรัพย์สินอุปกรณ์เพื่อรื้อถอนระบบจำหน่ายงาน " . $row3["Name"] . " อนุมัติ เลขที่ " . $row3["Demolish"] . " ลว. " . thai_date_fullmonth(strtotime($row3["Demolish_date"])) . " ซึ่งได้สำรวจแล้วเสร็จ เมื่อวันที่ " . thai_date_fullmonth(strtotime($row3["Demolish_finish"])) . "รายละเอียดมีดังนี้.- "), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t1. ที่ตั้งทรัพย์สิน(ชื่อบ้าน,ชื่อสถานี) " . $row3["Address"]), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t2. ทรัพย์สินที่รื้ถอนได้ก่อสร้างเมื่อ ปี พ.ศ._____________"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t3. หมายเลขงานที่รื้อถอน.-"), $fontStyleName1, $cellHCentered2);
while ($row6 = $result6->fetch_assoc()) {
  $section->addText(htmlspecialchars("\t\t1 " . $row6["wbs"] . " แผนก " . $row6["job"] . " โครงข่าย " . $row6["network"]), $fontStyleName1, $cellHCentered2);
}

$section->addText(htmlspecialchars("จึงเรียนมาเพื่อโปรดทราบและพิจารณาสั่งการต่อไป"), $fontStyleName1, $cellHCentered2);
$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\tลงชื่อ     __________________ "), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t           ( " . $row3["FName_Demolish_Check"] . " " . $row3["LName_Demolish_Check"] . " )\t   ตำแหน่ง\t" . $row3["Rank_Demolish_Check"]), $fontStyleName1, $cellHCentered2);
$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\tลงชื่อ     __________________ "), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t           ( " . $row3["FName_Demolish_Check2"] . " " . $row3["LName_Demolish_Check2"] . " )\t   ตำแหน่ง\t" . $row3["Rank_Demolish_Check2"]), $fontStyleName1, $cellHCentered2);
$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\tลงชื่อ     __________________ "), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t           ( " . $row3["FName_Demolish_Check3"] . " " . $row3["LName_Demolish_Check3"] . " )\t   ตำแหน่ง\t" . $row3["Rank_Demolish_Check3"]), $fontStyleName1, $cellHCentered2);


$styleTable = array('cellMargin' => 18);
$phpWord->addTableStyle('Fancy Table', $styleTable);

$section->addTextBreak(1);
$table1 = $section->addTable('Fancy Table');
$table1->addRow();
$table1->addCell(5500, ['borderTopSize' => 6, 'borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(" 1. พชง.ควบคุมงาน/ผู้ควบคุมงานจ้าง"), null, $cellHCentered2);
$table1->addCell(5500, ['borderTopSize' => 6, 'borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(" 2. เรียน อ.ข _______/ ผจก._______"), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("   ดำเนินการรื้อถอนและรายการผลตาม กส.4 ป.47"), null, $cellHCentered2);
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("   ได้ทำการรือถอนแล้วเสร็จเมื่อวันที่________________ และได้ทำการส่งคืนอุปกรณ์เรียบร้อยตามเอกสารแนบ"), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(""), null, $cellHCentered2);
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(""), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("   ลงชื่อ"), null, $cellHCentered2);
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("   ลงชื่อ"), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("( " . $row1["Fname"] . " " . $row1["Lname"] . " )"), null, $cellHCentered1);
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("       (_____________________________________)"), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars($row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"]), null, $cellHCentered1);
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(""), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("   ลงวันที่___________________________________"), null, $cellHCentered2);
$table1->addCell(5500, ['borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars("   ลงวันที่___________________________________"), null, $cellHCentered2);
$table1->addRow();
$table1->addCell(5500, ['borderBottomSize' => 6, 'borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(""), null, $cellHCentered2);
$table1->addCell(5500, ['borderBottomSize' => 6, 'borderRightSize' => 6, 'borderLeftSize' => 6])->addText(htmlspecialchars(""), null, $cellHCentered2);



// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน_.docx');

echo "<script type='text/javascript'>window.location.href = 'ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน_.docx';</script>";
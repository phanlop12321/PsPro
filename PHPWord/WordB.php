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

$sql4 = "SELECT * FROM new285data WHERE  userid = $id AND ( user = '$User' )";
$result4 = $conn->query($sql4);
$row4 = $result4->fetch_assoc();

require_once 'bootstrap.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$fontStyleName1 = 'oneUserDefinedStyle1';
$phpWord->addFontStyle(
  $fontStyleName1,
  array('name' => 'TH SarabunIT๙', 'size' => 16, 'color' => '1B2232', 'bold' => false)
);

$fontStyleName2 = 'oneUserDefinedStyle2';
$phpWord->addFontStyle(
  $fontStyleName2,
  array('name' => 'TH SarabunIT๙', 'size' => 16, 'color' => '1B2232', 'bold' => true)
);

$fontStyleName3 = 'oneUserDefinedStyle3';
$phpWord->addFontStyle(
  $fontStyleName3,
  array('name' => 'TH SarabunIT๙', 'size' => 8, 'color' => '1B2232', 'bold' => false)
);

$cellHCentered2 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => '20');

$section = $phpWord->addSection(['marginTop' => 100, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 0]);
$section->addImage('img/pea.jpg', ['width' => 100, 'height' => 100]);
$table = $section->addTable();
$table->addRow();
$table->addCell(5000)->addText("จาก  คณะกรรมการกำหนดราคากลาง", $fontStyleName1, $cellHCentered2);
$table->addCell(2500)->addText("ถึง ผจก." . $row1["pea"], $fontStyleName1, $cellHCentered2);
$table->addRow();
$table->addCell(5000)->addText("เลขที่ ", $fontStyleName1, $cellHCentered2);
if ($row3["Nopaperdate"] != '') {
  $Nopaperdate = thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}

$table->addCell(2500)->addText("วันที่ " . $Nopaperdate, $fontStyleName1, $cellHCentered2);
$section->addText('เรื่อง  ขออนุมัติกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า (เฉพาะค่าเเรง)', $fontStyleName1, $cellHCentered2);
if ($row1["Under"] == "") {
  $Under = '';
} else {
  $Under = "ผ่าน หผ." . $row1["Under"] . "." . $row1["pea"];
}
$section->addText('เรียน  ผจก.' . $row1["pea"] . ' ' . $Under, $fontStyleName1, $cellHCentered2);
if ($row1["Under"] != "") {
  $Under = 'ผ' . $row1["Under"] . '.' . $row1["pea"];
}
$section->addText(htmlspecialchars("\t1. เรื่องเดิม"), $fontStyleName2, $cellHCentered2);
$text = thai_date_fullmonth(strtotime($row3["Center_Price_Date"]));
$text2 = substr($row3["Center_Price_Date"], 0, 4) + 543;

$section->addText(htmlspecialchars("\t   1.1  ตามบันทึกที่ " . $row3["Center_Price"] . " ลว. " . $text . " ได้อนุมัติแต่งตั้งผู้มีรายนามข้างท้ายนี้ เป็นคณะกรรมการกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า ประจำปี " . $text2 . " นั้น"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t2. ข้อเท็จจริง"), $fontStyleName2, $cellHCentered2);
if ($row3["Nopaper"] == '') {
  echo $row1["county"]; ?>&nbsp;
  <?php echo $row1["pea"];
  $text3 = "(    ) ลว.                            ";
} else {
  $text3 = $row3["Nopaper"] . " ลว. " . thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}
$text4 = "";
for ($B = 0; $B < $count; $B++) {
  $text4 .= $WBS[$B];
  if ($B < ($count - 1)) {
    $text4 .= ", ";
  }
}
$text5 = substr($row3["Center_Price_Date"], 0, 4) + 543;
$section->addText(htmlspecialchars("\t   2.1  ตามรายงานขอจ้างเลขที่ " . $text3 . " ได้ขออนุมัติให้ คณะกรรมการกำหนดราคา งานจ้างเหมาเฉพาะค่าเเรงงาน บริเวณ " . $row3["Address"] . " ในหมายเลข WBS " . $row4["wbs"] . " ตามอนุมัติประมาณการเลขที่ " . $row3["Estimate"] . " ลว. " . $text5), $fontStyleName1, $cellHCentered2);

$section->addText(htmlspecialchars("\t3. ข้อพิจารณา"), $fontStyleName2, $cellHCentered2);
$section->addText(htmlspecialchars("\t   3.1  จากรายละเอียด เรื่องเดิม ข้อเท็จจริง เพื่อให้เป็นไปตามหลักเกณฑ์ของ กฟภ. ในการคำนวณราคากลางจ้างเหมาก่อสร้างระบบไฟฟ้า และให้การจ้างเหมาเอกชนช่วยงานก่อสร้างระบบไฟฟ้าของ " . $row1["pea"] . " ในสังกัด กฟ" . $row1["county"] . " มีราคากลางในการจ้างเหมาฯ ที่เหมาะสมเป็นปัจจุบัน ดังนั้นเพื่อให้เกิดความคล่องตัวในการดำเนินการ คณะกรรมการฯตรวจสอบและ พิจารณางานแล้ว จึงขออนุมัติกำหนดราคากลางงานข้างต้น ตามแบบฟอร์ม การคำนาณราคากลาง งานจ้างเหมาระบบไฟฟ้า (เฉพาะค่าแรง)"), $fontStyleName1, $cellHCentered2);


$section->addText(htmlspecialchars("\tจึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ "), $fontStyleName1, $cellHCentered2);

$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\t\t\t\tลงชื่อ __________________________ ประธานกรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t\t\t\t                ( " . $row3["FName_Chairman_Center_Price"] . " " . $row3["Lname_Chairman_Center_Price"] . " )   " . $row3["Rank_C_C"]), $fontStyleName1, $cellHCentered2);

$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\t\t\t\tลงชื่อ __________________________ กรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t\t\t\t                ( " . $row3["FName_Director_1"] . " " . $row3["LName_Director_1"] . " )   " . $row3["Rank_D_C1"]), $fontStyleName1, $cellHCentered2);

$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\t\t\t\tลงชื่อ __________________________ กรรมการ"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t\t\t\t               ( " . $row3["FName_Director_2"] . " " . $row3["LName_Director_2"] . " )   " . $row3["Rank_D_C2"]), $fontStyleName1, $cellHCentered2);

$styleTable = array('borderSize' => 6, 'cellMargin' => 80);
$styleFirstRow = array('borderBottomSize' => 18);
$styleCell = array('valign' => 'center');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fontStyle = array('bold' => true);
$fontStyleAlign = array('align' => 'center');
$phpWord->addTableStyle('Fancy Table', $styleTable);

$table1 = $section->addTable('Fancy Table');
$table1->addRow(3000);
$table1->addCell(4000, $styleCell)->addText(htmlspecialchars("\tเห็นชอบและอนุมัติตามเสนอ      \t\t\t\t\t\t           ลงชื่อ ________________________            ( ___________________________ )       ตำแหน่ง ______________________       วันที่ ________________________"), null, $fontStyleAlign, $cellHCentered2);

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('ขออนุมัติกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า.docx');

echo "<script type='text/javascript'>window.location.href = 'ขออนุมัติกำหนดราคากลางในการจ้างเหมาก่อสร้างระบบไฟฟ้า.docx';</script>";
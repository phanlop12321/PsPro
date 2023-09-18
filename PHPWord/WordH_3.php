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
$rowNetwork = '';
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
while ($row6 = $result6->fetch_assoc()) {
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
  $cellRowContinue = array('borderSize' => 6, 'vMerge' => 'continue');
  $styleCell = array('borderSize' => 6, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
  $section = $phpWord->addSection(['marginTop' => 100, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 100]);
  $section->addText(htmlspecialchars("รายงานการสำรวจและการรื้อถอนทรัพย์สินอุปกรณ์ระบบไฟฟ้า"), $fontStyleName2, $cellHCentered1);
  $section->addText(htmlspecialchars("งานรือถอน หมายเลขงาน " . $row6["wbs"] . " โครงข่าย " . $row6["network"] . " แผนก " . $row6["job"]), $fontStyleName1, $cellHCentered2);
  $section->addText(htmlspecialchars("สถานที่ตั้งทรัพย์สินที่รื้อถอน " . $row3["Address"] . " ทรัพย์สินที่รื้อถอนได้ก่อสร้างเมื่อ ปี พ.ศ. _________"), $fontStyleName1, $cellHCentered2);

  $styleTable = array('cellMargin' => 18);
  $phpWord->addTableStyle('Fancy Table', $styleTable);
  $table1 = $section->addTable('Fancy Table');
  $table1->addRow(null, ['vMerge' => 'restart']);
  $table1->addCell(1000, $styleCell)->addText(htmlspecialchars("ที่"), null, $cellHCentered1);
  $table1->addCell(6000, $styleCell)->addText(htmlspecialchars("ชื่ออุปกรณ์"), null, $cellHCentered1);
  $table1->addCell(1000, ['borderSize' => 6, 'gridSpan' => 2, 'valign' => 'center'])->addText(htmlspecialchars("จำนวน"), null, $cellHCentered1);
  $table1->addCell(1000, ['borderSize' => 6, 'gridSpan' => 3, 'valign' => 'center'])->addText(htmlspecialchars("สภาพของอุปกรณ์"), null, $cellHCentered1);
  $table1->addCell(200, $styleCell)->addText(htmlspecialchars("จำนวน ที่รื้อถอน"), null, $cellHCentered1);
  $table1->addCell(1000, ['borderSize' => 6, 'gridSpan' => 2, 'valign' => 'center'])->addText(htmlspecialchars("การส่งคืนคลัง"), null, $cellHCentered1);
  $table1->addCell(1000, $styleCell)->addText(htmlspecialchars("หมายเหตุ"), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, $cellRowContinue);
  $table1->addCell(null, $cellRowContinue);
  $table1->addCell(null, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("ตามประมาณการ"), null, $cellHCentered1);
  $table1->addCell(null, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("จากสำรวจ"), null, $cellHCentered1);
  $table1->addCell(330, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("ดี"), null, $cellHCentered1);
  $table1->addCell(330, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("ชำรุด"), null, $cellHCentered1);
  $table1->addCell(330, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("ล้าสมัย"), null, $cellHCentered1);
  $table1->addCell(null, $cellRowContinue);
  $table1->addCell(null, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("จำนวน"), null, $cellHCentered1);
  $table1->addCell(null, ['borderSize' => 6, 'valign' => 'center'])->addText(htmlspecialchars("เลขใบ ส่งคืน/ลว."), null, $cellHCentered1);
  $table1->addCell(null, $cellRowContinue);
  $rowNetwork = $row6['network'];
  $sql1234 = "SELECT * FROM new285data WHERE    user = $User  AND ( userid = $id  ) AND ( type = 'Remove(งานรื้อถอน)') AND ( network  = '$rowNetwork')";
  $result1234 = $conn->query($sql1234);
  $x = 1;
  while ($row5 = $result1234->fetch_assoc()) {
    $data = $row5["id"];
    $sqldata = "SELECT * FROM data WHERE ID = $data";
    $resultdata = $conn->query($sqldata);
    $rowdata = $resultdata->fetch_assoc();
    if (isset($rowdata["NAME"])) {
      $dataname = $rowdata["NAME"];
      $dataunit = $rowdata["UNIT"];
    } else {
      $dataname = $row5["name"];
      $dataunit = $row5["unit"];
      if ($dataunit == 'EA') {
        $dataunit = 'ชิ้น';
      }
    }
    $table1->addRow();
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars($x), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars($dataname), null, $cellHCentered2);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars($row5['qty'] . " " . $dataunit), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars($row5['newqty'] . " " . $dataunit), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $table1->addCell(null, ['borderSize' => 6,])->addText(htmlspecialchars(""), null, $cellHCentered1);
    $x++;
  }

  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("ลายมือชื่อผู้สำรวจ"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars("ลายมือชื่อ พชง.ควบคุมงาน/ผู้ควบคุมงานจ้าง"), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("1______________________________ วันที่ ____________"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars(""), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("    นาย " . $row3["FName_Demolish_Check"] . " " . $row3["LName_Demolish_Check"] . "   ( " . $row3["Rank_Demolish_Check"] . " )"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars("ลงชื่อ____________________________"), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars(""), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars("( " . $row1["Fname"] . " " . $row1["Lname"] . " )"), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("2______________________________ วันที่ ____________"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars($row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"]), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("    นาย " . $row3["FName_Demolish_Check2"] . " " . $row3["LName_Demolish_Check2"] . "   ( " . $row3["Rank_Demolish_Check2"] . " )"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars(""), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars(""), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars("ลงวันที่__________________"), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("3______________________________ วันที่ ____________"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars(""), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars("    นาย " . $row3["FName_Demolish_Check3"] . " " . $row3["LName_Demolish_Check3"] . "   ( " . $row3["Rank_Demolish_Check3"] . " )"), null, $cellHCentered1);
  $table1->addCell(null, ['borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars(""), null, $cellHCentered1);
  $table1->addRow();
  $table1->addCell(null, ['borderBottomSize' => 6, 'borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 4])->addText(htmlspecialchars(""), null, $cellHCentered2);
  $table1->addCell(null, ['borderBottomSize' => 6, 'borderRightSize' => 6, 'borderLeftSize' => 6, 'gridSpan' => 7])->addText(htmlspecialchars(""), null, $cellHCentered1);
  // Saving the document as OOXML file...
}
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('รายงานการสำรวจและการรื้อถอนทรัพย์สินอุปกรณ์ระบบไฟฟ้า__.docx');

echo "<script type='text/javascript'>window.location.href = 'รายงานการสำรวจและการรื้อถอนทรัพย์สินอุปกรณ์ระบบไฟฟ้า__.docx';</script>";
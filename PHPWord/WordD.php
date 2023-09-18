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


$i = 1;
$ii = 0;
$price = 0;
$vat = 0;
$price_abd_vat = 0;
$all_price = 0;
$all_vat = 0;
$All_price_abd_vat = 0;
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

$sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

$status = $row2['status'];




require_once 'bootstrap.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$fontStyleName1 = 'oneUserDefinedStyle1';
$phpWord->addFontStyle(
  $fontStyleName1,
  array('name' => 'TH SarabunIT๙', 'size' => 14, 'color' => '1B2232', 'bold' => false, 'spaceBefore' => 0, 'spaceAfter' => 0)
);

$fontStyleName2 = 'oneUserDefinedStyle2';
$phpWord->addFontStyle(
  $fontStyleName2,
  array('name' => 'TH SarabunIT๙', 'size' => 14, 'color' => '1B2232', 'bold' => true, 'spaceBefore' => 0, 'spaceAfter' => 0)
);

$fontStyleName3 = 'oneUserDefinedStyle3';
$phpWord->addFontStyle(
  $fontStyleName3,
  array('name' => 'TH SarabunIT๙', 'size' => 11, 'color' => '1B2232', 'bold' => true, 'spaceBefore' => 0, 'spaceAfter' => 0)
);

$fontStyleName4 = 'oneUserDefinedStyle3';
$phpWord->addFontStyle(
  $fontStyleName4,
  array('name' => 'TH SarabunIT๙', 'size' => 14, 'color' => '1B2232', 'bold' => true, 'spaceBefore' => 0, 'spaceAfter' => 0)
);
$cellHCentered2 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1);
$paragraphOptions = array('space' => array('line' => 1000));
$phpWord->addParagraphStyle('P-listStyle', array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0));

$section = $phpWord->addSection(['marginTop' => 200, 'marginLeft' => 200, 'marginRight' => 200, 'marginBottom' => 200]);

$section->getStyle()
  ->setPaperSize('A4')
  ->setLandscape()
;

$sql = "SELECT * FROM new285data WHERE price != 0 AND ( user = $User )  AND ( userid = $id  ) ";
$result = $conn->query($sql);
$resultrow = $conn->query($sql);
$num_rows = mysqli_num_rows($resultrow);
$countLoop;
$countLoop = $num_rows / 5;
//echo ($countLoop);

$styleTable = array('cellMargin' => 80, 'topFromText' => 6, 'bottomFromText' => 6);
$cellRowSpan = array('borderSize' => 6, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$styleFirstRow = array('borderBottomSize' => 18);
$cellRowContinue = array('borderSize' => 6, 'vMerge' => 'continue');
$cellColSpan = array('borderSize' => 6, 'gridSpan' => 2, 'valign' => 'center');
$cellColSpan3 = array('borderSize' => 6, 'gridSpan' => 3, 'valign' => 'center');
$styleCell = array('borderSize' => 6, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$styleCell2 = array('borderBottomSize' => 6, 'topFromText' => 0, 'bottomFromText' => 0, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$styleCell4 = array('borderBottomSize' => 6, 'borderLeftSize' => 6, 'topFromText' => 0, 'bottomFromText' => 0, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$styleCell5 = array('borderBottomSize' => 6, 'borderRightSize' => 6, 'topFromText' => 0, 'bottomFromText' => 0, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$styleCell3 = array('borderBottomSize' => 6, 'cellMargin' => 20, 'vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fontStyle = array('name' => 'TH SarabunIT๙', 'size' => 14, 'bold' => true);
$fontStyle2 = array('name' => 'TH SarabunIT๙', 'size' => 14, 'bold' => false);
$fontStyleAlign = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'align' => 'center');
$fontStyleAlign2 = array('cellMargin' => 80, 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'align' => 'left');
$phpWord->addTableStyle('Fancy Table', $styleTable);
$table = $section->addTable('Fancy Table');
$table->addRow(null, ['vMerge' => 'restart']);
$table->addCell(1500, $styleCell)->addImage('img/pea.jpg', ['width' => 60, 'height' => 60]);
$table->addCell(3000, $styleCell)->addText(htmlspecialchars("การไฟฟ้าส่วนภูมิภาค ใบขอเสนอซื้อ/จ้าง      (Purchase Requistion)     ประเภทเอกสาร      (Document Type)"), $fontStyle, $fontStyleAlign);
$table->addCell(3000, $styleCell)->addText(htmlspecialchars("หน่วยงานผู้ขอซื้อ/จ้าง                                วันที่ส่งมอบ"), $fontStyle, $fontStyleAlign);
$table->addCell(3000, $styleCell)->addText(htmlspecialchars("รหัสกลุ่มจัดของผู้ซื้อ/ผู้ว่าจ้าง            คลัง/สถานที่รับบริการ(รง.)"), $fontStyle, $fontStyleAlign);
$table->addCell(3000, $styleCell)->addText(htmlspecialchars("เลขที่ใบขอเสนอซื้อ/จ้าง                                        เลขที่ติดตาม (Tracking No.)"), $fontStyle, $fontStyleAlign);
$table->addRow();
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(4000, $styleCell)->addText(htmlspecialchars("หมวดรายการ (Item Category : I)"), $fontStyle, $fontStyleAlign);
$table->addCell(8000, $cellColSpan)->addText(htmlspecialchars("หมวดการกำหนดบัญชี (Account Assignment Category : A)"), $fontStyle, $fontStyleAlign);
$table->addRow(null, ['cantSplit' => true]);
$cell1 = $table->addCell(4500, $cellColSpan);
$textrun1 = $cell1->addTextRun();
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("PR มาตรฐาน(ZNB1)"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("PR จากการปฏิบัติงาน (ZNB3)"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("PR จาก WRP(ZNB2)"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("PR สัญญาล่วงหน้า(ZRV1)"), $fontStyleName3, $fontStyleAlign);
$cell1 = $table->addCell(3000, ['borderSize' => 6]);
$textrun1 = $cell1->addTextRun();
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("มาตรฐาน ( )                                                          "), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("การรับช่วง(L)"), $fontStyleName3, $fontStyleAlign);
$cell1 = $table->addCell(5500, $cellColSpan);
$textrun1 = $cell1->addTextRun();
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("พัสดุสำรองคลัง ( )    "), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("คชจ.เข้าหน้างาน ( )"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("ทรัพย์สินถาวรพร้อมใช้(Z)"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("งานจ้างเหมาเบ็ดเสร็จ(P)"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("พัสดุโครงการที่มีแผน ( )"), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("คชจ.เข้าใบสั่งซ่อม/งานบริการ(F)                        "), $fontStyleName3, $fontStyleAlign);
$textrun1->addImage('img/check.png', ['width' => 25, 'height' => 15]);
$textrun1->addText(htmlspecialchars("งานจ้างเหมาบางส่วน(N)"), $fontStyleName3, $fontStyleAlign);
$date = thai_date_fullmonth(strtotime($row3["Estimate_Date"]));

$section->addText(" บันทึกส่วนหัว (Header Note) : " . $row3["Address"] . " อนุมัติที่ " . $row3["Estimate"] . " ลว. " . $date, $fontStyleName2, $fontStyleAlign2);
$section->addText(" WBS.  Vender List : " . $ID_vdlist, $fontStyleName2, $fontStyleAlign2);
$table = $section->addTable('Fancy Table');
$table->addRow(0, ['vMerge' => 'restart']);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("ลำดับ"), $fontStyle, $fontStyleAlign);
$table->addCell(2000, $styleCell)->addText(htmlspecialchars("แผนก"), $fontStyle, $fontStyleAlign);
$table->addCell(5500, $styleCell)->addText(htmlspecialchars("รหัสพัสดุ/ข้อความ"), $fontStyle, $fontStyleAlign);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("ปริมาณ"), $fontStyle, $fontStyleAlign);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("หน่วย"), $fontStyle, $fontStyleAlign);
$table->addCell(1000, $cellColSpan3)->addText(htmlspecialchars("วงเงินงบประมาณ"), $fontStyle, $fontStyleAlign);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("กลุ่มวัสดุ"), $fontStyle, $fontStyleAlign);
$table->addCell(1500, $styleCell)->addText(htmlspecialchars("รหัสบัญชี GL"), $fontStyle, $fontStyleAlign);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("เงินทุน"), $fontStyle, $fontStyleAlign);
$table->addCell(1000, $cellColSpan)->addText(htmlspecialchars("หมวดการกำหนดบัญชี"), $fontStyle, $fontStyleAlign);


$table->addRow(0);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("ต่อหน่วย"), $fontStyle, $fontStyleAlign);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("ราคารวม"), $fontStyle, $fontStyleAlign);
$table->addCell(500, $styleCell)->addText(htmlspecialchars("สกุลเงิน"), $fontStyle, $fontStyleAlign);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(2500, $styleCell)->addText(htmlspecialchars("ศูนย์ต้นทุน/องค์ประกอบ WBS"), $fontStyleName4, $fontStyleAlign);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars("งานจ้างเหมาบางส่วน"), $fontStyle, $fontStyleAlign);
$table->addRow(0);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars("รายการ(ข้อความแบบสั้น)"), $fontStyle, $fontStyleAlign);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(1000, $cellColSpan3)->addText(htmlspecialchars("ข้อความรายการ"), $fontStyle, $fontStyleAlign);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(null, $cellRowContinue);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars("แหล่งเงินกู้"), $fontStyle, $fontStyleAlign);
$table->addCell(1000, $styleCell)->addText(htmlspecialchars("เลขที่สัญญากู้"), $fontStyle, $fontStyleAlign);

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
  }

  // if ($status == 2) {
  //   $newprice = $row["newprice"] + ($row["newprice"] * 0.07);
  // } else {
  $newprice = $row["newprice"];
  // }
  $pricenew = number_format($newprice, 2);
  $price_qty = number_format($newprice * $row["qty"], 2);
  $table->addRow(0);
  $table->addCell(null, $styleCell4)->addText(htmlspecialchars($i), $fontStyle3, $fontStyleAlign);
  $table->addCell(null, $styleCell2)->addText(htmlspecialchars($row["job"]), $fontStyle2, $fontStyleAlign);
  $table->addCell(null, $styleCell3)->addText(htmlspecialchars($dataname), $fontStyle2, $fontStyleAlign2);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($row["qty"]), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($dataunit), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($pricenew), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($price_qty), $fontStyle2, $fontStyleAlign);
  $table->addCell(null, $styleCell2)->addText(htmlspecialchars("THB"), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($row3["material"]), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($row3["GL"]), $fontStyle2, $fontStyleAlign);
  $table->addCell(null, $styleCell2)->addText(htmlspecialchars("THB"), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell2)->addText(htmlspecialchars($row["network"]), $fontStyle2, $fontStyleAlign);
  $table->addCell(500, $styleCell5)->addText(htmlspecialchars(""), $fontStyle2, $fontStyleAlign);

  $i = 1 + $i;
  $ii++;
  $pricenew = "";
  $price_qty = "";
  $price = $price + ($newprice * $row["qty"]);


}

$priceNNN = number_format($price, 2);
$table->addRow(0);
$table->addCell(null, $styleCell4);
$table->addCell(500, $styleCell2)->addText(htmlspecialchars("รวมรายการ"), $fontStyle2, $fontStyleAlign);
$table->addCell(500, $styleCell2)->addText(htmlspecialchars($ii), $fontStyle2, $fontStyleAlign);
$table->addCell(500, $styleCell2)->addText(htmlspecialchars("รายการ"), $fontStyle2, $fontStyleAlign);
$table->addCell(null, $styleCell2);
$table->addCell(null, $styleCell2);
$table->addCell(1000, $styleCell2)->addText(htmlspecialchars($priceNNN), $fontStyle2, $fontStyleAlign);
$table->addCell(500, $styleCell2)->addText(htmlspecialchars("บาท"), $fontStyle2, $fontStyleAlign);
$table->addCell(null, $styleCell2);
$table->addCell(null, $styleCell2);
$table->addCell(null, $styleCell2);
$table->addCell(null, $styleCell2);
$table->addCell(null, $styleCell5);

$section->addTextBreak(4);
$section->addText("                             ผู้เสนอซื้อ/จ้าง..........................................                                      ผู้อนุมัติ..........................................                                      ผู้บันทึก..........................................", $fontStyleName2, $fontStyleAlign);
$section->addText("                                    ตำแหน่ง..........................................                                    ตำแหน่ง..........................................                                     ตำแหน่ง..........................................", $fontStyleName2, $fontStyleAlign);
$section->addText("                                          วันที่..........................................                                        วันที่..........................................                                          วันที่..........................................", $fontStyleName2, $fontStyleAlign);



// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('ใบขอเสนอซื้อจ้าง19.docx');

echo "<script type='text/javascript'>window.location.href = 'ใบขอเสนอซื้อจ้าง19.docx';</script>";
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
$price = 0;
$vat = 0;
$vat11 = 0;
$price_abd_vat = 0;
$all_price_Only = 0;
$all_vat = 0;
$All_price_abd_vat = 0;

$Under = '';
$Nopaperdate = '';

$priceAll = 0;
$User = $_SESSION["User"];
$id = $_GET["create"];

$sql3 = "SELECT Employee, Vender_List, Nopaperdate, Nopaper, year, Address FROM data285 WHERE  id = $id AND ( user = '$User' )";
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
$section->addText('เรื่อง  รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง', $fontStyleName1, $cellHCentered2);
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
while ($row6 = $result6->fetch_assoc()) {
  if ($status == 1) {
    $monney = ($row6["newprice"] * $row6["qty"]) + $monney;
  }
  if ($status == 2) {
    $monney = ($row6["newprice"] * $row6["qty"]) + $monney + ($row6["newprice"] * $row6["qty"] * 0.07);
  }
}

$section->addText(htmlspecialchars("\tตามที่ได้ดำเนินการจัดจ้างเหมาเอกชน (เฉพาะค่าแรงงาน) ช่วยก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้างบผู้ใช้ไฟ ปี" . $row3["year"] . " บริเวณ " . $row3["Address"] . " โดยวิธีเฉพาะเจาะจง ตามอนุมัติที่ " . $text3 . " ขอรายงานผลการพิจารณาขอจ้าง ดังนี้"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t1. สืบราคาด้วยวาจา จากผู้รับจ้าง จำนวน 1 ราย"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t   1.1 " . $row2["fname"] . " " . $row2["lname"] . " เสนอราคาตามเอกสารแนบ ดังนี้ "), $fontStyleName1, $cellHCentered2);
$text1 = number_format($monney, 2);
$text2 = thai_date_fullmonth(strtotime($row2["smedate"]));
$section->addText(htmlspecialchars("\t  จำนวนเงิน " . $text1 . " บาท (ราคารวมภาษีมูลค่าเพิ่ม)"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t2. ผู้เสนอราคา  " . $row2["fname"] . " " . $row2["lname"] . " เนื่องจาก มีผู้เสนอราคาเพียงรายเดียว และได้ลงทะเบียนเป็นผู้ประกอบการวิสหกิจขนาดกลางและขนาดย่อม (SME) ตามหนังสือรับรองการขึ้นทะเบียนสมาชิก SME เลขที่ " . $row2["sme"] . " ลว. " . $text2 . " ซึ่งมีคุณสมบัติถูกต้องตามหลักเกณฑ์ที่ กฟภ.ระบุ ดังนี้"), $fontStyleName1, $cellHCentered2);


$sql1234 = "SELECT * FROM new285data WHERE    user = $User  AND ( userid = $id  ) GROUP BY network";
$result1234 = $conn->query($sql1234);
$x = 0;
while ($row5 = $result1234->fetch_assoc()) {
  $x++;
  $section->addText(htmlspecialchars("\t   2." . $x . " หมายเลขงาน " . $row5["wbs"] . " โครงข่าย " . $row5["network"] . "  " . $row5["job"]), $fontStyleName1, $cellHCentered2);

  $styleTable = array('cellMargin' => 50, 'topFromText' => 0, 'bottomFromText' => 0, 'borderSize' => 6, 'borderColor' => '000000');
  $cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
  $cellRowContinue = array('vMerge' => 'continue');
  $cellColSpan = array('gridSpan' => 3, 'valign' => 'center');
  $cellColSpan1 = array('gridSpan' => 5, 'valign' => 'center');
  $fontStyleAlign = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'align' => 'right');
  $cellHCentered = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'align' => 'center');
  $cellVCentered = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'valign' => 'center');
  $fontStyle = array('bold' => true, 'spaceBefore' => 0, 'spaceAfter' => 0);
  $phpWord->addTableStyle('Colspan Rowspan', $styleTable);
  $table = $section->addTable('Colspan Rowspan');
  $table->addRow();
  $cell1 = $table->addCell(1000, $cellRowSpan);
  $textrun1 = $cell1->addTextRun($cellHCentered);
  $textrun1->addText(htmlspecialchars('ที่'), $fontStyle, $cellHCentered2);

  $cell2 = $table->addCell(1000, $cellRowSpan);
  $textrun2 = $cell2->addTextRun($cellHCentered);
  $textrun2->addText(htmlspecialchars('ประเภทงาน'), $fontStyle, $cellHCentered2);

  $cell3 = $table->addCell(4700, $cellRowSpan);
  $textrun3 = $cell3->addTextRun($cellHCentered);
  $textrun3->addText(htmlspecialchars('รายละเอียด'), $fontStyle, $cellHCentered2);

  $cell4 = $table->addCell(1000, $cellRowSpan);
  $textrun4 = $cell4->addTextRun($cellHCentered);
  $textrun4->addText(htmlspecialchars('จำนวน'), $fontStyle, $cellHCentered2);

  $cell4 = $table->addCell(500, $cellRowSpan);
  $textrun4 = $cell4->addTextRun($cellHCentered);
  $textrun4->addText(htmlspecialchars('ราคา ต่อหน่วย ไม่รวมภาษี'), $fontStyle, $cellHCentered2);

  $cell5 = $table->addCell(1100, $cellColSpan);
  $textrun5 = $cell5->addTextRun($cellHCentered);
  $textrun5->addText(htmlspecialchars('ราคารวมตกลง (บาท)'), $fontStyle, $cellHCentered2);

  $table->addRow();
  $table->addCell(null, $cellRowContinue);
  $table->addCell(null, $cellRowContinue);
  $table->addCell(null, $cellRowContinue);
  $table->addCell(null, $cellRowContinue);
  $table->addCell(null, $cellRowContinue);
  $table->addCell(500, $cellVCentered)->addText(htmlspecialchars('ราคา ไม่รวมภาษี'), $fontStyle, $cellHCentered);
  $table->addCell(100, $cellVCentered)->addText(htmlspecialchars('ภาษี 7%'), $fontStyle, $cellHCentered);
  $table->addCell(500, $cellVCentered)->addText(htmlspecialchars('ราคา รวมทั้งสิ้น'), $fontStyle, $cellHCentered);



  $sql = "SELECT * FROM new285data WHERE network = {$row5["network"]} AND ( user = $User )  AND ( userid = $id  )";
  $result = $conn->query($sql);
  // output data of each row
  $i = 1;
  $APAVSt = 0;
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
      $table->addCell()->addText(htmlspecialchars("{$i}"), null, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars("{$row['type']}"), null, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars("{$dataname}"), null, $cellHCentered2);
      $table->addCell()->addText(htmlspecialchars("{$row['qty']}  {$dataunit}"), null, $cellHCentered);
      $PriceSt = "";
      $VatSt = "";
      $PAVSt = "";
      if ($status == 1) {
        $VatSt = 0;
        $PAVSt = number_format(($row["newprice"] * $row['qty']) + $row["newprice"] * $row['qty'], 2);
        $vat = 0;
      }
      if ($status == 2) {
        $VatSt = number_format($row["newprice"] * $row['qty'] * 0.07, 2);
        $PAVSt = number_format(($row["newprice"] * $row['qty'] * 0.07) + $row["newprice"] * $row['qty'], 2);
        $vat = $vat + ($row["newprice"] * $row["qty"] * 0.07);
      }
      $table->addCell()->addText(htmlspecialchars(number_format($row["newprice"], 2)), null, $fontStyleAlign);
      $table->addCell()->addText(htmlspecialchars(number_format($row["newprice"] * $row['qty'], 2)), null, $fontStyleAlign);
      $table->addCell()->addText(htmlspecialchars($VatSt), null, $fontStyleAlign);
      $table->addCell()->addText(htmlspecialchars(number_format($row["newprice"] * $row['qty'] + $VatSt, 2)), null, $fontStyleAlign);

      $i = $i + 1;
      $price = round($price + ($row["newprice"] * $row["qty"]), 2);
      $vat11 = round($vat11 + $vat, 2);
      $price_abd_vat = round($price_abd_vat + ($row["newprice"] * $row["qty"]) + $vat, 2);
      $priceAll = round($priceAll + ($row["price"] * $row["qty"]), 2);
      $all_vat = round($all_vat + $vat, 2);
      $APAVSt = round($APAVSt + $vat, 2);
      $vat = 0;
    }
  }
  $all_price_Only = round($all_price_Only + $price, 2);
  $All_price_abd_vat = round($All_price_abd_vat + $price_abd_vat, 2);
  $APriceNVSt = number_format($price, 2);
  $APAVSt1 = number_format($APAVSt, 2);
  $APriceSt = number_format($price_abd_vat, 2);
  $price = 0;
  $vat = 0;
  $price_abd_vat = 0;

  if ($status == 1) {
    $APAVSt1 = " - ";
    $all_vat = 0;
  }



  $table->addRow();
  $cell6 = $table->addCell(2500, $cellColSpan1);
  $textrun6 = $cell6->addTextRun($cellHCentered);
  $textrun6->addText(htmlspecialchars('ราคารวม (บาท)'), $fontStyle);
  $table->addCell(1000, $cellVCentered)->addText(htmlspecialchars("{$APriceNVSt}"), null, $fontStyleAlign);
  $table->addCell(500, $cellVCentered)->addText(htmlspecialchars("{$APAVSt1}"), null, $fontStyleAlign);
  $table->addCell(1000, $cellVCentered)->addText(htmlspecialchars("{$APriceSt}"), null, $fontStyleAlign);

  $APriceNVSt = "";
  $APAVSt = "";
  $APriceSt = "";
  $price = 0;
  $vat = 0;
  $price_abd_vat = 0;
  $price = 0;

}

$ALLPriceNVSt = number_format($all_price_Only, 2);
$ALLPAVSt = number_format($all_vat, 2);
$ALLPriceSt = number_format($All_price_abd_vat, 2);
$priceAll1 = $priceAll - $All_price_abd_vat;
$priceAll1 = number_format($priceAll1, 2);
$priceAll2 = number_format($priceAll, 2);

$table->addRow();
$cell6 = $table->addCell(2500, $cellColSpan1);
$textrun6 = $cell6->addTextRun($cellHCentered);
$textrun6->addText(htmlspecialchars('ราคารวมทั้งสิ้น (บาท)'), $fontStyle);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars("{$ALLPriceNVSt}"), null, $fontStyleAlign);
$table->addCell(500, $cellVCentered)->addText(htmlspecialchars("{$ALLPAVSt}"), null, $fontStyleAlign);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars("{$ALLPriceSt}"), null, $fontStyleAlign);

$section->addTextBreak(1);


$section->addText(htmlspecialchars("\tพิจารณาแล้ว เห็นควรจัดจ้าง จากผู้เสนอราคาดังกล่าว เป็นจำนวนเงิน " . $ALLPriceNVSt . " บาท ภาษีมูลค่าเพิ่ม " . $ALLPAVSt . " บาท ราคารวมภาษีมูลค่าเพิ่ม " . $ALLPriceSt . " บาท "), $fontStyleName1, $cellHCentered2);

$section->addText(htmlspecialchars("\t\tจึงเรียนมาเพื่อโปรดพิจารณา หากเห็นชอบขอได้โปรดอนุมัติจัดจ้าง จากผู้เสนอราคาดังกล่าว"), $fontStyleName1, $cellHCentered2);

$section->addTextBreak(1);
$section->addText(htmlspecialchars("\t\t\tลงชื่อ     __________________________ เจ้าหน้าที่"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\t                     ( " . $row1["Fname"] . " " . $row1["Lname"] . " )"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("\t\t\tตำแหน่ง            " . $row1["Rank"] . " ผ" . $row1["Under"] . "." . $row1["pea"]), $fontStyleName1, $cellHCentered2);


$styleTable = array('borderSize' => 6, 'cellMargin' => 80);
$styleFirstRow = array('borderBottomSize' => 18);
$styleCell = array('valign' => 'center');
$styleCellBTLR = array('valign' => 'center', 'textDirection' => \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR);
$fontStyle = array('bold' => true);
$fontStyleAlign = array('align' => 'center');
$phpWord->addTableStyle('Fancy Table', $styleTable);
$table = $section->addTable('Fancy Table');

$section->addTextBreak(1);
$table1 = $section->addTable('Fancy Table');
$table1->addRow(3000);
$table1->addCell(4000, $styleCell)->addText(htmlspecialchars("\tเห็นชอบและอนุมัติตามเสนอ      \t\t\t\t\t\t           ลงชื่อ ________________________            ( ___________________________ )       ตำแหน่ง ______________________       วันที่ ________________________"), null, $fontStyleAlign, $cellHCentered2);

// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง1.docx');

echo "<script type='text/javascript'>window.location.href = 'รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง1.docx';</script>";
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
$User = $_SESSION["User"];
$id = $_SESSION["ID"];
$sql3 = "SELECT Address,Name,Estimate,Estimate_Date FROM data285 WHERE  id = $id AND ( user = '$User' )";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();
$sql4 = "SELECT wbs FROM new285data WHERE  userid = $id AND ( user = '$User' )";
$result4 = $conn->query($sql4);
$row4 = $result4->fetch_assoc();
require_once 'bootstrap.php';

$phpWord = new \PhpOffice\PhpWord\PhpWord();

$fontStyleName1 = 'oneUserDefinedStyle1';
$phpWord->addFontStyle(
  $fontStyleName1,
  array(
    'name' => 'TH SarabunIT๙',
    'size' => 16,
    'color' => '1B2232',
    'bold' => false,
  )
);

$fontStyleName2 = 'oneUserDefinedStyle2';
$phpWord->addFontStyle(
  $fontStyleName2,
  array(
    'name' => 'TH SarabunIT๙',
    'size' => 16,
    'color' => '1B2232',
    'bold' => true,
  )
);

$fontStyleName3 = 'oneUserDefinedStyle3';
$phpWord->addFontStyle(
  $fontStyleName3,
  array(
    'name' => 'TH SarabunIT๙',
    'size' => 14,
    'color' => '1B2232',
    'bold' => false,
  )
);

$cellHCentered3 = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'left');
$cellHCentered2 = array('cellMargin' => 80, 'spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'both');

$section = $phpWord->addSection(['marginTop' => 500, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 500]);
$section->addText(htmlspecialchars("\t\t\tแบบฟอร์มการคำนวณราคากลางงานจ้างเหมาระบบไฟฟ้า(เฉพาะค่าเเรง)"), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("การคำนวณราคากลางขยายเขตฯ " . $row3["Name"] . "  " . $row3["Address"]), $fontStyleName1, $cellHCentered2);
$section->addText(htmlspecialchars("อนุมัติประมาณการเลขที่ : " . $row3["Estimate"] . " ลว. " . thai_date_fullmonth(strtotime($row3["Estimate_Date"])) . " WBS " . $row4["wbs"]));

$styleTable = array('cellMargin' => 10, 'topFromText' => 0, 'bottomFromText' => 0, 'borderSize' => 6, 'borderColor' => '000000');
$cellRowSpan = array('vMerge' => 'restart', 'valign' => 'center', 'bgColor' => 'FFFFFF');
$cellRowContinue = array('vMerge' => 'continue');
$cellColSpan = array('gridSpan' => 3, 'valign' => 'center');
$cellColSpan2 = array('gridSpan' => 2, 'valign' => 'center');
$fontStyleAlign = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'align' => 'right');
$cellHCentered = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1, 'align' => 'center');
$cellVCentered = array('spaceBefore' => 0, 'spaceAfter' => 0, 'lineHeight' => 1.0, 'valign' => 'center');
$fontStyle = array('bold' => true, 'spaceBefore' => 0, 'spaceAfter' => 0, 'marginBottom' => 0);
$phpWord->addTableStyle('Colspan Rowspan', $styleTable);
$table = $section->addTable('Colspan Rowspan');

$table->addRow();
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars('ลำดับ'), $fontStyle, $cellHCentered);
$table->addCell(5000, $cellVCentered)->addText(htmlspecialchars('รายการ'), $fontStyle, $cellHCentered);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars('จำนวน'), $fontStyle, $cellHCentered);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars('หน่วย'), $fontStyle, $cellHCentered);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars('ราคาต่อหน่วย ไม่รวมภาษี'), $fontStyle, $cellHCentered);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars('ราคาตกลงจ้าง'), $fontStyle, $cellHCentered);
$table->addCell(500, $cellVCentered)->addText(htmlspecialchars('ภาษี 7%'), $fontStyle, $cellHCentered);
$table->addCell(1000, $cellVCentered)->addText(htmlspecialchars('ราคารวมทั้งสิ้น'), $fontStyle, $cellHCentered);


$sql123 = "SELECT job FROM new285data WHERE    user = $User   AND ( userid = $id  ) GROUP BY job";
$result123 = $conn->query($sql123);
$type = '';

$priceAllEnd1 = 0;
$priceAllEnd2 = 0;
$priceAllEnd3 = 0;
$i = 1;
while ($row123 = $result123->fetch_assoc()) {
  $priceAll1 = 0;
  $priceAll2 = 0;
  $priceAll3 = 0;
  $job = $row123["job"];

  $sql12 = "SELECT * FROM new285data WHERE  user = $User AND (job = '$job') AND ( userid = $id  ) ";

  $table->addRow();
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText(htmlspecialchars($job), $fontStyleName2, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);

  $result5 = $conn->query($sql12);
  while ($row = $result5->fetch_assoc()) {
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
    if ($type != $row["type"]) {
      $type = $row["type"];

      $table->addRow();
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars($type), $fontStyleName2, $cellHCentered);
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
      $table->addCell()->addText('', $fontStyleName3, $cellHCentered);

    }

    if ($row["price"] != 0) {
      $table->addRow();
      $table->addCell()->addText(htmlspecialchars($i), $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars($dataname), $fontStyleName3, $cellHCentered3);
      $table->addCell()->addText(htmlspecialchars($row["qty"]), $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars($dataunit), $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars(number_format($row["price"], 2)), $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars(number_format($row["price"] * $row["qty"], 2)), $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars(number_format($row["price"] * $row["qty"] * 0.07, 2)), $fontStyleName3, $cellHCentered);
      $table->addCell()->addText(htmlspecialchars(number_format(($row["price"] * $row["qty"] * 0.07) + ($row["price"] * $row["qty"]), 2)), $fontStyleName3, $cellHCentered);
      $priceAll1 = $row["price"] + $priceAll1;
      $priceAll2 = ($row["price"] * $row["qty"]) + $priceAll2;
      $priceAll3 = ($row["price"] * $row["qty"] * 0.07) + $priceAll3;
      $i++;
    }
  }
  $priceAllEnd1 = $priceAllEnd1 + $priceAll1;
  $priceAllEnd2 = $priceAllEnd2 + $priceAll2;
  $priceAllEnd3 = $priceAllEnd3 + $priceAll3;
  $table->addRow();
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText(htmlspecialchars("รวมเป็นเงิน"), $fontStyleName2, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText('', $fontStyleName3, $cellHCentered);
  $table->addCell()->addText(htmlspecialchars(number_format($priceAll1, 2)), $fontStyleName2, $cellHCentered);
  $table->addCell()->addText(htmlspecialchars(number_format($priceAll2, 2)), $fontStyleName2, $cellHCentered);
  $table->addCell()->addText(htmlspecialchars(number_format($priceAll3, 2)), $fontStyleName2, $cellHCentered);
  $table->addCell()->addText(htmlspecialchars(number_format($priceAll3 + $priceAll2, 2)), $fontStyleName2, $cellHCentered);
}

$table->addRow();
$table->addCell()->addText('', $fontStyleName3, $cellHCentered);
$table->addCell()->addText(htmlspecialchars("รวมทั้งหมดเป็นเงิน"), $fontStyleName2, $cellHCentered);
$table->addCell()->addText('', $fontStyleName3, $cellHCentered);
$table->addCell()->addText('', $fontStyleName3, $cellHCentered);
$table->addCell()->addText(htmlspecialchars(number_format($priceAllEnd1, 2)), $fontStyleName2, $cellHCentered);
$table->addCell()->addText(htmlspecialchars(number_format($priceAllEnd2, 2)), $fontStyleName2, $cellHCentered);
$table->addCell()->addText(htmlspecialchars(number_format($priceAllEnd3, 2)), $fontStyleName2, $cellHCentered);
$table->addCell()->addText(htmlspecialchars(number_format($priceAllEnd3 + $priceAllEnd2, 2)), $fontStyleName2, $cellHCentered);
// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('เอกสารแนบ_.docx');

echo "<script type='text/javascript'>window.location.href = 'เอกสารแนบ_.docx';</script>";
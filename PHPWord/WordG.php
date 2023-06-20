<?php
session_start();

$dayTH = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
$monthTH = [null, 'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน', 'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'];
$monthTH_brev = [null, 'ม.ค.', 'ก.พ.', 'มี.ค.', 'เม.ย.', 'พ.ค.', 'มิ.ย.', 'ก.ค.', 'ส.ค.', 'ก.ย.', 'ต.ค.', 'พ.ย.', 'ธ.ค.'];


function thai_date_fullmonth($time)
{ 
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
$price_abd_vat = 0;
$all_price = 0;
$all_vat = 0;
$All_price_abd_vat = 0;
$WBS;
$count = 0;
$Under = '';
$Nopaperdate = '';

$User = $_SESSION["User"];
$User = 500306;
$id = 1;

$sqlc = "SELECT * FROM contract WHERE  id = $id AND ( user = '$User' )";
$resultc = $conn->query($sqlc);
$rowc = $resultc->fetch_assoc();

$sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$User' )";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();

$ID_employee = $row3["Employee"];
$ID_vdlist = $row3["Vender_List"];

$sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
$result2 = $conn->query($sql2);
$row2 = $result2->fetch_assoc();

$status = $row2['status'];



$sql4 = "SELECT * FROM wbs WHERE id = $id AND ( User = '$User' )";
$result4 = $conn->query($sql4);

while ($row4 = $result4->fetch_assoc()) {
  $WBS[$count] = $row4["WBS"];
  $count++;
}


$sql6 = "SELECT * FROM end_data WHERE ID_User = $id AND ( User = '$User' )";
$result6 = $conn->query($sql6);

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

$phpWord->addParagraphStyle(
  'multipleTab',
  array(
    'tabs' => array(
      new \PhpOffice\PhpWord\Style\Tab('left', 600),
      new \PhpOffice\PhpWord\Style\Tab('center', 3200),
      new \PhpOffice\PhpWord\Style\Tab('right', 5300),
    )
  )
);

$section = $phpWord->addSection(['marginTop' => 500, 'marginLeft' => 500, 'marginRight' => 500, 'marginBottom' => 500]);
$section->addImage('img/pea.jpg', ['width' => 100, 'height' => 100]);
$table = $section->addTable();
$table->addRow();
$table->addCell(7000)->addText("ที่ มท 5308.18/".$row1["pea"], $fontStyleName1);
$table->addCell(4000)->addText("สำนักงานการไฟฟ้าส่วนภูมิภาคจังหวัดอุตรดิตถ์ เลขที่ 174 หมู่ที่ 1 ถนนบรมอาสน์  ตำบลท่าเสา อำเภอเมือง จังหวัดอุตรดิตถ์ 53000", $fontStyleName1);


if ($row3["Nopaperdate"] != '') {
  $Nopaperdate = thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}

$section->addText('เรื่อง  ขออนุมัติวางเงินประกันจ้างเหมาเอกชนช่วยงานก่อสร้างระบบจำหน่ายไฟฟ้า', $fontStyleName1);
if ($row1["Under"] == "") {
  $Under = '';
} else {
  $Under = "ผ่าน หผ." . $row1["Under"] . "." . $row1["pea"];
}
$section->addText('เรียน  ผจก.' . $row1["pea"] . ' ' . $Under, $fontStyleName1);
if ($row1["Under"] != "") {
  $Under = 'ผ' . $row1["Under"] . '.' . $row1["pea"];
}

if ($row3["Nopaper"] == '') {
  echo $row1["county"]; ?>&nbsp;<?php echo $row1["pea"];
    $text3 = "(    ) ลว.                            ";
} else {
  $text3 = $row3["Nopaper"] . " ลว. " . thai_date_fullmonth(strtotime($row3["Nopaperdate"]));
}
$price1 = "";
$pricetext = "";
$ContractMoney = "";
$price1 = number_format($price, 2);
$pricetext = Convert($price);
$ContractMoney = number_format($rowc["ContractMoney"]);

$section->addText(htmlspecialchars("\tตามรายงานขอจ้างเหมาเอกชนช่วยงานก่อสร้างฯ ตามอนุมัติที่ ".$row1["county"]." ".$row1["pea"]." (   )               ลว.             "), $fontStyleName1, 'multipleTab');
$section->addText(htmlspecialchars("ตามที่ แผนกก่อสร้างได้จ้างเหมา ".$row2["fname"]." ".$row2["lname"]." Vender List ".$ID_vdlist." เลขประจำตัวผู้เสียภาษี ".$row2["idtax"]." ช่วยงานก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า บริเวณ ".$row3["Address"]." เป็นจำนวนเงิน ".$price1." บาท ( ".$pricetext." ) รับประกันงาน ".$row3["avouch"]." วัน นับตั้งแต่วันที่ ส่งมอบงานจ้างถูกต้องเรียบร้อยแล้ว เพื่อเป็นหลักประกันการปฏิบัติงานตามเงื่อนไข ของใบสั่งจ้าง ผ".$row1["Under"].".".$row1["pea"]." จึงขอแจ้งให้ ".$row2["fname"]." ".$row2["lname"]."  วางเงินประกันสัญญาจ้างในอัตราร้อยละ 5 ของวงเงินการจ้างเหมาทั้งสิ้น คิดเป็นเงินประกัน ".$ContractMoney." บาท "), $fontStyleName1, 'multipleTab');
$section->addText(htmlspecialchars("\t\tจึงเรียนมาเพื่อโปรดพิจารณาอนุมัติ"), $fontStyleName1, 'multipleTab');
$section->addTextBreak(2);
$section->addText(htmlspecialchars("\t\t\t                  ".$row1["Fname"]." ".$row1["Lname"]), $fontStyleName1, 'multipleTab');
$section->addText(htmlspecialchars("\t\t\t                ( ".$row1["Rank"]." ผ".$row1["Under"].".".$row1["pea"]." )"), $fontStyleName1, 'multipleTab');


// Saving the document as OOXML file...
$objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
$objWriter->save('ขออนุมัติวางเงินประกันจ้างเหมาเอกชนช่วยงานก่อสร้างระบบจำหน่ายไฟฟ้า.docx');
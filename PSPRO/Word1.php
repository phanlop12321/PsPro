<?php
session_start();

if (!$_SESSION["UserID"]) {  //check session

  Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
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

$price = 0;
  $vat = 0;
  $price_abd_vat = 0;
  $all_price = 0;
  $all_vat = 0;
  $All_price_abd_vat = 0;
  $WBS;
  $count = 0;



  $User = $_SESSION["User"];
  //$id = $_SESSION["ID"];
  $id = $_GET["create"];
  $id = 1;

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

	require_once 'PHPWord.php';
	// New Word Document
	$PHPWord = new PHPWord();

	// New portrait section
	$section = $PHPWord->createSection();

	// Add text elements
	$section->addImage('img/pea.jpg', array('width'=>150, 'height'=>150, 'align'=>'left'));
	$section->addText("จาก  ".$row1["Rank"]." ".$row1["Under"].".".$row1["pea"]);
	$section->addText("จาก  ".$row1["Rank"]." ".$row1["Under"].".".$row1["pea"],array('marginTop'=>200, 'marginLeft'=>55));
	$section->addTextBreak(2);

	$section->addText("สวัสดีครับ! ชาวไทยครีเอท 777", array('name'=>'TH SarabunIT๙', 'size'=>'24'));
	$section->addTextBreak(2);

	$PHPWord->addFontStyle('rStyle', array('bold'=>true, 'italic'=>true, 'size'=>16));
	$PHPWord->addParagraphStyle('pStyle', array('align'=>'center', 'spaceAfter'=>100));
	$section->addText('I am styled by two style definitions.', 'rStyle', 'pStyle');
	$section->addText('I have only a paragraph style definition.', null, 'pStyle');

	// Save File
	$objWriter = PHPWord_IOFactory::createWriter($PHPWord, 'Word2007');
	$objWriter->save('CreateWord1.docx');
?>
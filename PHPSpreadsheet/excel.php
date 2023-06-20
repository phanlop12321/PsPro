<?php
require 'vendor/autoload.php';

require 'connection.php';


session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
if (isset($_GET['create'])) {
    $_SESSION["ID"] = $_GET['create'];
}

$User = $_SESSION["User"];
$id = $_SESSION["ID"];

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

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getDefaultStyle()->getFont()->setName('TH SarabunPSK');
$spreadsheet->getDefaultStyle()->getFont()->setSize(16);
$sheet->mergeCells('A1:Q1')
    ->mergeCells('A2:Q2')
    ->mergeCells('A3:D3')
    ->mergeCells('K3:L3')
    ->mergeCells('O3:Q3')
    ->mergeCells('A5:I5')
    ->mergeCells('J5:Q5')
    ->mergeCells('B6:C7')
    ->mergeCells('D6:G6')
    ->mergeCells('H6:I6')
    ->mergeCells('J6:K6')
    ->mergeCells('L6:N6')
    ->mergeCells('L7:Q7')
    ->mergeCells('L10:O10')
    ->mergeCells('L11:O11')
    ->mergeCells('L12:O12')
    ->mergeCells('L14:O14')
    ->mergeCells('L15:N15')
    ->mergeCells('M17:Q17')
    ->mergeCells('L18:Q18')
    ->mergeCells('L19:Q19')
    ->mergeCells('L20:Q20')
    ->mergeCells('L21:N21')
    ->mergeCells('L22:N22')
    ->mergeCells('L24:N24')
    ->mergeCells('L26:N26')
    ->mergeCells('L27:O27')
    ->mergeCells('L28:O28')
    ->mergeCells('L29:O29')
    ->mergeCells('L30:O30')
    ->mergeCells('L31:O31')
    ->mergeCells('L34:O34')
    ->mergeCells('L35:N35')
    ->mergeCells('L38:O38')
    ->mergeCells('L39:N39')
    ->mergeCells('L42:N42')
    ->mergeCells('L43:N43')
    ->mergeCells('L45:Q46')
    ->mergeCells('L47:Q49')
    ->mergeCells('L50:Q51')
;


$sheet->getStyle('A')->getAlignment()->setHorizontal('center');
$sheet->getStyle('K4')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L7')->getAlignment()->setHorizontal('center');
$sheet->getStyle('A5:K7')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L15')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L20')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L35')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L39')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L42')->getAlignment()->setHorizontal('center');
$sheet->getStyle('L45:L50')->getAlignment()->setHorizontal('center');

$spreadsheet->getActiveSheet()->getStyle('J5')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ffd38df1')
    ->getActiveSheet()->getStyle('A5')->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('ff5cf350');

$sheet->getStyle('A3:Q4')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A5:Q5')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('A6:A7')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('L6:Q15')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('B6:K7')->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('L16:Q44')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
$sheet->getStyle('L45:Q52')->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$spreadsheet->getActiveSheet()->getStyle('A1:Q5')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('A6:K8')->getFont()->setBold(true);
$spreadsheet->getActiveSheet()->getStyle('L45')->getFont()->setBold(true);

$spreadsheet->getActiveSheet()->getStyle('L45')->getFont()->setBold(true)->setSize(20);

$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(50);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(15);
$spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(25);
$spreadsheet->getActiveSheet()->setCellValue('A1', 'การไฟฟ้าส่วนภูมิภาค')
    ->setCellValue('A2', 'รายละเอียดของงานเพื่อขออนุมัติจ่าย')
    ->setCellValue('A3', $row3['Name'])
    ->setCellValue('K3', 'จำนวนเงินตามสัญญา')
    ->setCellValue('M3', '480676.1')
    ->setCellValue('N3', 'ผู้รับจ้าง')
    ->setCellValue('O3', $row2['fname'] . ' ' . $row2['lname'])
    ->setCellValue('B4', 'เริ่ม วันที่')
    ->setCellValue('C4', '9/11/2565')
    ->setCellValue('F4', 'สิ้นสุดวันที่')
    ->setCellValue('G4', '11/11/2565')
    ->setCellValue('J5', 'สำหรับการไฟฟ้าส่วนภูมิภาค')
    ->setCellValue('A5', 'สำหรับผู้รับจ้าง')
    ->setCellValue('A6', 'ลำดับ')
    ->setCellValue('A7', 'ที่')
    ->setCellValue('B6', 'รายละเอียด')
    ->setCellValue('D6', 'งานตามสัญญา')
    ->setCellValue('H6', 'ส่งมอบงานครั้งนี้')
    ->setCellValue('J6', 'สรุปคณะกรรมการตรวจรับ')
    ->setCellValue('D7', 'จำนวน')
    ->setCellValue('E7', 'หน่วย')
    ->setCellValue('F7', 'หน่วยละ')
    ->setCellValue('G7', 'เป็นเงิน')
    ->setCellValue('H7', 'จำนวน')
    ->setCellValue('I7', 'เป็นเงิน')
    ->setCellValue('J7', 'จำนวน')
    ->setCellValue('K7', 'เป็นเงิน')
    ->setCellValue('L6', 'เรียน  คณะกรรมการตรวจรับงานจ้าง')
    ->setCellValue('L7', 'ผลงานที่ส่งมอบครั้งนี้ถูกต้องครบถ้วน เป็นไปตามรูปแบบ รายละเอียด และข้อกำหนดในสัญญาทุกประการ')
    ->setCellValue('L10', 'งานแล้วเสร็จเมื่อวันที่______________________')
    ->setCellValue('L11', '▢ งานแล้วเสร็จตามกำหนดเวลา')
    ->setCellValue('L12', '▢ งานแล้วเสร็จช้ากว่ากำหนดตามสัญญา……… วัน')
    ->setCellValue('L14', 'ลงชื่อ …….................….....……………………….')
    ->setCellValue('L15', '( ' . $row1['Fname'] . ' ' . $row1['Lname'] . '  ) ')
    ->setCellValue('P14', 'ผู้ควบคุมงาน')
    ->setCellValue('P15', 'ตำแหน่ง: ' . $row1['Rank'] . ' ' . $row1['Under'] . ' ' . $row1['pea'])
    ->setCellValue('L16', 'เรียน')
    ->setCellValue('M17', 'คณะกรรมการตรวจรับอุปกรณ์ และงานจ้างได้ทำการตรวจรับงานดังกล่าวแล้วเมื่อ วันที่')
    ->setCellValue('L18', '▢ ถูกต้องครบถ้วนตามสัญญาทุกประการทุกประการ เห็นควรรับมอบงาน และจ่ายเงินให้แก่ผู้รับจ้างดังนี้')
    ->setCellValue('L19', '▢ ผู้รับจ้างส่งมอบงานมีรายละเอียดส่วนใหญ่ถูกต้องตามสัญญา และมีรายละเอียดส่วนย่อยที่ไม่ใช่สาระสำคัญแตกต่าง')
    ->setCellValue('L20', 'จากสัญญา และไม่ก่อให้เกิดความเสียหายต่อการใช้งาน จึงเห็นควรรับมอบงาน และอนุมัติจ่ายเงินให้แก่ผู้รับจ้างดังนี้')
    ->setCellValue('L21', 'ค่าจ้าง จำนวน')
    ->setCellValue('L22', 'จ่าย')
    ->setCellValue('L24', 'บวก ภาษีมูลค่าเพิ่ม')
    ->setCellValue('L26', 'รวมทั้งสิ้น')
    ->setCellValue('L27', 'หัก   - ค่าปรับส่งงานเกินเวลา')
    ->setCellValue('L28', '     - ค่าใช้จ่ายผู้ควบคุมงาน')
    ->setCellValue('L29', '     - ค่าชดเชย / Penalty')
    ->setCellValue('L30', '     - ค่าใช้จ่ายอื่น ๆ')
    ->setCellValue('L31', 'เงินนนนนนนนนนนนนนนนนนนนนนนนนนนน')
    ->setCellValue('L34', 'ลงชื่อ …….....………..……………………      ')
    ->setCellValue('L35', ' ( ' . $row3['FName_Chairman_Check'] . ' ' . $row3['LName_Chairman_Check'] . ' )')
    ->setCellValue('L38', 'ลงชื่อ …….....………..……………………      ')
    ->setCellValue('L39', ' ( ' . $row3['FName_Director_Check1'] . ' ' . $row3['LName_Director_Check1'] . ' )')
    ->setCellValue('L41', 'ลงชื่อ …….....………..……………………      ')
    ->setCellValue('L42', ' ( ' . $row3['FName_Director_Check2'] . ' ' . $row3['LName_Director_Check2'] . ' )')
    ->setCellValue('L45', 'อนุมัติ ')
    ->setCellValue('L47', '..............................................................................................................')
    ->setCellValue('L50', '(                                              )')
    ->setCellValue('P21', 12234)
    ->setCellValue('Q21', 'บาท')
    ->setCellValue('P22', 12234)
    ->setCellValue('Q22', 'บาท')
    ->setCellValue('P24', 12234)
    ->setCellValue('Q24', 'บาท')

    ->setCellValue('P26', 12234)
    ->setCellValue('Q26', 'บาท')
    ->setCellValue('P27', ' ')
    ->setCellValue('Q27', 'บาท')
    ->setCellValue('P28', ' ')
    ->setCellValue('Q28', 'บาท')
    ->setCellValue('P29', ' ')
    ->setCellValue('Q29', 'บาท')
    ->setCellValue('P30', ' ')
    ->setCellValue('Q30', 'บาท')
    ->setCellValue('P31', 12234)
    ->setCellValue('Q31', 'บาท')

    ->setCellValue('P34', 'ประธานกรรมการ')
    ->setCellValue('P35', $row3['Rank_C_Check'])

    ->setCellValue('P38', 'กรรมการ')
    ->setCellValue('P39', $row3['Rank_D_Check1'])

    ->setCellValue('P41', 'กรรมการ')
    ->setCellValue('P42', $row3['Rank_D_Check2'])

;



$sql123 = "SELECT * FROM new285data WHERE    user = '$User'  AND ( userid = $id ) GROUP BY job";
$result123 = $conn->query($sql123);
$type = '';
$i = 8;
$price = 0;
//$num = 1;
while ($row123 = $result123->fetch_assoc()) {
    $job = $row123["job"];
    $num = 1;
    $B = 'B' . $i;
    $C = 'C' . $i;
    $B_C = $B . ':' . $C;
    $A = 'A' . $i . ':K' . $i;
    $sheet->mergeCells($B_C);
    $sheet->getStyle($A)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
    $spreadsheet->getActiveSheet()->getStyle($B)->getFont()->setBold(true);
    $spreadsheet->getActiveSheet()->setCellValue($B, $job);
    $i++;

    $sql1234 = "SELECT * FROM new285data WHERE    user = '$User'AND ( job = '$job' )  AND ( userid = $id ) GROUP BY type";
    $result1234 = $conn->query($sql1234);
    while ($row1234 = $result1234->fetch_assoc()) {
        $type = $row1234["type"];

        $sql12 = "SELECT * FROM new285data WHERE  user = '$User'AND ( userid = $id )AND( job = '$job' )AND type = '$type' ";
        $result12 = $conn->query($sql12);

        while ($row12 = $result12->fetch_assoc()) {
            $data = $row12["id"];
            $sqldata = "SELECT * FROM data WHERE ID = $data";
            $resultdata = $conn->query($sqldata);
            $rowdata = $resultdata->fetch_assoc();

            if (isset($rowdata["NAME"])) {
                $dataname = $rowdata["NAME"];
                $dataunit = $rowdata["UNIT"];
            } else {
                $dataname = $row12["name"];
                $dataunit = $row12["unit"];
            }

            if ($row12["price"] != 0) {
                $sheet->mergeCells('B' . $i . ':C' . $i);
                $sheet->getStyle('A' . $i . ':K' . $i)->getBorders()->getVertical()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A' . $i . ':K' . $i)->getBorders()->getRight()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A' . $i . ':K' . $i)->getBorders()->getLeft()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                $sheet->getStyle('A' . $i . ':K' . $i)->getBorders()->getBottom()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_DOTTED);
                $spreadsheet->getActiveSheet()->setCellValue('B' . $i, $dataname);
                $spreadsheet->getActiveSheet()->setCellValue('A' . $i, $num);
                $spreadsheet->getActiveSheet()->setCellValue('D' . $i, $row12["qty"]);
                $spreadsheet->getActiveSheet()->setCellValue('E' . $i, $dataunit);
                $spreadsheet->getActiveSheet()->setCellValue('F' . $i, $row12["newprice"]);
                $spreadsheet->getActiveSheet()->setCellValue('G' . $i, $row12["newprice"] * $row12["qty"]);
                $spreadsheet->getActiveSheet()->setCellValue('H' . $i, $row12["qty"]);
                $spreadsheet->getActiveSheet()->setCellValue('I' . $i, $row12["newprice"] * $row12["qty"]);
                $spreadsheet->getActiveSheet()->setCellValue('J' . $i, $row12["qty"]);
                $spreadsheet->getActiveSheet()->setCellValue('K' . $i, $row12["newprice"] * $row12["qty"]);
                $i++;
                $num++;
                $price = ($row12["price"] * $row12["qty"]) + $price;
            }

        }
    }
}
$sheet->mergeCells('A' . $i . ':C' . $i);
$spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'เจ้าหน้าที่รับมอบอำนาจจากผู้รับจ้าง');
$spreadsheet->getActiveSheet()->setCellValue('J' . $i, 'รวม');
$spreadsheet->getActiveSheet()->setCellValue('K' . $i, $price);
$sheet->getStyle('J' . $i . ':K' . $i)->getBorders()->getAllBorders()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$sheet->getStyle('A' . $i . ':K' . $i + 4)->getBorders()->getOutline()->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

$i = $i + 2;
$sheet->mergeCells('A' . $i . ':C' . $i);
$spreadsheet->getActiveSheet()->setCellValue('A' . $i, '(                      )');
$i++;
$sheet->mergeCells('A' . $i . ':C' . $i);
$spreadsheet->getActiveSheet()->setCellValue('A' . $i, 'วันที่ …………../……………../……………..');


$writer = new Xlsx($spreadsheet);

// save file to server and create link
$writer->save('excel/จค01.xlsx');
//echo '<a href="excel/itoffside.xlsx">Download Excel</a>';
echo "<script type='text/javascript'>window.location.href = 'excel/จค01.xlsx';</script>";
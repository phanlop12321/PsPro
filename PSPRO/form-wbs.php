<?php
session_start();
if (!$_SESSION["UserID"]) {
    Header("Location: auth-login.html");
}
if ($_SESSION["Depratment"] == 02) {
    Header("Location: form-wbs2.php");
}
include('connection.php');
if (isset($_GET['create'])) {
    $_SESSION["ID"] = $_GET['create'];
}
$user = $_SESSION["User"];
$id = $_SESSION["ID"];
$sql3 = "SELECT Address, Employee, Rank_C_C, Rank_C_Check, Vender_List, po FROM data285 WHERE  id = $id AND ( user = '$user' )";
$result3 = $conn->query($sql3);
$row3 = $result3->fetch_assoc();

$sqlcon = "SELECT ContractNo FROM contract WHERE Id = $id AND ( User = '$user' )";
$resultcon = $conn->query($sqlcon);
$rowcon = $resultcon->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Power System Procurement and Construction Program</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/bootstrap.css">
    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
    <link rel="stylesheet" href="assets/css/text.css">
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">

</head>

<body>
    <div id="app">
        <div id="sidebar" class="active">
            <div class="sidebar-wrapper active">
                <div class="sidebar-header">
                    <div class="d-flex justify-content-between">
                        <div class="toggler">
                            <a href="#" class="sidebar-hide d-xl-none d-block"><i class="bi bi-x bi-middle"></i></a>
                        </div>
                    </div>
                </div>
                <div class="sidebar-menu">
                    <ul class="menu">
                        <li class="sidebar-title">เมนู</li>
                        <li class="sidebar-item  ">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>หน้าหลัก</span>
                            </a>
                        </li>

                        <li class="sidebar-item active has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-stack"></i>
                                <span>กรอกข้อมูล</span>
                            </a>
                            <ul class="submenu active ">
                                <li class="submenu-item ">

                                    <a href="form-work.php">ข้อมูลงานจ้างเหมาเฉพาะค่าแรง</a>
                                </li>
                                <li class="submenu-item active">
                                    <a href="form-wbs.php">ระบุองค์ประกอบหมายเลขงาน WBS</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-employees.php">ผู้ควบคุมงาน</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-center.php">แต่งตั้งคณะกรรมการราคากลาง</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-check.php">แต่งตั้งคณะกรรมการตรวจรับพัสดุ</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-price.php">ใบเสนอราคา</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-vender.php">ข้อมูลผู้รับจ้าง</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-demolish.php">แต่งตั้งคณะกรรมการตรวจรื้อถอน</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-contract.php">ข้อมูลสัญญาจ้าง</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-check_forme.php">ผลการตรวจรับ</a>
                                </li>
                            </ul>
                        </li>

                        <li class="sidebar-item  has-sub">
                            <a href="#" class='sidebar-link'>
                                <i class="bi bi-collection-fill"></i>
                                <span>พิมพ์รายงาน</span>
                            </a>
                            <ul class="submenu ">
                                <?php if (isset($row3["Address"])) {
                                    if (isset($row3["Employee"])) { ?>
                                        <li class="submenu-item ">
                                            <a href="../PHPWord/WordA.php?create=<?= $id; ?>" target="_blank">รายงานขอจ้าง</a>
                                        </li>
                                        <?php
                                        if (isset($row3["Rank_C_C"])) {
                                            if (isset($row3["Rank_C_Check"])) { ?>
                                                <li class="submenu-item ">
                                                    <a href="../PHPWord/WordB.php?create=<?= $id; ?>"
                                                        target="_blank">ขออนุมัติกำหนดราคากลาง</a>
                                                </li>
                                                <li class="submenu-item ">
                                                    <a href="../PHPWord/WordB_2.php?create=<?= $id; ?>"
                                                        target="_blank">แบบฟอร์มคำนวณราคากลาง</a>
                                                </li>
                                                <?php
                                                if (isset($row3["Vender_List"])) { ?>

                                                    <li class="submenu-item ">
                                                        <a href="../PHPWord/WordC.php?create=<?= $id; ?>"
                                                            target="_blank">รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง</a>
                                                    </li>
                                                    <li class="submenu-item ">
                                                        <a href="../PHPWord/WordD.php?create=<?= $id; ?>"
                                                            target="_blank">ใบขอเสนอซื้อ/จ้าง</a>
                                                    </li>

                                                    <?php if (isset($rowcon["ContractNo"])) { ?>
                                                        <li class="submenu-item ">
                                                            <a href="../PHPWord/WordE.php?create=<?= $id; ?>"
                                                                target="_blank">ขออนุมัติวางเงินประกัน</a>
                                                        </li>
                                                        <li class="submenu-item ">
                                                            <a href="pdfH.php?create=<?= $id; ?>" target="_blank">สัญญาจ้างเหมา</a>
                                                        </li>
                                                        <li class="submenu-item ">
                                                            <a href="pdfI.php?create=<?= $id; ?>" target="_blank">แจ้งให้เข้าเริ่มดำเนินการ</a>
                                                        </li>
                                                        <?php if (isset($row3["po"])) { ?>
                                                            <li class="submenu-item ">
                                                                <a href="../PHPSpreadsheet/excel.php?create=<?= $id; ?>" target="_blank">จค 01</a>
                                                            </li>
                                                            <!-- <li class="submenu-item ">
                                                                <a href="../PHPWord/WordG.php?create=<?= $id; ?>"
                                                                    target="_blank">ขออนุมัติวางเงินประกันจ้างเหมาเอกชนช่วยงานก่อสร้างระบบจำหน่ายไฟฟ้า</a>
                                                            </li> -->
                                                            <li class="submenu-item ">
                                                                <a href="../PHPWord/WordH.php?create=<?= $id; ?>"
                                                                    target="_blank">ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน</a>
                                                            </li>
                                                            <!-- <li class="submenu-item ">
                                                                <a href="../PHPWord/WordH_2.php?create=<?= $id; ?>"
                                                                    target="_blank">ขออนุมัติสำรวจทรัพย์สินระบบไฟฟ้าเพื่อการรื้อถอน</a>
                                                            </li> -->
                                                            <li class="submenu-item ">
                                                                <a href="../PHPWord/WordH_3.php?create=<?= $id; ?>"
                                                                    target="_blank">รายงานการสำรวจและการรื้อถอนทรัพย์สินอุปกรณ์ระบบไฟฟ้า</a>
                                                            </li>
                                                            <li class="submenu-item ">
                                                                <a href="../PHPWord/WordI.php?create=<?= $id; ?>"
                                                                    target="_blank">แบบฟอร์มตรวจสอบมาตรฐานงานก่อสร้างและปรับปรุงระบบจำหน่าย</a>
                                                            </li>
                                                            <li class="submenu-item ">
                                                                <a href="../PHPWord/WordJ.php?create=<?= $id; ?>"
                                                                    target="_blank">การส่งมอบงานก่อสร้างระบบไฟฟ้าและขออนุมัติจ่ายกระแสไฟฟ้า</a>
                                                            </li>
                                                        <?php }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } ?>
                            </ul>
                        </li>
                    </ul>
                </div>
                <button class="sidebar-toggler btn x"><i data-feather="x"></i></button>
            </div>
        </div>
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            <div class="page-heading">
                <div class="page-title">
                    <div class="row">
                        <div class="col-12 col-md-6 order-md-1 order-last">
                            <h3>ระบุองค์ประกอบหมายเลขงาน WBS</h3>
                        </div>
                    </div>
                </div>

                <div class="row match-height">
                    <div class="card">
                        <div class="card-body">
                            <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-3">
                                        <br>
                                        <input class="form-control" type="file" name="filUpload"
                                            accept="application/vnd.ms-excel" multiple required>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="first-name-vertical">factor</label>
                                        <?php
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
                                            <input class="form-control" type="number" step="0.0001" name="factor"
                                                value="<?php echo $_POST['factor']; ?>">
                                            <?php
                                        } else { ?>
                                            <input class="form-control" type="number" step="0.0001" name="factor" required>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="form-group col-md-2">
                                        <label for="first-name-vertical">คิดค่าแรง (%)</label>
                                        <?php
                                        if ($_SERVER["REQUEST_METHOD"] == "POST") { ?>
                                            <input class="form-control" type="number" step="0.0001" name="wage"
                                                value="<?php echo $_POST['wage']; ?>">
                                            <?php
                                        } else { ?>
                                            <input class="form-control" type="number" step="0.0001" name="wage" required>
                                            <?php
                                        }
                                        ?>
                                    </div>
                                    <div class="col-md-3">
                                        <br>
                                        <button id="fileSelect" class="btn btn-outline-primary"
                                            name="submit">ตกลง</button>
                                    </div>
                                </div>
                            </form>
                            <div class="row">
                                <div class="col-md-7">
                                </div>
                                <div class="col-md-1">
                                    <button id="fileSelect" class="btn btn-outline-danger">
                                        <a href="delwbs.php" style="color: red;"
                                            onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a>
                                    </button>
                                </div>
                            </div>
                            <?php
                            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                                if (move_uploaded_file($_FILES["filUpload"]["tmp_name"], $_FILES["filUpload"]["name"])) {
                                    require_once "Classes/PHPExcel.php"; //เรียกใช้ library สำหรับอ่านไฟล์ excel
                                    $tmpfname = $_FILES["filUpload"]["name"]; //กำหนดให้อ่านข้อมูลจากไฟล์จากไฟล์ชื่อ
                                    //สร้าง object สำหรับอ่านข้อมูล ชื่อ $excelReader
                                    $excelReader = PHPExcel_IOFactory::createReaderForFile($tmpfname);
                                    $excelObj = $excelReader->load($tmpfname); //อ่านข้อมูลจากไฟล์ชื่อ test_excel.xlsx
                                    $worksheet = $excelObj->getSheet(0); //อ่านข้อมูลจาก sheet แรก
                                    $lastRow = $worksheet->getHighestRow();

                                    $factor = floatval($_POST['factor']);
                                    $wage = floatval($_POST['wage']);

                                    // $factor = is_float($factor);
                                    //$wage = is_float($wage);
                            

                                    //นับว่า sheet แรกมีทั้งหมดกี่แถวแล้วเก็บจำนวนแถวไว้ในตัวแปรชื่อ $lastRow
                                    ?>
                                    <br>
                                    <table class="table table-striped">

                                        <?php
                                        $newprice = 0;
                                        for ($row = 2; $row <= $lastRow; $row++) //วน loop อ่านข้อมูลเอามาแสดงทีละแถว
                                        {
                                            $dataId = $worksheet->getCell('F' . $row)->getValue();
                                            $dataName = $worksheet->getCell('G' . $row)->getValue();
                                            $dataJob = $worksheet->getCell('D' . $row)->getValue();
                                            $dataType = $worksheet->getCell('E' . $row)->getValue();
                                            $dataQty = $worksheet->getCell('H' . $row)->getValue();
                                            $dataUnit = $worksheet->getCell('I' . $row)->getValue();
                                            $dataPrice = $worksheet->getCell('K' . $row)->getValue();
                                            $dataWbs = $worksheet->getCell('C' . $row)->getValue();
                                            $newprice = round($dataPrice * $factor * ($wage / 100) / $dataQty, 2);
                                            echo ("newprice = " . $newprice);
                                            if ($dataPrice != 0) {
                                                $sql = "INSERT INTO new285data (id, name, job, type, qty, unit, price, newprice, factor, wage, wbs, user, userid )VALUES ('$dataId', '$dataName', '$dataJob','$dataType',$dataQty,'$dataUnit', $dataPrice,$newprice, $factor,$wage, '$dataWbs', $user ,$id )";

                                                if (mysqli_query($conn, $sql)) {

                                                } else {
                                                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                                                }
                                            }
                                            $sql = "UPDATE new285data SET factor='$factor', newprice =  $newprice, wage = $wage WHERE id = '$dataId' AND ( user = $user  ) AND ( userid = $id  )";

                                            if ($conn->query($sql) === TRUE) {
                                            } else {
                                                echo "Error updating record: " . $conn->error;
                                            }
                                            $newprice = 0;
                                            $dataPrice = 0;
                                        }
                                        ?>
                                    </table>
                                    <?php
                                }
                            }
                            ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-12 col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="form-group col-md-3">
                                    <label for="first-name-vertical">โครงข่าย</label>
                                </div>
                            </div>
                            <?php
                            $array_etworkdata = array();
                            $array_etworkdata_count = 0;
                            $sql_networkdata = "SELECT id FROM networkdata WHERE iduser = $user AND ( idwork = $id  )";
                            $result_networkdata = $conn->query($sql_networkdata);
                            while ($row_networkdata = $result_networkdata->fetch_assoc()) {
                                $array_etworkdata[$array_etworkdata_count] = $row_networkdata['id'];
                                $array_etworkdata_count++;
                                ?>
                                <form method="post" action="delnetwork_select.php" enctype="multipart/form-data">
                                    <div class="row">
                                        <div class="form-group col-md-2">
                                            <input type="text" value="<?= $row_networkdata['id']; ?>" class="form-control"
                                                disabled>
                                            <input type="hidden" name="idwork" value="<?= $id ?>">
                                            <input type="hidden" name="iduser" value="<?= $user ?>">
                                            <input type="hidden" name="network" value="<?= $row_networkdata['id']; ?>">
                                        </div>
                                        <div class="form-group col-md-3">
                                            <button class="btn btn-outline-danger" type="submit">ลบ</button>
                                        </div>
                                    </div>
                                </form>
                                <?php
                            }
                            ?>
                            <form method="post" action="addnetwork_select.php" enctype="multipart/form-data">
                                <div class="row">
                                    <div class="form-group col-md-2">
                                        <input type="text" name="network" maxlength="10" class="form-control" required>
                                        <input type="hidden" name="idwork" value="<?= $id ?>">
                                        <input type="hidden" name="iduser" value="<?= $user ?>">
                                    </div>
                                    <div class="form-group col-md-3">
                                        <button class="btn btn-outline-primary" type="submit">เพิ่ม</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <?php
                $sql123 = "SELECT job,wage,factor FROM new285data WHERE    user = $user   AND ( userid = $id  ) GROUP BY job";
                $result123 = $conn->query($sql123);
                $type = '';
                $countid = 19;
                while ($row123 = $result123->fetch_assoc()) {
                    $job = $row123["job"];
                    $sql_type = "SELECT type FROM new285data WHERE    user = $user AND ( job = '$job' )  AND ( userid = $id  ) GROUP BY type";
                    $result_type = $conn->query($sql_type);
                    while ($row_type = $result_type->fetch_assoc()) {
                        $type = $row_type["type"];
                        ?>
                        <div class="col-md-12 col-12" id="<?= $countid ?>">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <table class="table table-striped">
                                            <tr>
                                                <th>ที่</th>
                                                <th style="width: 10rem;">แผนก</th>
                                                <th style="width: 10rem;">ประเภทงาน</th>
                                                <th>รายการ</th>
                                                <th>จำนวน</th>
                                                <th>หน่วย</th>
                                                <th style="width: 8rem;text-align:center;">ค่าเเรงตามประมาณการ 100%</th>
                                                <th style="width: 8rem;text-align:center;">ค่าเเรงตามประมาณการ <br>
                                                    <?= $row123["wage"]; ?>%
                                                </th>
                                                <th style="width: 7rem;text-align:center;">ราคากลางประมาณการ <br>x
                                                    <?= $row123["factor"]; ?>
                                                </th>
                                                <th style="width: 8rem;text-align:center;">ราคากลางต่อหน่วย</th>
                                                <th style="width: 10rem;text-align:center;">โครงข่าย</th>
                                            </tr>
                                            <?php
                                            $sql12 = "SELECT id, price, qty, job, type, name, unit, wage, network FROM new285data WHERE  job = '$job' AND type = '$type' AND (user = $user) AND ( userid = $id  )";
                                            $result12 = $conn->query($sql12);
                                            $i = 1;
                                            while ($row12 = $result12->fetch_assoc()) {
                                                $data = $row12["id"];
                                                $sqldata = "SELECT * FROM data WHERE ID = $data";
                                                if ($resultdata = $conn->query($sqldata)) {
                                                    $rowdata = $resultdata->fetch_assoc();
                                                }
                                                if ($row12["price"] != 0) {
                                                    $DataIdForAt = $row12["id"];
                                                    $DataQTYForSelect = $row12["qty"];
                                                    $DataIdForType = substr($type, 0, 3); ?>

                                                    <tr>
                                                        <td>
                                                            <?= $i ?>
                                                        </td>
                                                        <td>
                                                            <?= $row12["job"]; ?>
                                                        </td>
                                                        <td>
                                                            <?= $row12["type"]; ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($rowdata["NAME"])) {
                                                                echo $rowdata["NAME"];
                                                            } else {
                                                                echo $row12["name"];
                                                            }
                                                            ; ?>
                                                        </td>
                                                        <td>
                                                            <?= number_format($row12["qty"], 2); ?>
                                                        </td>
                                                        <td>
                                                            <?php if (isset($rowdata["UNIT"])) {
                                                                echo $rowdata["UNIT"];
                                                            } else {
                                                                echo $row12["unit"];
                                                            }
                                                            ; ?>
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format($row12["price"], 2); ?>
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format($row12["price"] * $row12["wage"] / 100, 2); ?>
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format($row12["price"] * ($row12["wage"] / 100) * $row123["factor"], 2); ?>
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format($row12["price"] * $row12["wage"] / 100 * $row123["factor"] / $row12["qty"], 2); ?>
                                                        </td>
                                                        <td><select class="form-control" name="network_select" id="network_select"
                                                                onchange="changeValueFunction('<?= $job ?>','<?= $DataIdForType ?>',<?= $DataQTYForSelect ?>,'<?= $DataIdForAt ?>',this.value)">
                                                                <option>
                                                                    <?= $row12["network"]; ?>
                                                                </option>
                                                                <?php for ($count_net = 0; $count_net < $array_etworkdata_count; $count_net++) {
                                                                    ?>
                                                                    <option value=<?= $array_etworkdata[$count_net]; ?>><?= $array_etworkdata[$count_net]; ?></option>
                                                                    <?php
                                                                }
                                                                ?>
                                                            </select>
                                                        </td>
                                                        <td><a href="del1.php?id=<?= $row12['id']; ?>&network=<?= $row12['network']; ?>"
                                                                style="color: red;"
                                                                onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>
                                                    </tr>
                                                    <?php $i = $i + 1;
                                                }
                                            } ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                        $countid = $countid + 1;
                    }
                } ?>
            </div>
        </div>
        <footer>
            <div class="footer clearfix mb-0 text-muted">
                <div class="float-start">
                    <p>2022 &copy; 285</p>
                </div>
            </div>
        </footer>
    </div>
    </div>
    <script>
        function changeValueFunction(job, type, qty, id, network) {
            fetch("service/addNetworkSelect.php", {
                method: "POST",
                body: JSON.stringify({
                    Datajob: job,
                    DataType: type,
                    DataQty: qty,
                    DataId: id,
                    DataNetwork: network,
                })
            }).then(function (response) {
                return response.json();
            }).then(function (responseData) {
                console.log({
                    responseData
                });
            });
        }
    </script>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/main.js"></script>
</body>

</html>
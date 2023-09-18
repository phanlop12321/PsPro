<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include('connection.php');
if (isset($_GET['create'])) {
    $_SESSION["ID"] = $_GET['create'];
}

$user = $_SESSION["User"];
$id = $_SESSION["ID"];

$sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$user' )";
$result3 = $conn->query($sql3);

$row3 = $result3->fetch_assoc();
$sqlcon = "SELECT * FROM contract WHERE Id = $id AND ( User = '$user' )";
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
    <link rel="shortcut icon" href="assets/images/favicon.svg" type="image/x-icon">
    <link rel="stylesheet" href="assets/css/text.css">
    <style>
        input:last-child::placeholder {
            color: #DADDE0;
        }
    </style>
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
                                <li class="submenu-item active">
                                    <a href="form-work.php">ข้อมูลงานจ้างเหมาเฉพาะค่าแรง</a>
                                </li>
                                <li class="submenu-item ">
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
                                                                <a href="../PHPSpreadsheet/excel.php?create=<?= $id; ?>" ta rget="_blank">จค 01</a>
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
                            <h3>ข้อมูลงานจ้างเหมาเฉพาะค่าแรง</h3>
                        </div>
                    </div>
                </div>

                <!-- Basic Horizontal form layout section start -->
                <section id="basic-horizontal-layouts">
                    <div class="row match-height">
                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-header">
                                </div>
                                <div class="card-content">
                                    <div class="card-body">
                                        <form class="form form-horizontal" action="addwork.php" method="post">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>ชื่องาน</label>
                                                        </div>
                                                        <div class="col-md-9 form-group">
                                                            <input type="text" id="first-name" class="form-control"
                                                                name="Name" value="<?php if (isset($row3["Name"])) {
                                                                    echo $row3["Name"];
                                                                } ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>ประเภทงาน</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <fieldset class="form-group">
                                                                <select class="form-select" name="type_budget">
                                                                    <option>งบผู้ใช้ไฟ</option>
                                                                    <option>งบโครงการ</option>
                                                                    <option>งบลงทุน</option>
                                                                    <?php if (isset($row3["Type_Budget"])) { ?>
                                                                        <option selected>
                                                                            <?= $row3["Type_Budget"]; ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                        <div class="col-md-3 d-flex justify-content-end">
                                                            <label>ปี พ.ศ. (25xx)</label>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <input type="number" class="form-control" name="year"
                                                                placeholder="66" value="<?php if (isset($row3["year"])) {
                                                                    echo $row3["year"];
                                                                } ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-2">
                                                            <label>เลขที่ผังงาน</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="diagram"
                                                                value="<?php if (isset($row3["Diagram"])) {
                                                                    echo $row3["Diagram"];
                                                                } ?>" required>
                                                        </div>
                                                        <div class="col-md-3 d-flex justify-content-end">
                                                            <label>มาตฐานการให้บริการ (วัน)</label>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <input type="number" class="form-control" name="delivery"
                                                                value="<?php if (isset($row3["delivery"])) {
                                                                    echo $row3["delivery"];
                                                                } ?>" required>
                                                        </div>
                                                        <div class="col-md-2">
                                                            <a href="https://www.pea.co.th/Portals/0/Document/WorkStandard/standardmix_2564_1.pdf"
                                                                target="_blank">
                                                                <i class="bi bi-exclamation-circle-fill"></i>
                                                            </a>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>เลขที่อนุมัติประมาณการ</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="estimate"
                                                                value="<?php if (isset($row3["Estimate"])) {
                                                                    echo $row3["Estimate"];
                                                                } ?>" required>
                                                        </div>
                                                        <div class="col-md-2 d-flex justify-content-end">
                                                            <label>ลว.</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="date" class="form-control" name="estimate_date"
                                                                value="<?php if (isset($row3["Estimate_Date"])) {
                                                                    echo $row3["Estimate_Date"];
                                                                } ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>เลขที่อนุมัติงานก่อสร้าง</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="construct"
                                                                value="<?php if (isset($row3["Construct"])) {
                                                                    echo $row3["Construct"];
                                                                } ?>" required>
                                                        </div>
                                                        <div class="col-md-2 d-flex justify-content-end">
                                                            <label>ลว.</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="date" class="form-control"
                                                                name="construct_date" value="<?php if (isset($row3["Construct_Date"])) {
                                                                    echo $row3["Construct_Date"];
                                                                } ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>เลขที่รายงานขอจ้าง</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="No_paper"
                                                                value="<?php if (isset($row3["Nopaper"])) {
                                                                    echo $row3["Nopaper"];
                                                                } ?>" required>
                                                        </div>
                                                        <div class="col-md-2 d-flex justify-content-end">
                                                            <label>ลว.</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="date" class="form-control" name="No_paper_date"
                                                                value="<?php if (isset($row3["Nopaperdate"])) {
                                                                    echo $row3["Nopaperdate"];
                                                                } ?>" required>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>หลักเกณฑ์การพิจารณา</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <fieldset class="form-group">
                                                                <select class="form-select" name="decide">
                                                                    <option value="1">พิจารณาจากราคารวม</option>
                                                                    <option value="2">พิจารณาจากราคาต่อรายการ</option>
                                                                    <option value="3">พิจารณาจากราคาต่อหน่วย</option>
                                                                    <?php if (isset($row3["decide"])) { ?>
                                                                        <option selected>
                                                                            <?php if ($row3["decide"] == 1) {
                                                                                echo "พิจารณาจากราคารวม";
                                                                            } else if ($row3["decide"] == 2) {
                                                                                echo "พิจารณาจากราคาต่อรายการ";
                                                                            } else if ($row3["decide"] == 3) {
                                                                                echo "พิจารณาจากราคาต่อหน่วย";
                                                                            } ?>
                                                                        </option>
                                                                    <?php } ?>
                                                                </select>
                                                            </fieldset>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>สถานที่ ดำเนินก่อสร้างขยายเขตระบบจำหน่ายไฟฟ้า
                                                                (บ้านเลขที่ / หมู่ / ตำบล / อำเภอ / จังหวัด)</label>
                                                        </div>
                                                    </div>
                                                    <div class="row ">
                                                        <div class="col-md-11 form-group ">
                                                            <input type="text" id="first-name" class="form-control"
                                                                name="Address" value="<?php if (isset($row3["Address"])) {
                                                                    echo $row3["Address"];
                                                                } ?>" required>
                                                        </div>
                                                        <div class="col-md-1 form-group "></div>
                                                    </div>

                                                    <div class="col-sm-12 ">
                                                        <button type="submit"
                                                            class="btn btn-outline-primary me-1 mb-1">บันทึก</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </section>
                <!-- // Basic Horizontal form layout section end -->
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
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>
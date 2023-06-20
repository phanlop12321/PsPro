<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include('connection.php');
if (isset($_GET['create'])) {
    $_SESSION["ID"] = $_GET['create'];
}

$i = 1;
$price = 0;
$vat = 0;
$price_abd_vat = 0;

$user = $_SESSION["User"];
$id = $_SESSION["ID"];

$sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$user' )";
$result3 = $conn->query($sql3);

$row3 = $result3->fetch_assoc();

$sql4 = "SELECT * FROM wbs WHERE  (id = $id) AND ( user = '$user' )";
$result5 = $conn->query($sql4);
$result6 = $conn->query($sql4);
$result4 = $conn->query($sql4);
$result15 = $conn->query($sql4);
$row5 = $result5->fetch_assoc();

$sqlcon = "SELECT * FROM contract WHERE Id = $id AND ( User = '$user' )";
$resultcon = $conn->query($sqlcon);
$rowcon = $resultcon->fetch_assoc();


$sql9 = "SELECT * FROM wbs WHERE  (id = $id) AND ( user = '$user' ) GROUP BY WBS";
$result9 = $conn->query($sql9);
$num_rows = mysqli_num_rows($result9);

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
                                <li class="submenu-item ">
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
                                    <a href="form-contract.php">ข้อมูลสัญญาจ้าง</a>
                                </li>
                                <li class="submenu-item active">
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
                                                <?php
                                                if (isset($row3["Vender_List"])) { ?>

                                                    <li class="submenu-item ">
                                                        <a href="../PHPWord/WordC.php?create=<?= $id; ?>"
                                                            target="_blank">รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง</a>
                                                    </li>
                                                    <li class="submenu-item ">
                                                        <a href="../PHPWord/WordB_2.php?create=<?= $id; ?>"
                                                            target="_blank">แบบฟอร์มคำนวณราคากลาง</a>
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
                            <h3>รายงานผลการตรวจรับ</h3>
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
                                        <form class="form form-vertical" action="addnewqty.php" method="POST">
                                            <div class="row">
                                                <div class="col-md-3">
                                                    <h6>ใบสั่งจ้าง(Purchase Order No)</h6>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <input type="text" class="form-control" name="po" value="<?php if (isset($row3["po"])) {
                                                        echo $row3["po"];
                                                    } ?>" required>
                                                </div>
                                                <div class="col-md-1">
                                                    <h6>ลงวันที่</h6>
                                                </div>
                                                <div class="col-md-3 form-group">
                                                    <input type="date" class="form-control" name="po_date" value="<?php if (isset($row3["po_date"])) {
                                                        echo $row3["po_date"];
                                                    } ?>" required>
                                                </div>
                                            </div>
                                            <?php

                                            $ii = 1;
                                            while ($row5 = $result15->fetch_assoc()) {



                                                if (is_array($row5)) {



                                                    $NETWORK = $row5["NETWORK"];



                                                    $sql = "SELECT * FROM end_data WHERE network =  $NETWORK  ";
                                                    $result = $conn->query($sql);

                                                    ?>

                                                    <div>
                                                        <table class="table table-striped">
                                                            <div class="row">
                                                                <div class="form-group col-md-3">
                                                                    <input type="text" class="form-control"
                                                                        value="WBS <?= $row5["WBS"]; ?>" disabled>
                                                                </div>
                                                                <div class="form-group col-md-3">
                                                                    <input type="text" class="form-control"
                                                                        value="โครงข่าย <?= $row5["NETWORK"]; ?>" disabled>
                                                                </div>
                                                            </div>
                                                            <tr>
                                                                <th>ที่</th>
                                                                <th>แผนก</th>
                                                                <th>รายละเอียด</th>
                                                                <th>จำนวน</th>
                                                                <th>ผลการตรวจรับ</th>
                                                            </tr>
                                                            <?php


                                                            // output data of each row
                                                            $i = 1;
                                                            while ($row = $result->fetch_assoc()) {

                                                                ?>
                                                                <tr>
                                                                    <td>
                                                                        <?= $i ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= $row["job"]; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= $row["name"]; ?>
                                                                    </td>
                                                                    <td>
                                                                        <?= $row["quantity"]; ?>
                                                                    </td>
                                                                    <td>
                                                                        <input type='text' class="form-control"
                                                                            name='qty[<?= $ii ?>]' value="<?php if (isset($row["qty"])) {
                                                                                  echo $row["qty"];
                                                                              } ?>" required>
                                                                        <input type="hidden" name='id[<?= $ii ?>]'
                                                                            value="<?= $row["id"]; ?>">
                                                                        <input type="hidden" name='network[<?= $ii ?>]'
                                                                            value="<?= $row5["NETWORK"]; ?>">
                                                                    </td>
                                                                </tr>
                                                                <?php
                                                                $i = $i + 1;
                                                                $ii++;
                                                                $price = $price + $row["price_no_v"];
                                                                $vat = $vat + $row["vat"];
                                                                $price_abd_vat = $price_abd_vat + $row["price_and_v"];
                                                            }
                                                            ?>
                                                            <tr>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                                <td></td>
                                                            </tr>
                                                        </table>
                                                    </div>
                                                    <?php
                                                }
                                            } ?>
                                            <input type="hidden" name="count" value="<?= $ii ?>">
                                            <h6>
                                                ***หมายเหตุ
                                            </h6>
                                            <input type="text" class="form-control" name="comment"
                                                placeholder="สาเหตุที่ตรวจรับไม่ครบถ้วนตามสัญญา" value="<?php if (isset($row3["etc"])) {
                                                    echo $row3["etc"];
                                                } ?>">
                                            <br>
                                            <div class="center1">
                                                <button type="submit" class="btn btn-outline-primary"> บันทึก </button>
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
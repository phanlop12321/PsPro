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
                                    <div class="col-md-3">
                                        <br>
                                        <button id="fileSelect" class="btn btn-outline-primary"
                                            name="submit">ตกลง</button>
                                    </div>
                                </div>
                            </form>
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


                                    // $factor = is_float($factor);
                                    //$wage = is_float($wage);
                            

                                    //นับว่า sheet แรกมีทั้งหมดกี่แถวแล้วเก็บจำนวนแถวไว้ในตัวแปรชื่อ $lastRow
                                    ?>
                                    <br>
                                    <table class="table table-striped">

                                        <?php
                                        $checkValue = 'แผนก';
                                        $checkWbs = 'WBS';
                                        $newprice = 0;
                                        

                                        // output data of each row
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

                                            //  echo gettype($wage) . "<br>";
                                            //  echo gettype($factor) . "<br>";
                                            //  echo gettype($dataPrice) . "<br>";
                                
                                            //   echo "Wage = ".$wage." Factor = ".$factor." price = ".$dataPrice;
                                            $newprice = 1;
                                            //  echo "Price = ".$dataPrice;
                                
                                            $sql123 = "SELECT * FROM new285data WHERE  id = '$dataId' AND ( type = '$dataType' ) AND ( job = '$dataJob' ) AND ( user = $user  ) AND ( userid = $id  )";
                                            $result123 = $conn->query($sql123);
                                            $row123 = mysqli_num_rows($result123);

                                            if ($row123 === 0) {

                                                $sql = "INSERT INTO new285data (id, name, job, type, qty, unit, price, newprice, factor, wage, wbs, user, userid )
                                                VALUES ('$dataId', '$dataName', '$dataJob','$dataType','$dataQty','$dataUnit', 1,1, '1', '1', '$dataWbs', $user ,$id )";

                                                if (mysqli_query($conn, $sql)) {

                                                } else {
                                                    echo "Error: " . $sql . "<br>" . mysqli_error($conn);
                                                }
                                            } else {
                                                $sql = "UPDATE new285data SET factor='1', newprice =  '$newprice', wage = '1' WHERE id = '$dataId' AND ( user = $user  ) AND ( userid = $id  )";

                                                if ($conn->query($sql) === TRUE) {
                                                } else {
                                                    echo "Error updating record: " . $conn->error;
                                                }
                                            }
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

                <?php
                $sql123 = "SELECT * FROM new285data WHERE    user = $user   AND ( userid = $id  ) GROUP BY job";
                $result123 = $conn->query($sql123);
                $type = '';
                while ($row123 = $result123->fetch_assoc()) {
                    $job = $row123["job"];

                    $sql1234 = "SELECT * FROM new285data WHERE    user = $user AND ( job = '$job' )  AND ( userid = $id  ) GROUP BY type";
                    $result1234 = $conn->query($sql1234);

                    while ($row1234 = $result1234->fetch_assoc()) {
                        $type = $row1234["type"];



                        ?>

                        <div class="col-md-12 col-12">
                            <div class="card">
                                <div class="card-content">
                                    <div class="card-body">
                                        <table class="table table-striped">

                                            <form method="post" action="addnetwork.php" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="form-group col-md-3">
                                                        <br>
                                                        <input type="text" class="form-control"
                                                            value="WBS <?= $row123['wbs']; ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <br>
                                                        <input type="text" class="form-control"
                                                            value="factor <?= $row123["factor"]; ?>" disabled>
                                                    </div>
                                                    <div class="form-group col-md-2">
                                                        <label for="first-name-vertical">โครงข่าย</label>
                                                        <input type="text" name="network" class="form-control" value="<?php if (isset($row1234["network"])) {
                                                            echo $row1234["network"];
                                                        } ?>" required>
                                                        <input type="hidden" name="job" value="<?= $job ?>">
                                                        <input type="hidden" name="type" value="<?= $type ?>">
                                                    </div>
                                                    <div class="form-group col-md-3">
                                                        <br>
                                                        <button class="btn btn-outline-primary" type="submit">เพิ่ม</button>
                                                        <?php
                                                        if (isset($row1234["network"])) { ?>
                                                            <button class="btn btn-outline-danger"> <a
                                                                    href="del2.php?network=<?= $row1234['network']; ?>"
                                                                    style="color: red;"
                                                                    onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></button>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </form>
                                            <tr>
                                                <th>ที่</th>
                                                <th style="width: 10rem;">แผนก</th>
                                                <th style="width: 10rem;">ประเภทงาน</th>
                                                <th>รายการ</th>
                                                <th>จำนวน</th>
                                                <th>หน่วย</th>
                                                <th style="width: 8rem;text-align:center;">เลือกสภาพภูมิประเทศ</th>
                                                <th style="width: 8rem;text-align:center;">ราคากลางต่อหน่วย
                                                </th>
                                                <th style="width: 7rem;text-align:center;">ราคาไม่รวมภาษีฯ
                                                </th>
                                                <th style="width: 8rem;text-align:center;">ราคารวมภาษีฯ</th>

                                            </tr>
                                            <?php
                                            $sql12 = "SELECT * FROM new285data WHERE  job = '$job' AND type = '$type' AND (user = $user) AND ( userid = $id  )";
                                            $result12 = $conn->query($sql12);
                                            $i = 1;

                                            while ($row12 = $result12->fetch_assoc()) {
                                                $data = $row12["id"];
                                                $sqldata = "SELECT * FROM data WHERE ID = $data";
                                                $resultdata = $conn->query($sqldata);
                                                $rowdata = $resultdata->fetch_assoc();

                                                if ($row12["price"] != 0) {
                                                    $DataIdForAt = $row12["id"];
                                                    ?>
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
                                                            <?= $row12["qty"]; ?>
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
                                                            <input type="text" class="form-control" name="case" value="<?= $row12["AT"];?>"
                                                                id="case_ID<?= $DataIdForAt ?>"
                                                                onclick=" checkdata(<?= $DataIdForAt ?>)" required>
                                                            <span id="search_result_data285case<?= $DataIdForAt ?>"
                                                                class="search_result<?= $DataIdForAt ?>">
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format($row12["price"], 2); ?>
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format($row12["price"]*$row12["qty"], 2); ?>
                                                        </td>
                                                        <td style="width: 8rem;text-align:center;">
                                                            <?= number_format(($row12["price"]* $row12["qty"])+($row12["price"]* $row12["qty"]*0.07), 2); ?>
                                                        </td>

                                                        <td><a href="del1.php?id=<?= $row12['id']; ?>&network=<?= $row12['network']; ?>"
                                                                style="color: red;"
                                                                onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>
                                                    </tr>
                                                    <?php $i = $i + 1;
                                                }

                                            } ?>
                                        </table>
                                        <button  class="btn btn-outline-primary" onClick="window.location.reload();">ตกลง</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }

                } ?>






            </div>
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
    <script>
        let DataNumberId = '';
        let DataAtId = { app: 1, message: 1 };


        function checkdata(dataAt) {
            const data285Name = dataAt;
            DataNumberId = data285Name;
            // console.log(data285Name);
            fetchdata285case(data285Name)
        }

        const fetchdata285case = (data285Name) => {

            fetch("service/getcase285.php", { //ส่ง id เพื่อไป getdata employee
                method: "POST",
                body: JSON.stringify({ //encode json
                    data285Name: data285Name
                })
            }).then(function (response) {

                return response.json();

            }).then(function (responseData) {
                // console.log({
                //     responseData
                // });
                const html = autocompletedata285case(responseData);
                if (html != null) {
                    document.getElementById('search_result_data285case' + DataNumberId).innerHTML = html;
                }
            });
        };

        const autocompletedata285case = (data_285) => {
            let html = null;
            if (data_285.length > 0) {
                data285 = data_285;

                html = '<ul class="list-group">';


                for (let count = 0; count < data_285.length; count++) {

                    html += '<li class="list-group-item text-muted" style="cursor:pointer"  onclick="selectdata285case(' + count + ')"><i class="fas fa-history mr-3"></i><span>' + data_285[count].AT_NAME + '</span> <i class="far fa-trash-alt float-right mt-1" ></i></li>';

                }

            }
            return html
        }

        function selectdata285case(key) {
            const Data285Selected = data285[key];
            let y = 0;

            document.getElementById('case_ID' + DataNumberId).value = Data285Selected.AT_NAME

            fetch("service/addPriceN2.php", { //ส่ง id เพื่อไป getdata employee
                method: "POST",
                body: JSON.stringify({ //encode json
                    dataID: DataNumberId,
                    dataAT: Data285Selected.AT_NAME,
                })
            }).then(function (response) {

                return response.json();

            }).then(function (responseData) {
                console.log({
                    responseData
                });
            });

            const els = document.getElementsByClassName('search_result' + DataNumberId);
            // document.getElementById('search_result_id').innerHTML = '';
            Array.prototype.forEach.call(els, function (el) {
                el.innerHTML = '';
            });
        }


    </script>
    <script src="assets/vendors/perfect-scrollbar/perfect-scrollbar.min.js"></script>
    <script src="assets/js/bootstrap.bundle.min.js"></script>

    <script src="assets/js/main.js"></script>
</body>

</html>
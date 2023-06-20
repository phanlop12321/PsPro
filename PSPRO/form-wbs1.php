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
                          <a href="../PHPWord/WordB.php?create=<?= $id; ?>" target="_blank">ขออนุมัติกำหนดราคากลาง</a>
                        </li>
                        <?php
                        if (isset($row3["Vender_List"])) { ?>

                          <li class="submenu-item ">
                            <a href="../PHPWord/WordC.php?create=<?= $id; ?>"
                              target="_blank">รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง</a>
                          </li>
                          <li class="submenu-item ">
                            <a href="../PHPWord/WordD.php?create=<?= $id; ?>" target="_blank">ใบขอเสนอซื้อ/จ้าง</a>
                          </li>

                          <?php if (isset($rowcon["ContractNo"])) { ?>
                            <li class="submenu-item ">
                              <a href="../PHPWord/WordE.php?create=<?= $id; ?>" target="_blank">ขออนุมัติวางเงินประกัน</a>
                            </li>
                            <li class="submenu-item ">
                              <a href="pdfH.php?create=<?= $id; ?>" target="_blank">สัญญาจ้างเหมา</a>
                            </li>
                            <li class="submenu-item ">
                              <a href="pdfI.php?create=<?= $id; ?>" target="_blank">แจ้งให้เข้าเริ่มดำเนินการ</a>
                            </li>
                            <?php if (isset($row3["po"])) { ?>
                              <li class="submenu-item ">
                                <a href="pdfJ.php?create=<?= $id; ?>" target="_blank">รายงานผลการตรวจรับ</a>
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

        <!-- Basic Horizontal form layout section start -->
        <section id="basic-horizontal-layouts">
          <div class="row match-height">
            <div class="col-md-12 col-12">
              <div class="card">
                <div class="card-header">
                </div>
                <div class="card-content">
                  <div class="card-body">
                    <form class="form form-vertical" action="addwbs.php" method="POST">
                      <div class="form-body">
                        <div class="row">
                          <div class="row">
                            <div class="col-3">
                              <div class="form-group">
                                <label for="first-name-vertical">WBS</label>
                                <?php if ($num_rows == 4) { ?>
                                  <select class="form-select" aria-label="Default select example" name="wbs">
                                    <?php while ($row9 = $result9->fetch_assoc()) { ?>
                                      <option>
                                        <?= $row9["WBS"]; ?>
                                      </option>
                                    <?php } ?>
                                  </select>

                                <?php } else { ?>
                                  <input type="text" class="form-control" name="wbs" placeholder="" required>
                                <?php } ?>
                              </div>
                            </div>
                            <div class="col-3">
                              <div class="form-group">
                                <label>รหัสโครงข่าย</label>
                                <input type="text" id="first-name-vertical" class="form-control" name="network">
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-group">
                                <label for="first-name-vertical">กิจกรรม</label>
                                <input type="text" id="first-name-vertical" class="form-control" name="activity">
                              </div>
                            </div>
                            <div class="col-2">
                              <div class="form-group">
                                <label for="first-name-vertical">ค่าแรงงาน(บาท)</label>
                                <input type="text" id="first-name-vertical" class="form-control" name="pricework">
                              </div>
                            </div>
                            <div class="col-2">
                              <br>
                              <button type="submit" class="btn btn-outline-primary me-1 mb-1">เพิ่ม</button>
                            </div>
                          </div>
                        </div>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
            <?php if (is_array($row5)) { ?>
              <div class="col-md-12 col-12">
                <div class="card">
                  <div class="card-header">
                  </div>
                  <div class="card-content">
                    <div class="card-body">
                      <form class="form form-vertical" method="GET" action="check.php">
                        <div class="form-body">
                          <div class="row">
                            <div class="row">
                              <div class="col-3">
                                <fieldset class="form-group">
                                  <label for="first-name-vertical">โครงข่าย</label>
                                  <select class="form-select" aria-label="Default select example" name="NETWORK">
                                    <?php while ($row6 = $result6->fetch_assoc()) { ?>
                                      <option>
                                        <?= $row6["NETWORK"]; ?>
                                      </option>
                                    <?php } ?>
                                  </select>
                                </fieldset>
                              </div>
                              <div class="col-3">
                                <div class="form-group">
                                  <label for="first-name-vertical">ค่าแรงงาน(บาท)</label>
                                  <input type="text" id="first-name-vertical" class="form-control" value="<?php if (isset($row5["pricework"])) {
                                    echo $row5["pricework"];
                                  } ?>" disabled>
                                </div>
                              </div>
                            </div>
                            <div class="row">
                              <div class="col-3">
                                <div class="form-group">
                                  <label for="first-name-vertical">รหัสพัสดุ 10 หลัก</label>
                                  <input type="number" onkeyup="serchdata285('CODE')" class="form-control" name="CODE"
                                    id="ID_CODE" required>
                                  <span id="search_result_data285" class="search_result">
                                </div>
                              </div>
                              <div class="col-3">
                                <div class="form-group">
                                  <label for="first-name-vertical">เลือกสภาพภูมิประเทศ</label>
                                  <input type="text" class="form-control" name="case" id="case_ID" required>
                                  <span id="search_result_data285case" class="search_result">
                                </div>
                              </div>
                              <div class="col-1">
                                <div class="form-group">
                                  <label for="first-name-vertical">จำนวน</label>
                                  <input type="number" step="0.1" class="form-control" name="quantity" placeholder=" "
                                    required>
                                </div>
                              </div>
                              <div class="col-4">
                                <div class="form-group">
                                  <label for="first-name-vertical">ติดตั้ง/รื้อถอน/นำกลับมาใช้ใหม่</label>
                                  <select class="form-select" aria-label="Default select example" name="job" id="job_ID">
                                    <option selected>แผนกติดตั้ง</option>
                                    <option>แผนกรื้อถอน</option>
                                    <option>นำกลับมาใช้ใหม่</option>
                                  </select>
                                </div>
                              </div>
                            </div>
                            <div class="col-sm-12 ">
                              <button type="submit" class="btn btn-outline-primary me-1 mb-1">เพิ่ม</button>
                            </div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
              </div>
              <script>
                //employees

                let data285 = [];
                const headers = {
                  'Content-type': 'application/json; charset=UTF-8'
                };

                function serchdata285(type) {
                  switch (type) {
                    case "CODE":

                      //request  -> find employee form id
                      const data285Name = document.getElementsByName('CODE')[0].value;
                      console.log(data285Name);
                      if (data285Name.length >= 4) {
                        fetchdata285ByName(data285Name);
                      } else {
                        const els = document.getElementsByClassName('search_Check');
                        // document.getElementById('search_result_id').innerHTML = '';
                        Array.prototype.forEach.call(els, function (el) {
                          el.innerHTML = '';
                        });
                      }
                      break;

                    default:
                      console.log("default");
                      break;
                  }
                }

                const fetchdata285ByName = (data285Name) => {

                  fetch("service/data285.php", { //ส่ง id เพื่อไป getdata employee
                    method: "POST",
                    body: JSON.stringify({ //encode json
                      data285Name: data285Name
                    }),
                    headers
                  }).then(function (response) {

                    return response.json();

                  }).then(function (responseData) {
                    console.log({
                      responseData
                    });
                    const html = autocompletedata285(responseData);
                    if (html != null) {
                      document.getElementById('search_result_data285').innerHTML = html;
                    }
                  });
                };

                const autocompletedata285 = (data_285) => {
                  let html = null;
                  if (data_285.length > 0) {
                    data285 = data_285;

                    html = '<ul class="list-group">';


                    for (let count = 0; count < data_285.length; count++) {

                      html += '<li class="list-group-item text-muted" style="cursor:pointer"  onclick="selectdata285(' + count + ')"><i class="fas fa-history mr-3"></i><span>' + data_285[count].ID + '&nbsp;' + data_285[count].NAME + '</span> <i class="far fa-trash-alt float-right mt-1" ></i></li>';

                    }
                    html += '</ul>';
                  }
                  return html
                }

                function selectdata285(key) {
                  const Data285Selected = data285[key];

                  document.getElementsByName('CODE')[0].value = Data285Selected.ID
                  console.log("tttt");
                  console.log(Data285Selected.ID);

                  fetchdata285case(Data285Selected.ID);

                  const els = document.getElementsByClassName('search_result');
                  // document.getElementById('search_result_id').innerHTML = '';
                  Array.prototype.forEach.call(els, function (el) {
                    el.innerHTML = '';
                  });


                }

                const fetchdata285case = (data285Name) => {

                  fetch("service/getcase285.php", { //ส่ง id เพื่อไป getdata employee
                    method: "POST",
                    body: JSON.stringify({ //encode json
                      data285Name: data285Name
                    }),
                    headers
                  }).then(function (response) {

                    return response.json();

                  }).then(function (responseData) {
                    console.log({
                      responseData
                    });
                    const html = autocompletedata285case(responseData);
                    if (html != null) {
                      document.getElementById('search_result_data285case').innerHTML = html;
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

                  document.getElementsByName('case')[0].value = Data285Selected.AT_NAME

                  console.log(Data285Selected.ID);


                  const els = document.getElementsByClassName('search_result');
                  // document.getElementById('search_result_id').innerHTML = '';
                  Array.prototype.forEach.call(els, function (el) {
                    el.innerHTML = '';
                  });
                }

              </script>
              <?php
              while ($row4 = $result4->fetch_assoc()) {



                if (is_array($row4)) {



                  $NETWORK = $row4["NETWORK"];



                  $sql = "SELECT * FROM end_data WHERE network =  $NETWORK  ";
                  $result = $conn->query($sql);

                  ?>

                  <div class="col-md-12 col-12">
                    <div class="card">
                      <div class="card-header">
                      </div>
                      <div class="card-content">
                        <div class="card-body">


                          <table class="table table-striped">
                            <div class="row">
                              <div class="form-group col-md-3">
                                <input type="text" class="form-control" value="WBS <?= $row4["WBS"]; ?>" disabled>
                              </div>
                              <div class="form-group col-md-3">
                                <input type="text" class="form-control" value="โครงข่าย <?= $row4["NETWORK"]; ?>" disabled>
                              </div>
                              <div class="form-group col-md-3">
                                <button class="btn btn-outline-danger"><a
                                    href="del_network.php?id=<?= $row4['id']; ?>&NETWORK=<?= $row4["NETWORK"]; ?>"
                                    style="color: red; " onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></button>
                              </div>
                            </div>
                            <tr>
                              <th>ที่</th>
                              <th>แผนก</th>
                              <th>รายละเอียด</th>
                              <th>จำนวน</th>
                              <th>ราคากลางต่อหน่วย</th>
                              <th>ราคาไม่รวมภาษีฯ</th>
                              <th>ภาษีฯ 7 %</th>
                              <th>ราคารวมภาษีฯ</th>
                              <th>ลบ</th>
                            </tr>
                            <?php


                            // output data of each row
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
                                  <?= "";
                                  echo number_format($row["price"], 2); ?>
                                </td>
                                <td>
                                  <?= "";
                                  echo number_format($row["price_no_v"], 2); ?>
                                </td>
                                <td>
                                  <?= "";
                                  echo number_format($row["vat"], 2); ?>
                                </td>
                                <td>
                                  <?= "";
                                  echo number_format($row["price_and_v"], 2); ?>
                                </td>

                                <td><a href="del.php?id=<?= $row['id']; ?>&network=<?= $row['network']; ?>" style="color: red; "
                                    onclick="return confirm('ยืนยันการลบข้อมูล !!');">ลบ</a></td>
                              </tr>
                              <?php
                              $i = $i + 1;
                              $price = $price + $row["price_no_v"];
                              $vat = $vat + $row["vat"];
                              $price_abd_vat = $price_abd_vat + $row["price_and_v"];
                            }


                            ?>
                            <tr>
                              <td></td>
                              <td></td>
                              <td>ราคารวม (บาท)</td>
                              <td></td>
                              <td></td>
                              <td>
                                <?= "";
                                echo number_format($price, 2);
                                $price = 0; ?>

                              </td>
                              <td>

                                <?= "";
                                echo number_format($vat, 2);
                                $vat = 0; ?>
                              </td>
                              <td>

                                <?= "";
                                echo number_format($price_abd_vat, 2);
                                $price_abd_vat = 0;
                                $i = 1; ?>
                              </td>
                              <td></td>
                            </tr>

                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                  <?php
                }
              } ?>
            <?php } ?>
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
<?php

session_start();

if (!$_SESSION["UserID"]) {  //check session

  Header("Location: formlogin.php"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include('connection.php');
$user = $_SESSION["User"];
$id = $_SESSION["ID"];

$sqlcon = "SELECT * FROM contract WHERE Id = $id AND ( User = '$user' )";
$resultcon = $conn->query($sqlcon);
$rowcon = $resultcon->fetch_assoc();

$sql3 = "SELECT * FROM data285 WHERE  id = $id AND ( user = '$user' )";
$result3 = $conn->query($sql3);

$row3 = $result3->fetch_assoc();

if (isset( $row3["Vender_List"])) {
    $ID_vdlist = $row3["Vender_List"];
    $sql2 = "SELECT * FROM vender WHERE vdlist=$ID_vdlist";
    $result2 = $conn->query($sql2);
    $row2 = $result2->fetch_assoc();
  }


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
                                <li class="submenu-item ">
                                    <a href="form-wbs.php">ระบุองค์ประกอบหมายเลขงาน WBS</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-employees.php">ผู้ควบคุมงาน</a>
                                </li>
                                <li class="submenu-item  ">
                                    <a href="form-center.php">แต่งตั้งคณะกรรมการราคากลาง</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-check.php">แต่งตั้งคณะกรรมการตรวจรับพัสดุ</a>
                                </li>
                                <li class="submenu-item ">
                                    <a href="form-price.php">ใบเสนอราคา</a>
                                </li>
                                <li class="submenu-item active">
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
                                <?php if (isset($row3["Address"])) 
                                        { if(isset($row3["Employee"]))
                                            { ?>
                                            <li class="submenu-item ">
                                            <a href="../PHPWord/WordA.php?create=<?= $id; ?>" target="_blank">รายงานขอจ้าง</a>
                                            </li>
                                <?php 
                                                if (isset($row3["Rank_C_C"]))
                                                { if (isset($row3["Rank_C_Check"])) 
                                                   {?>
                                                    <li class="submenu-item ">
                                                    <a href="../PHPWord/WordB.php?create=<?= $id; ?>" target="_blank">ขออนุมัติกำหนดราคากลาง</a>
                                                    </li>      
                                                    <li class="submenu-item ">
                          <a href="../PHPWord/WordB_2.php?create=<?= $id; ?>" target="_blank">แบบฟอร์มคำนวณราคากลาง</a>
                        </li>              
                                        <?php       
                                                        if (isset($row3["Vender_List"])){ ?>
                                                        
                                                        <li class="submenu-item ">
                                                        <a href="../PHPWord/WordC.php?create=<?= $id; ?>" target="_blank">รายงานผลการพิจารณาและขออนุมัติสั่งจ้าง</a>
                                                        </li>
                                                        <li class="submenu-item ">
                                                        <a href="../PHPWord/WordD.php?create=<?= $id; ?>" target="_blank">ใบขอเสนอซื้อ/จ้าง</a>
                                                        </li>

                                        <?php           if (isset($rowcon["ContractNo"])){ ?>
                                                            <li class="submenu-item ">
                                                            <a href="../PHPWord/WordE.php?create=<?= $id; ?>" target="_blank">ขออนุมัติวางเงินประกัน</a>
                                                            </li>
                                                            <li class="submenu-item ">
                                                                <a href="pdfH.php?create=<?= $id; ?>" target="_blank">สัญญาจ้างเหมา</a>
                                                            </li>
                                                            <li class="submenu-item ">
                                                                <a href="pdfI.php?create=<?= $id; ?>" target="_blank">แจ้งให้เข้าเริ่มดำเนินการ</a>
                                                            </li>
                                            <?php               if (isset($row3["po"])){ ?>
                                                                    <li class="submenu-item ">
                                                                    <a href="../PHPSpreadsheet/excel.php?create=<?= $id; ?>" target="_blank">จค 01</a>
                                                                    </li>
                                            <?php               }
                                                            }    
                                                        }
                                                    }
                                                }
                                            }
                                        }?>
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
                        <div class="col-12 col-md-9 order-md-1 order-last">
                            <h3>ข้อมูลผู้รับจ้าง</h3>
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
                                        <form class="form form-horizontal" action="addvender.php" method="POST">
                                            <div class="form-body">
                                                <div class="row">
                                                    <div class="row col-md-6 ">
                                                        <ul class="list-unstyled mb-3">
                                                            <li class="d-inline-block me-2 mb-1 ">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="status"
                                                                        id="flexRadioDefault1" value="1" <?php if (isset($row2["status"])){if($row2["status"] == 1){echo "checked";}} ?>>
                                                                    <label class="form-check-label" for="flexRadioDefault1">
                                                                        บุคคลธรรมดา
                                                                    </label>
                                                                </div>
                                                            </li>
                                                            <li class="d-inline-block me-2 mb-1">
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="status"
                                                                        id="flexRadioDefault2" value="2" <?php if (isset($row2["status"])){if($row2["status"] == 2){echo "checked";}} ?>>
                                                                    <label class="form-check-label" for="flexRadioDefault2">
                                                                        นิติบุคคล
                                                                    </label>
                                                                </div>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>รหัสผู้ขาย(Vender List)</label>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                        <input type="text" onkeyup="serchvender('VENDER_LIST')" class="form-control" name="VENDER_LIST" placeholder="" value="<?php if (isset($row2["vdlist"])) {
                                                                                                                                    echo $row2["vdlist"];
                                                                                                                                  } ?>" required>
            <span id="search_result_VenderList" class="search_Check">
                                                        </div>
                                                        <div class="col-md-1">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>เลขประจำตัวผู้เสียภาษี</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="IDTAX" value="<?php if (isset($row2["idtax"])) {
                                                                                          echo $row2["idtax"];
                                                                                        } ?>"  >
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>ชื่อ</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                        <input type="text" onkeyup="serchvender('FNAME_VENDER')" class="form-control" name="FNAME_VENDER" placeholder="" value="<?php if (isset($row2["fname"])) {
                                                                                                                                      echo $row2["fname"];
                                                                                                                                    } ?>" required>
            <span id="search_result_FnameVender" class="search_Check">
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>นามสกุล</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="LNAME_VENDER" value="<?php if (isset($row2["lname"])) {
                                                                                                echo $row2["lname"];
                                                                                              } ?>"  >
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-3">
                                                            <label>ทะเบียนสมาชิก SME</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="text" class="form-control" name="SME" value="<?php if (isset($row2["sme"])) {
                                                                                        echo $row2["sme"];
                                                                                      } ?>" required >
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>ลงวันที่</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="date" class="form-control" name="DATE" value="<?php if (isset($row2["smedate"])) {
                                                                          echo $row2["smedate"];
                                                                        } ?>" required >
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label>ที่อยู่</label>
                                                        </div>
                                                        <div class="col-md-5 form-group">
                                                            <input type="text" class="form-control" name="ADDRESS" value="<?php if (isset($row2["address"])) {
                                                                                            echo $row2["address"];
                                                                                          } ?>" required >
                                                        </div>
                                                        <div class="col-md-3">
                                                            <label>เบอร์โทรศัพท์</label>
                                                        </div>
                                                        <div class="col-md-3 form-group">
                                                            <input type="number" class="form-control" name="TEL" value="<?php if (isset($row2["tel"])) {
                                                                                        echo $row2["tel"];
                                                                                      } ?>" required >
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-1">
                                                            <label>กลุ่มวัสดุ</label>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <input type="text" class="form-control" name="material" value="<?php if (isset($row3["material"])) {
                                                                                              echo $row3["material"];
                                                                                            } ?>" required >
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>รหัสบัญชี GL</label>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <input type="text" class="form-control" name="GL" value="<?php if (isset($row3["GL"])) {
                                                                                          echo $row3["GL"];
                                                                                        } ?>" required >
                                                        </div>
                                                        <div class="col-md-2">
                                                            <label>รับประกันงาน(วัน)</label>
                                                        </div>
                                                        <div class="col-md-2 form-group">
                                                            <input type="text" class="form-control" name="avouch" value="<?php if (isset($row3["avouch"])) {
                                                                                              echo $row3["avouch"];
                                                                                            } ?>" required >
                                                        </div>
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
            <script>
      //employees
      let dataEmployees = [];
      let dataVender = [];
      let datacheck = [];
      let checked;
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
              Array.prototype.forEach.call(els, function(el) {
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
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
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

        console.log(Data285Selected.ID);

        fetchdata285case(Data285Selected.ID);

        const els = document.getElementsByClassName('search_result');
        // document.getElementById('search_result_id').innerHTML = '';
        Array.prototype.forEach.call(els, function(el) {
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
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
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
        Array.prototype.forEach.call(els, function(el) {
          el.innerHTML = '';
        });


      }




      //////////////////////////////////////////////////////////////////////////////////////////////////////////////

      function serchcheck(type) {
        switch (type) {
          case "FName_Chairman_Center_Price":

            //request  -> find employee form id
            checked = 1;
            const CheckName = document.getElementsByName('FName_Chairman_Center_Price')[0].value;
            console.log(CheckName);
            if (CheckName.length >= 4) {
              fetchCheckByName(CheckName);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;

          case "FName_Director_1":
            //request  -> find employee form id
            checked = 2;
            const CheckName2 = document.getElementsByName('FName_Director_1')[0].value;
            console.log(CheckName2);
            if (CheckName2.length >= 4) {
              fetchCheckByName(CheckName2);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;

          case "FName_Director_2":
            //request  -> find employee form id
            checked = 3;
            const CheckName3 = document.getElementsByName('FName_Director_2')[0].value;
            console.log(CheckName3);
            if (CheckName3.length >= 4) {
              fetchCheckByName(CheckName3);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;

          case "FName_Chairman_Check":
            //request  -> find employee form id
            checked = 4;
            const CheckName4 = document.getElementsByName('FName_Chairman_Check')[0].value;
            console.log(CheckName4);
            if (CheckName4.length >= 4) {
              fetchCheckByName(CheckName4);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;

          case "FName_Director_Check1":
            //request  -> find employee form id
            checked = 5;
            const CheckName5 = document.getElementsByName('FName_Director_Check1')[0].value;
            console.log(CheckName5);
            if (CheckName5.length >= 4) {
              fetchCheckByName(CheckName5);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;

          case "FName_Director_Check2":
            //request  -> find employee form id
            checked = 6;
            const CheckName6 = document.getElementsByName('FName_Director_Check2')[0].value;
            console.log(CheckName6);
            if (CheckName6.length >= 4) {
              fetchCheckByName(CheckName6);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;

          case "ContractFname":
            //request  -> find employee form id
            checked = 7;
            const CheckName7 = document.getElementsByName('ContractFname')[0].value;
            console.log(CheckName7);
            if (CheckName7.length >= 4) {
              fetchCheckByName(CheckName7);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;


          default:
            console.log("default");
            break;
        }

      }

      const fetchCheckByName = (CheckName) => {

        fetch("service/checkname.php", { //ส่ง id เพื่อไป getdata employee
          method: "POST",
          body: JSON.stringify({ //encode json
            CheckName: CheckName
          }),
          headers
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
          console.log({
            responseData
          });
          const html = autocompleteCheck(responseData);
          if (html != null) {
            if (checked == 1) {
              document.getElementById('search_result_CheckName').innerHTML = html;
            } else if (checked == 2) {
              document.getElementById('search_result_CheckName2').innerHTML = html;
            } else if (checked == 3) {
              document.getElementById('search_result_CheckName3').innerHTML = html;
            } else if (checked == 4) {
              document.getElementById('search_result_CheckName4').innerHTML = html;
            } else if (checked == 5) {
              document.getElementById('search_result_CheckName5').innerHTML = html;
            } else if (checked == 6) {
              document.getElementById('search_result_CheckName6').innerHTML = html;
            } else if (checked == 7) {
              document.getElementById('search_result_CheckName7').innerHTML = html;
            }

          }
        });
      };

      const autocompleteCheck = (Check) => {
        let html = null;
        if (Check.length > 0) {
          datacheck = Check;

          html = '<ul class="list-group">';


          for (let count = 0; count < Check.length; count++) {

            html += '<li class="list-group-item text-muted" style="cursor:pointer"  onclick="selectCheck(' + count + ')"><i class="fas fa-history mr-3"></i><span>' + Check[count].fname + '&nbsp;' + Check[count].lname + '</span> <i class="far fa-trash-alt float-right mt-1" ></i></li>';

          }
          html += '</ul>';
        }
        return html
      }

      function selectCheck(key) {
        const CheckSelected = datacheck[key];

        if (checked == 1) {
          document.getElementsByName('FName_Chairman_Center_Price')[0].value = CheckSelected.fname
          document.getElementsByName('Lname_Chairman_Center_Price')[0].value = CheckSelected.lname
          document.getElementsByName('Rank_C_C')[0].value = CheckSelected.rank
        } else if (checked == 2) {
          document.getElementsByName('FName_Director_1')[0].value = CheckSelected.fname
          document.getElementsByName('LName_Director_1')[0].value = CheckSelected.lname
          document.getElementsByName('Rank_D_C1')[0].value = CheckSelected.rank
        } else if (checked == 3) {
          document.getElementsByName('FName_Director_2')[0].value = CheckSelected.fname
          document.getElementsByName('LName_Director_2')[0].value = CheckSelected.lname
          document.getElementsByName('Rank_D_C2')[0].value = CheckSelected.rank
        } else if (checked == 4) {
          document.getElementsByName('FName_Chairman_Check')[0].value = CheckSelected.fname
          document.getElementsByName('LName_Chairman_Check')[0].value = CheckSelected.lname
          document.getElementsByName('Rank_C_Check')[0].value = CheckSelected.rank
        } else if (checked == 5) {
          document.getElementsByName('FName_Director_Check1')[0].value = CheckSelected.fname
          document.getElementsByName('LName_Director_Check1')[0].value = CheckSelected.lname
          document.getElementsByName('Rank_D_Check1')[0].value = CheckSelected.rank
        } else if (checked == 6) {
          document.getElementsByName('FName_Director_Check2')[0].value = CheckSelected.fname
          document.getElementsByName('LName_Director_Check2')[0].value = CheckSelected.lname
          document.getElementsByName('Rank_D_Check2')[0].value = CheckSelected.rank
        } else if (checked == 7) {
          document.getElementsByName('ContractFname')[0].value = CheckSelected.fname
          document.getElementsByName('ContractLname')[0].value = CheckSelected.lname
          document.getElementsByName('ContractUnder')[0].value = CheckSelected.rank
        }



        const els = document.getElementsByClassName('search_Check');
        // document.getElementById('search_result_id').innerHTML = '';
        Array.prototype.forEach.call(els, function(el) {
          el.innerHTML = '';
        });


      }


      ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

      function serchvender(type) {
        switch (type) {
          case "FNAME_VENDER":

            //request  -> find employee form id
            const VenderName = document.getElementsByName('FNAME_VENDER')[0].value;
            if (VenderName.length >= 4) {
              fetchVenderByName(VenderName);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;
          case "VENDER_LIST":
            const VenderList = document.getElementsByName('VENDER_LIST')[0].value;
            if (VenderList.length >= 4) {
              fetchVenderByList(VenderList);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;
          default:
            console.log("default");
            break;
        }
      }

      const fetchVenderByList = (VenderList) => {

        fetch("service/vender.php", { //ส่ง id เพื่อไป getdata employee
          method: "POST",
          body: JSON.stringify({ //encode json
            VenderList: VenderList
          }),
          headers
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
          console.log({
            responseData
          });
          const html = autocompleteVender(responseData);
          if (html != null) {
            document.getElementById('search_result_VenderList').innerHTML = html;
          }
        });
      };

      const fetchVenderByName = (FnameVender) => {

        fetch("service/vender.php", { //ส่ง id เพื่อไป getdata employee
          method: "POST",
          body: JSON.stringify({ //encode json
            FnameVender: FnameVender
          }),
          headers
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
          console.log({
            responseData
          });
          const html = autocompleteVender(responseData);
          if (html != null) {
            document.getElementById('search_result_FnameVender').innerHTML = html;
          }
        });
      };


      const autocompleteVender = (Vender) => {
        let html = null;
        if (Vender.length > 0) {
          dataVender = Vender;

          html = '<ul class="list-group">';


          for (let count = 0; count < Vender.length; count++) {

            html += '<li class="list-group-item text-muted" style="cursor:pointer"  onclick="selectVender(' + count + ')"><i class="fas fa-history mr-3"></i><span>' + Vender[count].fname + '&nbsp;' + Vender[count].lname + '</span> <i class="far fa-trash-alt float-right mt-1" ></i></li>';

          }
          html += '</ul>';
        }
        return html
      }



      function selectVender(key) {
        const VenderSelected = dataVender[key];

        document.getElementsByName('FNAME_VENDER')[0].value = VenderSelected.fname
        document.getElementsByName('LNAME_VENDER')[0].value = VenderSelected.lname
        document.getElementsByName('IDTAX')[0].value = VenderSelected.idtax
        document.getElementsByName('VENDER_LIST')[0].value = VenderSelected.vdlist
        document.getElementsByName('SME')[0].value = VenderSelected.sme
        document.getElementsByName('DATE')[0].value = VenderSelected.smedate
        document.getElementsByName('ADDRESS')[0].value = VenderSelected.address
        document.getElementsByName('TEL')[0].value = VenderSelected.tel

        const els = document.getElementsByClassName('search_Check');
        // document.getElementById('search_result_id').innerHTML = '';
        Array.prototype.forEach.call(els, function(el) {
          el.innerHTML = '';
        });


      }



      /////////////////////////////////////////////////////////////////////////////////////////////////////////

      function serchEmployee(type) {
        switch (type) {
          case "ID_EMPLOYEE":
            //request  -> find employee form id
            const employeeId = document.getElementsByName('ID_EMPLOYEE')[0].value;
            if (employeeId.length >= 4) {
              fetchEmployeById(employeeId);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }

            break;
          case "FNAME":
            const fName = document.getElementsByName('FNAME')[0].value;
            if (fName.length >= 4) {
              fetchEmployeByFName(fName);
            } else {
              const els = document.getElementsByClassName('search_Check');
              // document.getElementById('search_result_id').innerHTML = '';
              Array.prototype.forEach.call(els, function(el) {
                el.innerHTML = '';
              });
            }
            break;
          default:
            console.log("default");
            break;
        }
      }



      const fetchEmployeByFName = (fName) => {

        fetch("service/employee.php", { //ส่ง id เพื่อไป getdata employee
          method: "POST",
          body: JSON.stringify({ //encode json
            fName: fName
          }),
          headers
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
          console.log({
            responseData
          });
          const html = selectVender(responseData);
          if (html != null) {
            document.getElementById('search_result_fname').innerHTML = html;
          }
        });
      };


      const fetchEmployeById = (id) => {
        console.log({
          id
        });
        fetch("service/employee.php", { //ส่ง id เพื่อไป getdata employee
          method: "POST",
          body: JSON.stringify({ //encode json
            id
          }),
          headers
        }).then(function(response) {

          return response.json();

        }).then(function(responseData) {
          console.log({
            responseData
          });

          const html = autocomplete(responseData);
          if (html != null) {
            document.getElementById('search_result_id').innerHTML = html;
          }

        });
      };

      const autocomplete = (employees) => {
        let html = null;
        if (employees.length > 0) {
          dataEmployees = employees;

          html = '<ul class="list-group">';


          for (let count = 0; count < employees.length; count++) {

            html += '<li class="list-group-item text-muted" style="cursor:pointer"  onclick="selectEmployee(' + count + ')"><i class="fas fa-history mr-3"></i><span>' + employees[count].Fname + '&nbsp;' + employees[count].Lname + '</span> <i class="far fa-trash-alt float-right mt-1" ></i></li>';

          }
          html += '</ul>';
        }
        return html
      }

      function selectEmployee(key) {
        const employeeSelected = dataEmployees[key];

        document.getElementsByName('ID_EMPLOYEE')[0].value = employeeSelected.ID
        document.getElementsByName('FNAME')[0].value = employeeSelected.Fname
        document.getElementsByName('LNAME')[0].value = employeeSelected.Lname
        document.getElementsByName('RANK')[0].value = employeeSelected.Rank
        document.getElementsByName('DEPARTMENT')[0].value = employeeSelected.Under
        document.getElementsByName('FULLNAME')[0].value = employeeSelected.Department
        document.getElementsByName('pea')[0].value = employeeSelected.pea
        document.getElementsByName('county')[0].value = employeeSelected.county
        document.getElementsByName('TEL')[0].value = employeeSelected.phone

        const els = document.getElementsByClassName('search_result');
        // document.getElementById('search_result_id').innerHTML = '';
        Array.prototype.forEach.call(els, function(el) {
          el.innerHTML = '';
        });


      }
    </script>

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
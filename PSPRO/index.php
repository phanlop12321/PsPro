<?php

session_start();

if (!$_SESSION["UserID"]) { //check session

    Header("Location: auth-login.html"); //ไม่พบผู้ใช้กระโดดกลับไปหน้า login form 

}
include('connection.php');

$user = $_SESSION["User"];

$sql = "SELECT * FROM data285 WHERE user = '$user' ";
$result = $conn->query($sql);
$count = $conn->query($sql);
$i = 0;
$sql1 = "SELECT * FROM data285 WHERE user = '$user' ORDER BY id DESC";
$result1 = $conn->query($sql1);
if ($result1->num_rows > 0) {
    $id = $result1->fetch_assoc();

    //echo $id["id"]; 
    $i = $id["id"] + 1;
} else {
    $i = rand(0, 100000);
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

    <link rel="stylesheet" href="assets/vendors/simple-datatables/style.css">

    <link rel="stylesheet" href="assets/vendors/perfect-scrollbar/perfect-scrollbar.css">
    <link rel="stylesheet" href="assets/vendors/bootstrap-icons/bootstrap-icons.css">
    <link rel="stylesheet" href="assets/css/app.css">
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

                        <li class="sidebar-item active ">
                            <a href="index.php" class='sidebar-link'>
                                <i class="bi bi-grid-fill"></i>
                                <span>หน้าหลัก</span>
                            </a>
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
                        <div class="col-6 col-md-6 order-md-1 order-last">
                            <h3>โปรแกรมจ้างเหมาก่อสร้างระบบไฟฟ้า</h3>
                            <p class="text-subtitle text-muted">Power System Procurement and Construction Program</p>
                        </div>
                        <div class="col-6 col-md-6 order-md-1 order-last">

                        </div>
                    </div>
                </div>
                <section class="section">
                    <div class="card">
                        <div class="card-header">
                            <div class="row">
                                <div class="col-md-3">
                                    <a href="form-work.php?create=<?= $i; ?>"
                                        class="btn btn-outline-success">เพิ่มงาน</a>
                                </div>
                                <div class="col-md-6"></div>
                                <div class="col-md-3 d-flex justify-content-end">
                                    <a href="logout.php" class="btn btn-outline-warning "
                                        onclick="return confirm('ยืนยันการออกจากระบบ !!');">ออกจากระบบ</a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped" id="table1">
                                <thead>
                                    <tr>
                                        <th>ลำดับที่</th>
                                        <th>ชื่องาน</th>
                                        <th>งบ</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $i = 0;
                                    if ($result->num_rows > 0) {
                                        // output data of each row
                                        while ($row = $result->fetch_assoc()) {
                                            $i = $i + 1; ?>

                                            <tr>
                                                <td>
                                                    <?= $i; ?>
                                                </td>
                                                <td><a href="form-work.php?create=<?= $row["id"]; ?>" class="alert-link"><?= $row["Name"]; ?></a></td>
                                                <td>
                                                    <?= $row["Type_Budget"]; ?>
                                                </td>
                                                <td><a href="delindex.php?id=<?= $row['id']; ?>&user=<?php echo $user; ?>"
                                                        style="color: red; "
                                                        onclick="return confirm('ยืนยันการลบข้อมูล !!');"><i
                                                            class="bi bi-trash-fill"></i></a></td>
                                            </tr>
                                        <?php }
                                    } ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </section>
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

    <script src="assets/vendors/simple-datatables/simple-datatables.js"></script>
    <script>
        // Simple Datatable
        let table1 = document.querySelector('#table1');
        let dataTable = new simpleDatatables.DataTable(table1);
    </script>

    <script src="assets/js/main.js"></script>
</body>

</html>
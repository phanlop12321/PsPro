<?php

?>
<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>PS Pro</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
</head>

<body>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe"
    crossorigin="anonymous"></script>

  <nav class="navbar" style="background-color: #e3f2fd;">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">PS Pro Ver.2</a>
    </div>
  </nav>
  <br>
  <div class="container">
    <div class="row justify-content-md-center">

      <div class="col-md-auto">
        <form name="form1" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" enctype="multipart/form-data">
          <div class="row">
            <div class="col-md-9">
              <input class="form-control" type="file" name="filUpload" accept="application/vnd.ms-excel" multiple>
            </div>
            <div class="col-md-3">
              <button id="fileSelect" class="btn btn-outline-primary" name="submit">ตกลง</button>
            </div>
          </div>
        </form>
      </div>
      <div class="card-body">

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
            //นับว่า sheet แรกมีทั้งหมดกี่แถวแล้วเก็บจำนวนแถวไว้ในตัวแปรชื่อ $lastRow
            ?>
            <br>
            <table class="table table-striped">

              <?php


              // output data of each row
              for ($row = 1; $row <= $lastRow; $row++) //วน loop อ่านข้อมูลเอามาแสดงทีละแถว
              {

                ?>
                <tr>
                  <td>
                    <?= $worksheet->getCell('F' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('G' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('H' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('I' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('J' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('K' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('L' . $row)->getValue(); ?>
                  </td>
                  <td>
                    <?= $worksheet->getCell('M' . $row)->getValue(); ?>
                  </td>
                </tr>
                <?php
              }
              ?>
            </table>
            <?php
          }
        }
        ?>



      </div>
      <div>

      </div>


    </div>

  </div>

</body>


</html>
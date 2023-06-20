<?php

use PhpParser\Node\Stmt\Echo_;

session_start();
        if(isset($_POST['Username'])){
				//connection
                  include("connection.php");
				//รับค่า user & password
                  $Username = $conn -> real_escape_string($_POST['Username']);
                  //$Username = $_POST['Username'];
                  $Password = md5($conn -> real_escape_string($_POST['Password']));
                 // $Password = md5($_POST['Password']);
				//query 
                  $sql="SELECT * FROM user Where Username='".$Username."' and Password='".$Password."' ";

                  $result = mysqli_query($conn,$sql);
				
                  if(mysqli_num_rows($result)==1){

                      $row = mysqli_fetch_array($result);

                      $_SESSION["UserID"] = $row["ID"];
                      $_SESSION["User"] = $row["Username"];
                      $_SESSION["Userlevel"] = $row["Userlevel"];
                      $_SESSION["Depratment"] = $row["Depratment"];
                      	

                      if($_SESSION["Userlevel"]){ //ถ้าเป็น admin ให้กระโดดไปหน้า admin_page.php


                        Header("Location: index.php");

                      }

                  }else{
                    echo "<script>";
                        echo "alert(\" user หรือ  password ไม่ถูกต้อง\");"; 
                        echo "window.history.back()";
                    echo "</script>"; 

                  }

        }else{


             Header("Location: auth-login.html"); //user & password incorrect back to login again

        }
?>
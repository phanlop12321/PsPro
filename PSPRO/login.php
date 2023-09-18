<?php

use PhpParser\Node\Stmt\Echo_;

session_start();
if (isset($_POST['Username'])) {
  //connection
  include("connection.php");
  function utf8_strrev($str)
  {
    preg_match_all('/./us', $str, $ar);
    return join('', array_reverse($ar[0]));
  }
  function pass_encrypt($pass, $show = false)
  {
    //you secret word
    $key1 = '10923@cas)as.D_%s#';
    $key2 = '#knssd:=-*9jgf';
    $loop = 10;
    $reverse = utf8_strrev($pass);
    for ($i = 0; $i < $loop; $i++) {
      $md5 = md5($reverse);
      $reverse_md5 = utf8_strrev($md5);
      $salt = substr($reverse_md5, -17) . md5($key1) . substr($reverse_md5, 5, 24) . md5($key2);
      $new_md5 = md5($salt);
      $reverse = utf8_strrev($new_md5);
    }
    return md5($reverse);
  }
  //รับค่า user & password
  $Username = $conn->real_escape_string($_POST['Username']);
  $Password = $conn->real_escape_string($_POST['Password']);
  echo ($_POST['Username']);
  $sql = "SELECT * FROM user WHERE Username=$Username ";

  $result = mysqli_query($conn, $sql);

  if (mysqli_num_rows($result) == 1) {
    $row = mysqli_fetch_assoc($result);
    $pass_in_db = $row["Password"];
    if (password_verify(pass_encrypt($Password), $pass_in_db)) {
      $_SESSION["UserID"] = $row["ID"];
      $_SESSION["User"] = $row["Username"];
      $_SESSION["Userlevel"] = $row["Userlevel"];
      $_SESSION["Depratment"] = $row["Depratment"];
      echo "===";
      echo ($_SESSION["Userlevel"]);

      if ($_SESSION["Userlevel"]) { //ถ้าเป็น admin ให้กระโดดไปหน้า admin_page.php
        //  Header("Location: index.php");
      } else {
        //   Header("Location: auth-login.html"); //user & password incorrect back to login again
      }
    } else {
      //  Header("Location: auth-login.html"); //user & password incorrect back to login again
    }
  } else {
    echo ("can not findff");
    //  Header("Location: auth-login.html"); //user & password incorrect back to login again
  }
} else {
  // Header("Location: auth-login.html"); //user & password incorrect back to login again
}

?>
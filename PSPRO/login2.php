<?php

header('Content-Type: text/html; charset=utf-8');
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
  if ($show == true) {
    echo '<br> กลับตัวอักษร &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ', $reverse;
  }
  for ($i = 0; $i < $loop; $i++) {
    $md5 = md5($reverse);
    if ($show == true) {
      echo '<br> เข้ารหัสเป็น 32 หลัก : ', $md5;
    }
    $reverse_md5 = utf8_strrev($md5);
    if ($show == true) {
      echo '<br> กลับตัวอักษร &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ', $reverse_md5;
    }
    $salt = substr($reverse_md5, -17) . md5($key1) . substr($reverse_md5, 5, 24) . md5($key2);
    if ($show == true) {
      echo '<br> สร้างข้อความใหม่ &nbsp;&nbsp;&nbsp; : ', $salt;
    }
    $new_md5 = md5($salt);
    if ($show == true) {
      echo '<br> เข้ารหัสเป็น 32 หลัก : ', $new_md5;
    }
    $reverse = utf8_strrev($new_md5);
    if ($show == true) {
      echo '<br> กลับตัวอักษรอีกครั้ง &nbsp;: ', $reverse;
    }
  }
  return md5($reverse);
}
$pass = "499362";
echo '<br> md5() ธรรมดา &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : ', md5($pass);
//เข้ารหัส md5 ก่อน
$encrypt = pass_encrypt($pass, true);
// และเข้ารหัส hash เพื่อนำไปบันทึกลงฐานข้อมูล
$hash = password_hash($encrypt, PASSWORD_DEFAULT);
echo '<hr/>รหัสผ่าน : ' . $pass;
echo '<br/> ผลลัพธ์ : <b>' . $hash . '</b>';
echo '<br/>ความยาวของตัวอักษร : <b>', strlen($hash), '</b>';
//ข้อมูลทดสอบ
$pass_in_db = '$2y$10$E4dfRcnTvaqqLe0n9ath9OActuIz2.hxyJwhm8CqNJQuE6XLUsmpC'; // is $hash
$post_data = "499362";
echo '<hr/><br/>from <b>POST</b> = ' . $post_data;
echo '<br/>data in <b>DB</b> = ' . $pass_in_db;
echo '<br/><b>Md5</b> = ', md5($post_data);
if (password_verify(pass_encrypt($post_data), $pass_in_db)) {
  echo '<br/><br/><span style="color:green">Password is valid!</span>';
} else {
  echo '<br/><br/><span style="color:red">Invalid password.</span>';
}
?>

<br /><br />
<h3>Function Reference</h3>
<pre>
http://php.net/manual/en/function.strrev.php
http://php.net/manual/en/function.md5.php
http://php.net/manual/en/function.password-hash.php
http://php.net/manual/en/function.password-verify.php
</pre>

?>
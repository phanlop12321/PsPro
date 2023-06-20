<?php function utf8_strrev($str)
{
    preg_match_all('/./us', $str, $ar);
    return join('', array_reverse($ar[0]));
}
function pass_encrypt($pass, $show = true)
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
        echo "<BR>" . $reverse;
    }
    return md5($reverse);

}

$x = pass_encrypt(499362);
echo "<BR>" . $x;
?>
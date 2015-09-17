<?php
header("Content-Type: text/html; charset=UTF-8");

$database=SAE_MYSQL_DB;
$intervalSecs=60;
$conn=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
mysql_query("set character set 'utf8'");
mysql_query("set names set 'utf8'");
mysql_select_db(SAE_MYSQL_DB,$conn);

if(!isset($_GET['user']) || !isset($_GET['user'])){
    die('args error');
}

mysql_uery("INSERT INTO `RecitingWord` (`word`,`user`) VALUES ('".$_GET['word']."','".$_GET["user"]."'); ", $conn);

mysql_close($conn);
?>


<?php
header("Content-Type: text/html; charset=UTF-8");

$database=SAE_MYSQL_DB;
$conn=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
mysql_query("set character set 'utf8'");
mysql_query("set names set 'utf8'");
mysql_select_db(SAE_MYSQL_DB,$conn);

$page=0;
if(!isset($_GET['word'])){
    die('[]');
}
if(isset($_GET['page'])){
    $page=intval($_GET['page']);
}

$result=mysql_query("SELECT `word`, `desc`, `source` FROM `WordBase` WHERE `word` LIKE '".$_GET['word']."' LIMIT ".($page*10).",".($page*10+10)."; ", $conn);

//print "SELECT * FROM `WordBase` WHERE `word` IS LIKE '".$_GET['word']."' LIMIT ".($page*10).",".($page*10+10)."; ";

$array=array();
while($one=mysql_fetch_array($result,MYSQL_ASSOC)) $array[]=$one;

print json_encode($array);

mysql_close($conn);
?>


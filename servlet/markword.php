<?php
header("Content-Type: text/html; charset=UTF-8");

$intervalSecs=60;
$conn=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
mysql_query("set character set 'utf8'");
mysql_query("set names set 'utf8'");
mysql_select_db(SAE_MYSQL_DB,$conn);

if(!isset($_GET["user"])){
    die('no user error');
}

if(isset($_GET["user"]) && isset($_GET["word"]) && isset($_GET["status"])){
    $result=mysql_query("lock table RecitingWord write;", $conn);
    $result=mysql_query("SELECT `word`,`status` FROM `RecitingWord` WHERE user='".$_GET["user"]."' AND word='".$_GET["word"]."'; ", $conn);
    $status=intval(mysql_fetch_array($result));
    
    if($_GET["status"]=="0") $status=$status+1;
    if($_GET["status"]=="1") if($status<5) $status=$status+1; else $status=$status;
    if($_GET["status"]=="2") $status=$status;
    if($_GET["status"]=="3") $status=$status-1;
    
    $minstatus=-1;
    if($status<$minstatus) $status=$minstatus;
    
    $result=mysql_query("UPDATE `RecitingWord` SET `status`='".$status."',`lastRecite`='".date('Y-m-d H:i:s',time())."' WHERE user='".$_GET["user"]."' AND word='".$_GET["word"]."'; ", $conn);
    $result=mysql_query("unlock tables;", $conn);
}


$result=mysql_query("
SELECT `word`,`status`,`lastRecite` FROM `RecitingWord` 
WHERE status<10 and DATE_ADD(`lastRecite`, INTERVAL power(`status`,4) MINUTE)<now() and user='".$_GET["user"]."' 
ORDER BY `RecitingWord`.`RWID` ASC; ", $conn);

$array=array();
while($one=mysql_fetch_array($result)) $array[]=$one;

if(count($array)==0){
    print '{"word":"__EMPTY__"}';
}else{
    $i=0;
    //$i=rand(0,count($array)-1);
    $result=mysql_query("SELECT `word`,`desc` FROM `WordBase` WHERE word='".$array[$i][0]."'; ", $conn);
    $one=mysql_fetch_array($result);
    $word=$one[0];
    $desc=$one[1];
    print '{"word":"'.$word.'","desc":"'.$desc.'","Remainder":'.count($array).'}';
}

mysql_close($conn);
?>


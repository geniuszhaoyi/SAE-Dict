<?php
header("Content-Type: text/html; charset=UTF-8");

$intervalSecs=60;
$conn=mysql_connect(SAE_MYSQL_HOST_M.':'.SAE_MYSQL_PORT, SAE_MYSQL_USER, SAE_MYSQL_PASS);
mysql_query("set character set 'utf8'");
mysql_query("set names set 'utf8'");
mysql_select_db(SAE_MYSQL_DB,$conn);

if(!isset($_GET["word"])){
    die('no word error');
}

$result=mysql_query("SELECT `word`,`desc`,`MWXml` FROM `WordBase` WHERE word='".$_GET["word"]."'; ", $conn);
$one=mysql_fetch_array($result);
$word=$one[0];
$desc=$one[1];
$MWXml=base64_decode($one[2]);

if($MWXml==''){
    $key="73453d0d-497a-45a9-b625-e309e7db05bf";
    $uri="http://www.dictionaryapi.com/api/v1/references/collegiate/xml/".urlencode($word)."?key=".urlencode($key);
	$MWXml=file_get_contents($uri);
	mysql_query("UPDATE `WordBase` SET `MWXml`='".base64_encode($MWXml)."' WHERE `word`='".$word."'; ", $conn);
}

print $MWXml;

mysql_close($conn);
?>


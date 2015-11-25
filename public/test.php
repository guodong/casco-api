<?php
	$a = array(1,2,array("a","b"));
	var_export($a);
	echo "<hr>";
	$v = var_export($a,true);
	echo $v."<br/>";

	$a1 = "hello";
	$a2 = "world!";
	echo "{$a1} {$a2}s <br/>";
?>

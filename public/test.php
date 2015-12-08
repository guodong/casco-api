<?php
 $curlPost='pcXEVfZGF0YVxc1b3C1L%2FNu6fX1Nb6z7XNs8341b5cXHd3d3Jvb3RcXA%3D%3D';
 
 $curlPost='wpp='.urlencode($curlPost);
//$curlPost= file_get_contents("php://input"); 
//echo $curlPost;exit;
$opts = array (
'http' => array (
'method' => 'POST',
'Referer'=>'http://vip.ufida.com.cn',
'header'=> "Content-Type: application/x-www-form-urlencoded
User-Agent:Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)
Host:localhost
Connection:Close
Cache-Control:no-cache
Content-Length:".strlen($curlPost),
'content' => $curlPost
)
);
$context = stream_context_create($opts);
$html = file_get_contents('http://localhost:38823/default.aspx', false, $context);
echo $html;
?>
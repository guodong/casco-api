<?php 
    $html = "<b>bold text</b><a href=baidu.com>click me</a>";
    preg_match_all("/(<([w]+)[^>]*>)(.*)(<\/>)/", $html,$matches,PREG_OFFSET_CAPTURE);
    echo 1;
    for($i=0;$i<count($matches[0]);$i++){
        echo $i;
        echo "matched:" .$matches[0][$i]."\n";
        echo "part 1:" .$matches[1][$i]."\n";
        echo "part 2:" .$matches[2][$i]."\n";
        echo "part 3:" .$matches[3][$i]."\n";
    }
   
?>

<?php
$counter = "./counter_data.txt";
// Log visit;
$fp = fopen($counter, "a");
$line = $REMOTE_ADDR . "|" . "\n";
$size = strlen($line);
fputs($fp, $line, $size);
fclose($fp);
$contents = file($counter);
$total_hits = sizeof($contents);
$total_hosts = array();
for ($i=0;$i<sizeof($contents);$i++) {
$entry = explode("|", $contents[$i]);
array_push($total_hosts, $entry[0]);
}
$total_hosts_size = sizeof(array_unique($total_hosts));
echo "<div class=\"iblock\"><center>This Page visted: " . $total_hits . " Times</center></div>";
    
    /*** 
    Now it just include this file to anywhere to show the counter. you can use the code <? include"count.php"; ?> 
    ***/
    
    
?>
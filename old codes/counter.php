<?php
	$ip = $_SERVER['REMOTE_ADDR'];
	
	$file_ip = fopen('ip.db', 'rb');
	while (!feof($file_ip)) $line[]=fgets($file_ip,1024);
	for ($i=0; $i<(count($line)); $i++) {
		list($ip_x) = split("\n",$line[$i]);
		if ($ip == $ip_x) {$found = 1;}
	}
	fclose($file_ip);
	
	if (!($found==1)) {
		$file_ip2 = fopen('ip.db', 'ab');
		$line = "$ip\n";
		fwrite($file_ip2, $line, strlen($line));

$counter = "./count.db";
$today = getdate();
$month = $today[month];
$mday = $today[mday];
$year = $today[year];
$current_date = $mday . $month . $year;
// Log visit;
$fp = fopen($counter, "a");
$line = $REMOTE_ADDR . "%" . $mday . $month . $year . "\n";
$size = strlen($line);
fputs($fp, $line, $size);
fclose($fp);
$contents = file($counter);
$total_hits = sizeof($contents);
$total_hosts = array();
for ($i=0;$i<sizeof($contents);$i++) {
$entry = explode("%", $contents[$i]);
array_push($total_hosts, $entry[0]);
}
$total_hosts_size = sizeof(array_unique($total_hosts));
$daily_hits = array();
for ($i=0;$i<sizeof($contents);$i++) {
$entry = explode("%", $contents[$i]);
if ($current_date == chop($entry[1])) {
array_push($daily_hits, $entry[0]);
}
}
$daily_hits_size = sizeof($daily_hits);
$daily_hosts = array();
for ($i=0;$i<sizeof($contents);$i++) {
$entry = explode("%", $contents[$i]);
if ($current_date == chop($entry[1])) {
array_push($daily_hosts, $entry[0]);
}
}
$daily_hosts_size = sizeof(array_unique($daily_hosts));
}

echo "<br/><b>Visits: " . $daily_hits_size . "</b></br>
<b>Active connections: " . $total_hits . "</b>";
?>
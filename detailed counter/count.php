<?php
$ip = $_SERVER['REMOTE_ADDR']; // Use $_SERVER for IP address retrieval

$file_ip = fopen('ip.db', 'rb');
if ($file_ip) {
    $line = []; // Properly initialize the $line array
    while (!feof($file_ip)) {
        $line[] = fgets($file_ip, 1024);
    }
    
    // Replace deprecated 'split()' with 'explode()'
    $found = 0;
    for ($i = 0; $i < count($line); $i++) {
        list($ip_x) = explode("\n", $line[$i]);
        if ($ip == $ip_x) {
            $found = 1;
            break;
        }
    }
    fclose($file_ip);
} else {
    // Handle the error in case the file could not be opened
    die('Could not open IP database file.');
}

if (!$found) {
    // Append the new IP if not found
    $file_ip2 = fopen('ip.db', 'ab');
    if ($file_ip2) {
        $line = "$ip\n";
        fwrite($file_ip2, $line, strlen($line));
        fclose($file_ip2);
    } else {
        // Handle the error in case the file could not be opened
        die('Could not open IP database file for writing.');
    }

    // Counter functionality
    $counter = "./count.db";
    $today = getdate();
    $month = $today['month'];
    $mday = $today['mday'];
    $year = $today['year'];
    $current_date = $mday . $month . $year;

    // Log visit
    $fp = fopen($counter, "a");
    if ($fp) {
        $line = $_SERVER['REMOTE_ADDR'] . "%" . $mday . $month . $year . "\n";
        fputs($fp, $line, strlen($line));
        fclose($fp);
    } else {
        // Handle error if count.db cannot be opened
        die('Could not open count.db for logging.');
    }

    // Read contents and calculate totals
    $contents = file($counter);
    $total_hits = count($contents);

    $total_hosts = [];
    foreach ($contents as $entry) {
        $data = explode("%", $entry);
        $total_hosts[] = $data[0];
    }
    $total_hosts_size = count(array_unique($total_hosts));

    // Calculate daily hits
    $daily_hits = [];
    foreach ($contents as $entry) {
        $data = explode("%", $entry);
        if (trim($data[1]) == $current_date) {
            $daily_hits[] = $data[0];
        }
    }
    $daily_hits_size = count($daily_hits);

    // Calculate daily unique hosts
    $daily_hosts = [];
    foreach ($contents as $entry) {
        $data = explode("%", $entry);
        if (trim($data[1]) == $current_date) {
            $daily_hosts[] = $data[0];
        }
    }
    $daily_hosts_size = count(array_unique($daily_hosts));
}
?>

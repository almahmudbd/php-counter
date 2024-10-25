<?php
$ip = $_SERVER['REMOTE_ADDR'];

// Function to delete old IP logs older than one month
function cleanupOldLogs() {
    $ip_file = 'ip.db';
    $lines = file($ip_file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    $current_time = time();

    $new_lines = [];
    foreach ($lines as $line) {
        // Check if the log is older than one month
        if ($current_time - strtotime($line) <= 2592000) { // 30 days in seconds
            $new_lines[] = $line;
        }
    }

    file_put_contents($ip_file, implode("\n", $new_lines) . "\n"); // Rewrite the file with updated logs
}

// Function to log visits to the page
function logPageVisit($page) {
    $page_summary_file = './page_summary.db';
    $page_summary_data = [];

    // Load existing data
    if (file_exists($page_summary_file)) {
        $page_summary_data = json_decode(file_get_contents($page_summary_file), true);
    }

    // Initialize count for the specific page if not present
    if (!isset($page_summary_data[$page])) {
        $page_summary_data[$page] = 0;
    }

    // Increment visit count for the page
    $page_summary_data[$page]++;

    // Write updated data back to the file
    file_put_contents($page_summary_file, json_encode($page_summary_data));
}

// Cleanup old logs
cleanupOldLogs();

$file_ip = fopen('ip.db', 'rb');
$found = false; // Initialize the found variable
$line = [];

while (!feof($file_ip)) {
    $line[] = fgets($file_ip, 1024);
}

foreach ($line as $ip_x) {
    if (trim($ip) === trim($ip_x)) {
        $found = true; // Use trim to compare without whitespace
        break;
    }
}
fclose($file_ip);

if (!$found) {
    $file_ip2 = fopen('ip.db', 'ab');
    $line = date("Y-m-d H:i:s") . " - $ip\n"; // Log IP with timestamp
    fwrite($file_ip2, $line);
    fclose($file_ip2); // Close the file after writing

    $counter = "./count.db";
    $today = getdate();
    $month = $today['mon']; // Correct array key usage
    $mday = $today['mday'];
    $year = $today['year'];
    $current_date = $mday . $month . $year;

    // Log visit
    $summary_file = './summary.db';
    $summary_data = [];

    if (file_exists($summary_file)) {
        $summary_data = json_decode(file_get_contents($summary_file), true);
    }

    // Update daily visit count
    if (!isset($summary_data[$current_date])) {
        $summary_data[$current_date] = 0;
    }
    $summary_data[$current_date]++;

    // Write summary data back to file
    file_put_contents($summary_file, json_encode($summary_data));
}

// Log the visit for this specific page
logPageVisit('your_page_identifier'); // Replace with actual page identifier

// Reading the visit data
$contents = file($counter);
$total_visits = count($contents); // Total site visits

// Update total users for the current month
$total_users = count(array_unique(array_map('trim', array_column(array_map(function($entry) {
    return explode(" - ", $entry);
}, $line), 1)))); // Count unique IPs from the log

// Get today's visits from summary
$current_date_visits = $summary_data[$current_date] ?? 0; // Today's visits
$total_hosts_size = count(array_unique(array_map('trim', array_column(array_map(function($entry) {
    return explode(" - ", $entry);
}, $line), 1)))); // Total active connections

// Read the page visit summary for the last month
$page_summary_data = json_decode(file_get_contents('./page_summary.db'), true);
echo "<b>This Month's Users:</b> " . $total_users . "<br>
<b>Total Site Visits:</b> " . $total_visits . "<br>
<b>Today's Visits:</b> " . $current_date_visits . "<br>
<b>Active Connections:</b> " . $total_hosts_size . ";<br>";

echo "<b>Page Visits in Last Month:</b><br>";
foreach ($page_summary_data as $page => $count) {
    echo "Page: $page - Visits: $count<br>";
}


   /*** 
    Now it just include this file to anywhere to show the counter. you can use the code <? include"full_counter.php"; ?> 
	Feel free to edit code.
    ***/
?>

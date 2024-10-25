<?php
// Define the file to store the total visit count
$counter_file = 'total_site_counter.db';

// Check if the file exists; if not, create it and initialize the counter
if (!file_exists($counter_file)) {
    $fp = fopen($counter_file, 'w');
    fwrite($fp, "0"); // Start the counter at 0
    fclose($fp);
}

// Open the counter file and read the current count
$fp = fopen($counter_file, 'r+');
$current_count = (int)fread($fp, filesize($counter_file));

// Increment the counter by 1
$current_count++;

// Move the file pointer to the beginning and update the counter value
fseek($fp, 0);
fwrite($fp, $current_count);

// Close the file
fclose($fp);

// Set options for displaying counters
$show_site_total = 'yes'; // Change to 'no' to hide total site visits
$show_page_visit_count = 'yes'; // Change to 'no' to hide page visit count

// Display the total visit count for the site if the option is set
if ($show_site_total === 'yes') {
    echo "<div class=\"iblock\"><center>Total site visits: " . $current_count . " </center></div>";
}

// Page visit tracking
if ($show_page_visit_count === 'yes') {
    $counter = "./page_counter_data.db"; 
    $page_name = $_SERVER['REQUEST_URI']; // Track the current page name or URL

    // Read the current summary data
    $page_summary_data = [];
    if (file_exists($counter)) {
        $page_summary_data = json_decode(file_get_contents($counter), true);
    }

    // Increment the visit count for the current page
    if (isset($page_summary_data[$page_name])) {
        $page_summary_data[$page_name]++;
    } else {
        $page_summary_data[$page_name] = 1;
    }

    // Save the updated summary back to the file
    file_put_contents($counter, json_encode($page_summary_data));

    // Display the visit count for the current page
    $page_hits = $page_summary_data[$page_name];
    $total_hits = array_sum($page_summary_data); // Total hits for all pages

    // Output total visits for the site and the current page
    echo "<div class=\"counter\"><center>This Page visited: " . $page_hits . " times</center></div>";
    echo "<div class=\"counter\"><center>Total site visited: " . $total_hits . " times</center></div>";
}


   /*** 
    Now it just include this file to anywhere to show the counter. you can use the code <? include"total_counter.php"; ?> 
	Feel free to edit code.
    ***/
	
?>

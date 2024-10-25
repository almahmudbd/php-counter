# PHP Visit Counter Scripts

## Overview
This repository contains two PHP scripts for tracking and displaying visit metrics on your website. The scripts help you monitor total site visits and visits to individual pages.

### 1. Full Counter Script (`full_counter.php`)
This script tracks unique visitors and logs visits to each page. It displays:
- This month's unique users
- Total site visits
- Today's visits
- Active connections
- Page visits over the last month

### 2. Total Visit Counter Script (`total_counter.php`)
This script tracks total visits for the site and individual pages. It includes:
- Total site visits
- Visits for the current page

## How to Use
1. Download the scripts to your server.
2. Ensure the server has write permissions for the database files:
   - `ip.db`: Logs unique visitors.
   - `count.db`: Tracks total visits.
   - `page_summary.db`: Stores page visit data.

3. Include the scripts in your web pages:
   ```php
   <?php include("full_counter.php"); ?>
   <?php include("total_counter.php"); ?>
   ```

## Display Options
Both scripts allow you to customize which metrics to show:
- For `total_counter.php`, you can choose to display:
  - Total site visits
  - Page visit counts
- Change the variables at the top of the script:
  ```php
  $show_site_total = 'yes'; // Change to 'no' to hide total site visits
  $show_page_visit_count = 'yes'; // Change to 'no' to hide page visit count
  ```

### Front-End HTML Output
Both scripts include HTML code to display the visit metrics. Feel free to modify the HTML as needed to fit your website's design.

## Example Output
```plaintext
Total site visits: 200
This Page visited: 30 times
Total site visited: 150 times
```

## Notes
- Make sure you have PHP running on your server.
- The scripts do not require a database and are lightweight.
- You are welcome to modify the scripts as needed.

## License
These scripts are free to use and modify. Attribution is appreciated but not required.
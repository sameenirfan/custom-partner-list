<?php
// Sample dates
$dates = [
    '2024-07-03',
    '2024-08-15',
    '2024-09-25',
];

// Set headers to prompt download
header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=dates.csv');

// Open the output stream
$output = fopen('php://output', 'w');

// Write the dates
foreach ($dates as $date) {
    // Format the date to yyyy-mm-dd
    $formattedDate = (new DateTime($date))->format('Y-m-d');
    
    fputcsv($output, [$formattedDate]);
}

// Close the output stream
fclose($output);
exit;
?>
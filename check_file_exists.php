<?php
// Get the file name from the query parameter
$file = isset($_GET['file']) ? $_GET['file'] : '';

if (file_exists($file)) {
    echo 'exists';
} else {
    echo 'not_exists';
}
?>

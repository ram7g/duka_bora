<?php
// Development mode
error_reporting(E_ALL);
ini_set("display_errors", 0);
ini_set("log_errors", 1);

// Convert PHP errors into exceptions
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function friendlyError()
{
    return "An error occurred. Please try again.";
}
?>
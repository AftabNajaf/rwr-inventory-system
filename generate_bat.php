<?php
$code = isset($_GET['code']) ? $_GET['code'] : 'default';
$batContent = "@echo off\nexplorer \"\\\\192.168.0.200\\MIS Docs\\$code\"";
$filePath = "temp_open_folder.bat";

// Create the .bat file
file_put_contents($filePath, $batContent);

// Force download
header('Content-Type: application/octet-stream');
header('Content-Disposition: attachment; filename="open_folder.bat"');
readfile($filePath);

// Delete the file after download
unlink($filePath);
exit;
?>

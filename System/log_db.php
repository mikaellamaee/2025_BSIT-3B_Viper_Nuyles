<?php
$log_host = 'localhost';
$log_username = 'root';
$log_password = '';
$log_database = 'origato_b2b';

$log_conn = new mysqli($log_host, $log_username, $log_password, $log_database);

if ($log_conn->connect_error) {
    die("Connection failed: " . $log_conn->connect_error);
} 
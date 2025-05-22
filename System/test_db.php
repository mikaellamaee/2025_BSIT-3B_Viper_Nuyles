<?php
$conn = mysqli_connect("localhost", "root", "", "origato");

if ($conn) {
    echo "Connected!";
} else {
    echo "Connection failed: " . mysqli_connect_error();
}
?>
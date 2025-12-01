<?php
$host = "localhost";
$user = "root";
$pass = "";
$db = "admin_dashboard";   // name of the database

// Create a connection to the database
$conn = new mysqli($host, $user, $pass, $db);

// Check if connection works or not
if ($conn->connect_error) {
    die("Connection Error");
} 

// if page is blank, means a connection is successful

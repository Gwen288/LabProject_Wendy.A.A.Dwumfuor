<?php

$env = parse_ini_file(__DIR__ . '/../env/connect.env');


$email=$_POST["email"];
$password=$_POST["password"];

echo $email;

$conn = new mysqli(
    $env['servername'],
    $env['username'],
    $env['password'],
    $env['dbname']
   );
   // Check connection
   if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
   }
   
else{
    echo ("Connection successful");
}


$stmt = $conn->prepare("SELECT password_hash FROM users WHERE email='$email'");
$stmt->execute();
$stmt->store_result(); 

if ($stmt->num_rows == 0) {
    die("User does not exist. Try again.");
}

// Bind the result to a variable
$stmt->bind_result($hash);
$stmt->fetch(); // fetch the row

// Verify password
if (password_verify($password, $hash)) {
    header("location:/LabProject_Wendy.A.A.Dwumfuor/LabProject_Wendy.A.A.Dwumfuor/html/faculty_dashboard.html");
    
} else {
    die("Incorrect password. Try again.");
}
?>
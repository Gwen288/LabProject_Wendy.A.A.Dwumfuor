<?php


$env = parse_ini_file(__DIR__ .'/../env/connect.env');
/*var_dump($env);
exit;

*/

$firstname=$_POST["first_name"];
$lastname=$_POST["last_name"];
$email=$_POST["email"];
$h_password=password_hash($_POST["password"],PASSWORD_DEFAULT);

echo "hi , writing php :)";

//connecting to the database using a local server
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
    echo "Connection Successfully";
}


//writing an insert query and executing it
$i_sql="Insert into Users(first_name,last_name,email,password_hash) values ('$firstname','$lastname','$email','$h_password')";

//reads the sql as string values to prevent sql injection
$conn->prepare($i_sql);
//executes the sql command by inserting into the database
$e_sql=$conn->query($i_sql);

//checks if execution was successfu;;y and redirects
if($e_sql===TRUE){
  header("location:/LabProject_Wendy.A.A.Dwumfuor/LabProject_Wendy.A.A.Dwumfuor/html/login.html");

}else{
    die ("Data insert failed");
}



?>
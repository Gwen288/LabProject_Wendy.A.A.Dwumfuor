<?php

$faculty_one=password_hash("david.mensah29",PASSWORD_DEFAULT);
$faculty_two=password_hash("sarah.agyei27",PASSWORD_DEFAULT);
$facultyIntern_one=password_hash("rich123..",PASSWORD_DEFAULT);
$facultyIntern_two=password_hash("samuel2345",PASSWORD_DEFAULT);

echo $faculty_one." ".$faculty_two ." ".$facultyIntern_one." ".$facultyIntern_two;


?>

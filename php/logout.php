<?php

//starts the session
session_start(); 

//remove all created session variables and destorys the session
try {
    session_unset();     
    session_destroy();   

    echo json_encode(["logout" => true]);
} catch (Exception $e) {
    echo json_encode(["logout" => false]);
}
?>

<?php
session_start();

function checkAccess($allowedRoles = null) {
    if (!isset($_SESSION['user_id'])) {
        header("Location: ../html/login_page.html");
        exit();
    }

    if ($allowedRoles !== null) {
        if (!is_array($allowedRoles)) {
            $allowedRoles = [$allowedRoles];
        }

        if (!in_array($_SESSION['u_role'], $allowedRoles)) {
            header("Location: ../html/login.html");
            exit();
        }
    }
}

?>
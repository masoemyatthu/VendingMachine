<?php

function requireLogin()
{
    if (!isset($_SESSION['user_id'])) {
        header('Location: ' . url('login'));
        exit;
    }
}

function requireAdmin()
{
    requireLogin();

    if ($_SESSION['role'] !== 'admin') {
        http_response_code(403);
        exit('403 Forbidden - Admin access required.');
    }
}


    
?>
<?php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/header.php';

$page = isset($_GET['page']) ? $_GET['page'] : 'dashboard';


$allowed = [
    'dashboard' => __DIR__ . '/dashboard.php',
    'user/list' => __DIR__ . '/../modules/user/list.php',
    'user/add'  => __DIR__ . '/../modules/user/add.php',
    'user/edit' => __DIR__ . '/../modules/user/edit.php',
    'user/delete' => __DIR__ . '/../modules/user/delete.php',
    'auth/login' => __DIR__ . '/../modules/auth/login.php',
    'auth/logout' => __DIR__ . '/../modules/auth/logout.php',
];


if (isset($allowed[$page])) {
    include $allowed[$page];
} else {
    echo '<h2>404 - Halaman tidak ditemukan</h2>';
}

require_once __DIR__ . '/footer.php';
?>
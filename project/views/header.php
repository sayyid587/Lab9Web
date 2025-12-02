<?php
if (session_status() == PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Praktikum 9 - Katalog Barang</title>
    <link rel="stylesheet" href="../asset/css/style.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Praktikum 9 - Katalog Barang</h1>
        <div class="user-info">
            <?php if (!empty($_SESSION['username'])): ?>
                <span>Halo, <?= htmlspecialchars($_SESSION['username']) ?></span> |
                <a href="index.php?page=auth/logout">Logout</a>
            <?php else: ?>
                <a href="index.php?page=auth/login">Login</a>
            <?php endif; ?>
        </div>
    </header>
    <nav>
        <a href="index.php">Dashboard</a>
        <a href="index.php?page=user/list">Daftar Barang</a>
    </nav>
    <main>

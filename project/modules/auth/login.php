<?php
// modules/auth/login.php
if (session_status() == PHP_SESSION_NONE) session_start();

$err = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = trim($_POST['password'] ?? '');
    // Dummy auth: accept any non-empty username/password
    if ($u !== '' && $p !== '') {
        $_SESSION['username'] = $u;
        header('Location: index.php');
        exit;
    } else {
        $err = 'Username atau password tidak boleh kosong.';
    }
}
?>
<h2>Login</h2>
<?php if ($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<form method="post" action="index.php?page=auth/login">
    <label>Username</label><br>
    <input type="text" name="username" required><br><br>
    <label>Password</label><br>
    <input type="password" name="password" required><br><br>
    <button type="submit">Login</button>
</form>

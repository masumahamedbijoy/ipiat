<?php
require_once 'config.php';
if (is_logged_in()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT id,name,email,password_hash,role FROM users WHERE email=?');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if ($user && password_verify($password, $user['password_hash'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['role'] = $user['role'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Email or password is incorrect.';
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Login · Infra Attendance</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-page"><div class="auth-card"><a class="brand auth-brand" href="#"><span class="brand-mark">IP</span><span><b>INFRA</b><small>Attendance Portal</small></span></a><div class="auth-heading"><span class="eyebrow">COMPUTER DEPARTMENT</span><h1>Welcome back</h1><p>Sign in to manage student attendance.</p></div>
<?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-stack"><label>Email address<input type="email" name="email" required autocomplete="username" placeholder="teacher@infra.edu.bd"></label><label>Password<input type="password" name="password" required autocomplete="current-password" placeholder="Enter your password"></label><button class="btn primary full" type="submit">Sign in <span>→</span></button></form><p class="auth-switch">New to the portal? <a href="register.php">Create an account</a></p><p class="muted tiny">Private institute portal · Authorized users only</p></div></body></html>

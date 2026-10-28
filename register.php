<?php
require_once 'config.php';
if (is_logged_in()) { header('Location: dashboard.php'); exit; }
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($password) < 8) {
        $error = 'Enter a name, valid email, and password with at least 8 characters.';
    } else {
        try {
            $pdo->beginTransaction();
            $count = (int)$pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
            $role = $count === 0 ? 'admin' : 'teacher';
            $stmt = $pdo->prepare('INSERT INTO users(name,email,password_hash,role) VALUES(?,?,?,?)');
            $stmt->execute([$name,$email,password_hash($password, PASSWORD_DEFAULT),$role]);
            $id = (int)$pdo->lastInsertId();
            $pdo->commit();
            session_regenerate_id(true);
            $_SESSION['user_id']=$id; $_SESSION['name']=$name; $_SESSION['role']=$role;
            header('Location: dashboard.php'); exit;
        } catch (PDOException $ex) {
            if ($pdo->inTransaction()) $pdo->rollBack();
            $error = 'This email may already be registered. Please try another.';
        }
    }
}
?>
<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Create account · Infra Attendance</title><link rel="stylesheet" href="assets/style.css"></head>
<body class="auth-page"><div class="auth-card"><a class="brand auth-brand" href="#"><span class="brand-mark">IP</span><span><b>INFRA</b><small>Attendance Portal</small></span></a><div class="auth-heading"><span class="eyebrow">GET STARTED</span><h1>Create your account</h1><p>The first account becomes Admin; later accounts are Teachers.</p></div>
<?php if($error): ?><div class="alert error"><?= e($error) ?></div><?php endif; ?>
<form method="post" class="form-stack"><label>Full name<input name="name" required maxlength="120" placeholder="Your full name"></label><label>Email address<input type="email" name="email" required placeholder="you@infra.edu.bd"></label><label>Password<input type="password" name="password" minlength="8" required placeholder="At least 8 characters"></label><button class="btn primary full" type="submit">Create account <span>→</span></button></form><p class="auth-switch">Already have an account? <a href="login.php">Sign in</a></p></div></body></html>

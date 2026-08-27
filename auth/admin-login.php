<?php
require_once __DIR__ . '/../includes/functions.php';
if (admin()) redirect('admin/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf(); $email = strtolower(trim($_POST['email'] ?? '')); $password = $_POST['password'] ?? '';
    $record = db_one('SELECT id,name,email,password FROM admins WHERE email = ?', 's', [$email]);
    if ($record && password_verify($password, $record['password'])) { unset($record['password']); session_regenerate_id(true); $_SESSION['admin'] = $record; redirect('admin/dashboard.php'); }
    $error = 'The administrator credentials are incorrect.';
}
$pageTitle = 'Admin Login'; require_once __DIR__ . '/../includes/header.php';
?><div class="auth-wrap"><div class="card auth-card"><div class="text-center mb-4"><div class="feature-icon mx-auto mb-3">A</div><h2 class="auth-heading">Administrator access</h2><p class="text-secondary">Manage and resolve student complaints.</p></div><?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="mb-3"><label class="form-label">Email address</label><input class="form-control" type="email" name="email" required></div><div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required></div><button class="btn btn-primary w-100 py-2" type="submit">Sign in as admin</button></form><p class="text-center small-muted mt-4 mb-0">Demo: admin@example.com / Admin@12345</p></div></div><?php require_once __DIR__ . '/../includes/footer.php'; ?>

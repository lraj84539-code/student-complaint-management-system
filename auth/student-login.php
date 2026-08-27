<?php
require_once __DIR__ . '/../includes/functions.php';
if (student()) redirect('student/dashboard.php');
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim($_POST['email'] ?? ''));
    $password = $_POST['password'] ?? '';
    $record = db_one('SELECT id, student_id, name, email, password FROM students WHERE email = ?', 's', [$email]);
    if ($record && password_verify($password, $record['password'])) {
        unset($record['password']); session_regenerate_id(true); $_SESSION['student'] = $record; redirect('student/dashboard.php');
    }
    $error = 'The email or password is incorrect.';
}
$pageTitle = 'Student Login'; require_once __DIR__ . '/../includes/header.php';
?>
<div class="auth-wrap"><div class="card auth-card"><div class="text-center mb-4"><div class="feature-icon mx-auto mb-3">C</div><h2 class="auth-heading">Welcome back</h2><p class="text-secondary">Sign in to track your campus concerns.</p></div>
<?php if ($error): ?><div class="alert alert-danger"><?= e($error) ?></div><?php endif; ?>
<form method="post" novalidate><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="mb-3"><label class="form-label">Email address</label><input class="form-control" type="email" name="email" required></div><div class="mb-3"><label class="form-label">Password</label><input class="form-control" type="password" name="password" required minlength="8"></div><button class="btn btn-primary w-100 py-2" type="submit">Sign in</button></form><p class="text-center text-secondary mt-4 mb-0">New to CampusCare? <a href="<?= e(url('auth/register.php')) ?>">Create an account</a></p><p class="text-center small-muted mt-3 mb-0"><a href="<?= e(url('auth/admin-login.php')) ?>">Administrator login</a></p>
</div></div>
<?php require_once __DIR__ . '/../includes/footer.php'; ?>

<?php
require_once __DIR__ . '/../includes/functions.php';
if (student()) redirect('student/dashboard.php');
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $studentId = trim($_POST['student_id'] ?? ''); $name = trim($_POST['name'] ?? ''); $email = strtolower(trim($_POST['email'] ?? '')); $phone = trim($_POST['phone'] ?? ''); $password = $_POST['password'] ?? ''; $confirm = $_POST['confirm_password'] ?? '';
    if ($studentId === '' || strlen($studentId) > 30) $errors[] = 'Enter a valid student ID.';
    if ($name === '' || strlen($name) < 2) $errors[] = 'Enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Enter a valid email address.';
    if (strlen($password) < 8) $errors[] = 'Password must contain at least 8 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!$errors && db_one('SELECT id FROM students WHERE email = ? OR student_id = ?', 'ss', [$email, $studentId])) $errors[] = 'That email or student ID is already registered.';
    if (!$errors) { $hash = password_hash($password, PASSWORD_DEFAULT); $stmt = db()->prepare('INSERT INTO students (student_id,name,email,phone,password) VALUES (?,?,?,?,?)'); $stmt->bind_param('sssss', $studentId, $name, $email, $phone, $hash); if ($stmt->execute()) { flash('success', 'Registration complete. You can now sign in.'); redirect('auth/student-login.php'); } $errors[] = 'Registration could not be completed.'; }
}
$pageTitle = 'Student Registration'; require_once __DIR__ . '/../includes/header.php';
?><div class="auth-wrap"><div class="card auth-card"><h2 class="auth-heading mb-2">Create your account</h2><p class="text-secondary mb-4">Join CampusCare to submit and track complaints.</p><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $item): ?><li><?= e($item) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" data-validate><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="row g-3"><div class="col-md-6"><label class="form-label">Student ID</label><input class="form-control" name="student_id" required value="<?= e($_POST['student_id'] ?? '') ?>"></div><div class="col-md-6"><label class="form-label">Phone</label><input class="form-control" name="phone" value="<?= e($_POST['phone'] ?? '') ?>"></div><div class="col-12"><label class="form-label">Full name</label><input class="form-control" name="name" required value="<?= e($_POST['name'] ?? '') ?>"></div><div class="col-12"><label class="form-label">Email address</label><input class="form-control" type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>"></div><div class="col-md-6"><label class="form-label">Password</label><input class="form-control" type="password" name="password" minlength="8" required></div><div class="col-md-6"><label class="form-label">Confirm password</label><input class="form-control" type="password" name="confirm_password" minlength="8" required></div></div><button class="btn btn-primary w-100 mt-4 py-2" type="submit">Create account</button></form><p class="text-center text-secondary mt-4 mb-0">Already registered? <a href="<?= e(url('auth/student-login.php')) ?>">Sign in</a></p></div></div><?php require_once __DIR__ . '/../includes/footer.php'; ?>

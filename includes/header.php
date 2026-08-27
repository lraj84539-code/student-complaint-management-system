<?php
require_once __DIR__ . '/functions.php';
$pageTitle = $pageTitle ?? APP_NAME;
$isDashboard = student() || admin();
?><!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="CampusCare Student Complaint Management System">
    <title><?= e($pageTitle) ?> | <?= e(APP_NAME) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?= e(url('assets/css/style.css')) ?>" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark app-navbar">
    <div class="container">
        <a class="navbar-brand fw-bold" href="<?= e(url('index.php')) ?>"><span class="brand-mark">C</span> CampusCare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav ms-auto align-items-lg-center gap-lg-2">
                <?php if (!$isDashboard): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('index.php')) ?>">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('about.php')) ?>">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('contact.php')) ?>">Contact</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('auth/admin-login.php')) ?>">Admin</a></li>
                    <li class="nav-item"><a class="btn btn-light btn-sm px-3" href="<?= e(url('auth/student-login.php')) ?>">Student Login</a></li>
                <?php elseif (student()): ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('student/dashboard.php')) ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('student/my-complaints.php')) ?>">My Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('student/profile.php')) ?>">Profile</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="<?= e(url('auth/logout.php')) ?>">Logout</a></li>
                <?php else: ?>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin/dashboard.php')) ?>">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin/complaints.php')) ?>">Complaints</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin/students.php')) ?>">Students</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= e(url('admin/categories.php')) ?>">Categories</a></li>
                    <li class="nav-item"><a class="btn btn-outline-light btn-sm" href="<?= e(url('auth/logout.php')) ?>">Logout</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
<main class="page-shell">
<div class="container py-4">
<?php foreach (consume_flash() as $notice): ?>
    <div class="alert alert-<?= e($notice['type']) ?> alert-dismissible fade show" role="alert"><?= e($notice['message']) ?><button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
<?php endforeach; ?>

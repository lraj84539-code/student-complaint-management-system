<?php
require_once __DIR__ . '/../config/config.php';

function e(?string $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function url(string $path = ''): string
{
    return rtrim(BASE_URL, '/') . '/' . ltrim($path, '/');
}

function redirect(string $path): never
{
    header('Location: ' . (str_starts_with($path, 'http') ? $path : url($path)));
    exit;
}

function flash(string $type, string $message): void
{
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function consume_flash(): array
{
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf(): void
{
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        exit('Invalid security token. Please go back and try again.');
    }
}

function student(): ?array
{
    return $_SESSION['student'] ?? null;
}

function admin(): ?array
{
    return $_SESSION['admin'] ?? null;
}

function require_student(): void
{
    if (!student()) {
        flash('warning', 'Please log in as a student to continue.');
        redirect('auth/student-login.php');
    }
}

function require_admin(): void
{
    if (!admin()) {
        flash('warning', 'Please log in as an administrator to continue.');
        redirect('auth/admin-login.php');
    }
}

function status_class(string $status): string
{
    return match ($status) {
        'Pending' => 'status-pending',
        'In Progress' => 'status-progress',
        'Resolved' => 'status-resolved',
        'Closed' => 'status-closed',
        'Rejected' => 'status-rejected',
        default => 'status-pending'
    };
}

function priority_class(string $priority): string
{
    return 'priority-' . strtolower($priority);
}

function db_one(string $sql, string $types = '', array $params = []): ?array
{
    $statement = db()->prepare($sql);
    if (!$statement) return null;
    if ($types !== '') $statement->bind_param($types, ...$params);
    $statement->execute();
    $result = $statement->get_result();
    return $result ? ($result->fetch_assoc() ?: null) : null;
}

function db_all(string $sql, string $types = '', array $params = []): array
{
    $statement = db()->prepare($sql);
    if (!$statement) return [];
    if ($types !== '') $statement->bind_param($types, ...$params);
    $statement->execute();
    $result = $statement->get_result();
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

function allowed_upload(?array $file): ?string
{
    if (!$file || ($file['error'] ?? UPLOAD_ERR_NO_FILE) === UPLOAD_ERR_NO_FILE) return null;
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > MAX_UPLOAD_BYTES) return null;
    $allowed = ['pdf', 'png', 'jpg', 'jpeg', 'doc', 'docx'];
    $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($extension, $allowed, true)) return null;
    $safeName = bin2hex(random_bytes(16)) . '.' . $extension;
    if (!is_dir(UPLOAD_DIR)) mkdir(UPLOAD_DIR, 0755, true);
    return move_uploaded_file($file['tmp_name'], UPLOAD_DIR . $safeName) ? $safeName : null;
}

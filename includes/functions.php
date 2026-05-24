<?php
function h($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
    header('Location: ' . $url);
    exit;
}

function set_flash(string $type, string $message): void
{
    $_SESSION['flash'] = [
        'type' => $type,
        'message' => $message,
    ];
}

function flash_message(): void
{
    if (empty($_SESSION['flash'])) {
        return;
    }

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    echo '<div class="alert alert-' . h($flash['type']) . ' alert-dismissible fade show" role="alert">';
    echo h($flash['message']);
    echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
    echo '</div>';
}

function is_logged_in(): bool
{
    return !empty($_SESSION['admin_id']);
}

function is_super_admin(): bool
{
    return is_logged_in() && ($_SESSION['admin_role'] ?? '') === 'super_admin';
}

function require_super_admin(): void
{
    if (!is_super_admin()) {
        die("Access Denied: Super Admin privileges required.");
    }
}

function fetch_scalar(string $sql, string $types = '', array $params = [])
{
    $stmt = db()->prepare($sql);

    if ($types !== '' && !empty($params)) {
        $stmt->bind_param($types, ...$params);
    }

    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result ? $result->fetch_row() : null;
    $stmt->close();

    return $row[0] ?? null;
}

/**
 * Generates a simple math captcha and stores the answer in the session.
 * Ensures the new question is different from the previous one.
 */
function generate_captcha(): string
{
    $previousAnswer = $_SESSION['captcha_answer'] ?? null;
    
    // Keep generating until we get a fresh question
    do {
        $num1 = rand(1, 9);
        $num2 = rand(1, 9);
        $answer = $num1 + $num2;
    } while ($answer === $previousAnswer);

    $_SESSION['captcha_answer'] = $answer;
    
    return "{$num1} + {$num2}";
}

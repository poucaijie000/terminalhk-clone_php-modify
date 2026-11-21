<?php
/**
 * Group: [Group Number]
 * Members: [Member Name] ([Student ID]), [Member Name] ([Student ID])
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Shared helpers for session bootstrap, output escaping, CSRF protection,
 * form validation, flash messages and redirects. All pages include this file
 * so the same rules are not copied into every page.
 */

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Escape a value before printing it inside HTML.
 *
 * @param mixed $value Raw value from user input, XML or database-like data.
 * @return string HTML-safe text.
 */
function e($value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Return the CSRF token for the current session, creating one on first use.
 *
 * @return string 64-character random token.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf_token'];
}

/**
 * Build a hidden CSRF input for a form.
 *
 * @return string HTML input element.
 */
function csrf_field(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(csrf_token()) . '">';
}

/**
 * Check the posted CSRF token against the session token.
 *
 * @return bool True when the token is present and identical.
 */
function verify_csrf(): bool
{
    $posted = $_POST['csrf_token'] ?? '';

    return is_string($posted)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $posted);
}

/**
 * Send a redirect response and stop the script.
 *
 * @param string $path Relative or absolute location.
 */
function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

/**
 * Store or read a one-time flash message.
 *
 * @param string      $key     Message key, for example "success".
 * @param string|null $message Message to store, or null to read and clear it.
 * @return string|null Stored message, or null when nothing is stored.
 */
function flash(string $key, ?string $message = null): ?string
{
    if ($message !== null) {
        $_SESSION['flash'][$key] = $message;
        return null;
    }

    if (!isset($_SESSION['flash'][$key])) {
        return null;
    }

    $value = (string) $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);

    return $value;
}

/**
 * Read a posted value and trim surrounding whitespace.
 *
 * @param string $key Form field name.
 * @return string Trimmed value, or an empty string.
 */
function post_string(string $key): string
{
    $value = $_POST[$key] ?? '';

    return is_string($value) ? trim($value) : '';
}

/**
 * Read a posted value without trimming, used for passwords where whitespace
 * must remain visible to the validator.
 *
 * @param string $key Form field name.
 * @return string Raw value, or an empty string.
 */
function post_raw(string $key): string
{
    $value = $_POST[$key] ?? '';

    return is_string($value) ? $value : '';
}

/**
 * Normalise an email address for storage and comparison.
 *
 * @param string $email Raw email address.
 * @return string Lower-case email address.
 */
function normalize_email(string $email): string
{
    return strtolower(trim($email));
}

/**
 * Validate the basic syntax of an email address.
 *
 * @param string $email Email address.
 * @return bool True when the address has a valid format.
 */
function is_valid_email(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

/**
 * Check that an address belongs to the Gmail domain required by the brief.
 *
 * @param string $email Normalised email address.
 * @return bool True for @gmail.com addresses only.
 */
function is_gmail_address(string $email): bool
{
    $domain = substr(strrchr($email, '@') ?: '', 1);

    return strcasecmp($domain, 'gmail.com') === 0;
}

/**
 * Return the password policy used by both the browser and the server.
 *
 * @return array{min_length:int,require_upper:bool,require_lower:bool,require_digit:bool}
 */
function password_policy(): array
{
    return [
        'min_length' => 8,
        'require_upper' => true,
        'require_lower' => true,
        'require_digit' => true,
    ];
}

/**
 * Validate a password against the group password policy.
 *
 * @param string $password Raw password from the form.
 * @return string[] Human-readable error messages; empty means valid.
 */
function validate_password(string $password): array
{
    $errors = [];
    $policy = password_policy();

    if (trim($password) === '') {
        $errors[] = '請輸入密碼，密碼不可以是空白。';
        return $errors;
    }

    if (preg_match('/\s/', $password) === 1) {
        $errors[] = '密碼不可以包含空白字元。';
    }

    if (mb_strlen($password) < $policy['min_length']) {
        $errors[] = '密碼至少需要 ' . $policy['min_length'] . ' 個字元。';
    }

    if ($policy['require_upper'] && preg_match('/[A-Z]/', $password) !== 1) {
        $errors[] = '密碼必須包含至少一個大寫英文字母。';
    }

    if ($policy['require_lower'] && preg_match('/[a-z]/', $password) !== 1) {
        $errors[] = '密碼必須包含至少一個小寫英文字母。';
    }

    if ($policy['require_digit'] && preg_match('/[0-9]/', $password) !== 1) {
        $errors[] = '密碼必須包含至少一個數字。';
    }

    return $errors;
}

/**
 * Validate a display name.
 *
 * @param string $name Raw name from the form.
 * @return string|null Error message, or null when the name is valid.
 */
function validate_name(string $name): ?string
{
    if ($name === '') {
        return '請輸入姓名。';
    }

    if (mb_strlen($name) < 2 || mb_strlen($name) > 50) {
        return '姓名長度必須介於 2 到 50 個字元。';
    }

    if (preg_match('/^[\p{L}\p{N} .\-]+$/u', $name) !== 1) {
        return '姓名只可以包含文字、數字、空格、句點和連字號。';
    }

    return null;
}

/**
 * Mask an email address for confirmation messages.
 *
 * @param string $email Email address.
 * @return string Address such as j***y@gmail.com.
 */
function mask_email(string $email): string
{
    $parts = explode('@', $email, 2);
    if (count($parts) !== 2) {
        return $email;
    }

    $name = $parts[0];
    $first = mb_substr($name, 0, 1);
    $last = mb_strlen($name) > 2 ? mb_substr($name, -1, 1) : '';

    return $first . str_repeat('*', max(1, mb_strlen($name) - 2)) . $last . '@' . $parts[1];
}

/**
 * Return the signed-in user stored in the session, if any.
 *
 * @return array{id:int,name:string,email:string}|null
 */
function current_user(): ?array
{
    return isset($_SESSION['user']) && is_array($_SESSION['user']) ? $_SESSION['user'] : null;
}

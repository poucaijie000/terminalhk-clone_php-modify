<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
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

/**
 * Return an inline SVG icon so pages do not depend on an external icon font.
 *
 * The icon inherits the surrounding text colour through fill="currentColor",
 * so Bootstrap utilities such as text-success and me-2 keep working.
 *
 * @param string $name  Icon key: search, arrow-left, arrow-right, trash, whatsapp.
 * @param string $class Optional space-separated CSS classes.
 * @return string Inline SVG markup, or an empty string for an unknown key.
 */
function icon_svg(string $name, string $class = ''): string
{
    $icons = [
        'search' => [
            'viewBox' => '0 0 16 16',
            'path' => 'M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z',
        ],
        'arrow-left' => [
            'viewBox' => '0 0 16 16',
            'path' => 'M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z',
        ],
        'arrow-right' => [
            'viewBox' => '0 0 16 16',
            'path' => 'M1 8a.5.5 0 0 1 .5-.5h11.793l-3.147-3.146a.5.5 0 0 1 .708-.708l4 4a.5.5 0 0 1 0 .708l-4 4a.5.5 0 0 1-.708-.708L13.293 8.5H1.5A.5.5 0 0 1 1 8z',
        ],
        'trash' => [
            'viewBox' => '0 0 16 16',
            'path' => 'M2.5 4.5h11M6 4.5V2.5h4v2M4 4.5l.7 9.5h6.6l.7-9.5M6.5 7v5M9.5 7v5',
            'stroke' => true,
        ],
        'whatsapp' => [
            'viewBox' => '0 0 24 24',
            'path' => 'M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a6.984 6.984 0 0 1-3.553-.973l-.255-.151-2.642.693.705-2.577-.167-.266a6.965 6.965 0 0 1-1.067-3.716c0-3.849 3.132-6.981 6.984-6.981a6.94 6.94 0 0 1 4.937 2.046 6.94 6.94 0 0 1 2.045 4.936c0 3.849-3.133 6.981-6.985 6.981m5.807-12.792A8.918 8.918 0 0 0 12.05.001C5.494.001.157 5.338.155 11.892c0 2.096.549 4.14 1.591 5.945L0 24l6.335-1.662a8.884 8.884 0 0 0 4.251 1.082h.004c6.555 0 11.893-5.337 11.895-11.892a8.88 8.88 0 0 0-2.607-6.319',
        ],
    ];

    if (!isset($icons[$name])) {
        return '';
    }

    $classAttribute = $class === '' ? '' : ' class="' . e($class) . '"';
    $isStroke = !empty($icons[$name]['stroke']);
    $paint = $isStroke
        ? ' fill="none" stroke="currentColor" stroke-width="1.2" stroke-linecap="round" stroke-linejoin="round"'
        : ' fill="currentColor"';

    return '<svg' . $classAttribute . ' xmlns="http://www.w3.org/2000/svg"'
        . ' width="1em" height="1em"' . $paint
        . ' viewBox="' . $icons[$name]['viewBox'] . '"'
        . ' style="vertical-align:-0.125em" aria-hidden="true" focusable="false">'
        . '<path d="' . $icons[$name]['path'] . '"/>'
        . '</svg>';
}

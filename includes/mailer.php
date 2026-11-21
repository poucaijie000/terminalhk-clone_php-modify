<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Mail delivery for the forgot-password verification code. The class group
 * can choose one of three drivers:
 * - smtp : connect to Gmail SMTP and authenticate with an app password.
 * - mail : use PHP mail() with the sendmail/SMTP settings in php.ini.
 * - log  : write the message to data/mail.log (local testing only).
 *
 * The SMTP driver is a small self-written client. It uses only PHP core
 * functions, so no third-party mail library is added to the project.
 */

declare(strict_types=1);

require_once __DIR__ . '/helpers.php';

/**
 * Load mail settings from includes/mail_config.local.php when present.
 *
 * @return array<string,mixed> Mail configuration.
 */
function mail_config(): array
{
    static $config = null;
    if (is_array($config)) {
        return $config;
    }

    $config = [
        'driver' => 'mail',
        'host' => 'smtp.gmail.com',
        'port' => 587,
        'encryption' => 'tls',
        'username' => '',
        'password' => '',
        'from' => '',
        'from_name' => 'TerminalHK',
        'verify_peer' => true,
    ];

    $localFile = __DIR__ . '/mail_config.local.php';
    if (is_file($localFile)) {
        $loaded = require $localFile;
        if (is_array($loaded)) {
            $config = array_merge($config, $loaded);
        }
    }

    return $config;
}

/**
 * Send the reset verification code to a user.
 *
 * @param string $toEmail Recipient Gmail address.
 * @param string $toName  Recipient display name.
 * @param string $code    Six-digit verification code.
 * @return bool True when the message was handed to the configured driver.
 */
function send_verification_code(string $toEmail, string $toName, string $code): bool
{
    if (!is_valid_email($toEmail) || !is_gmail_address($toEmail)) {
        return false;
    }

    $subject = 'TerminalHK 密碼重設驗證碼';
    $body = "您好 " . $toName . "，\n\n"
        . "我們收到重設 TerminalHK 帳戶密碼的請求。\n"
        . "驗證碼：" . $code . "\n"
        . "此驗證碼有效期為 15 分鐘。\n\n"
        . "如果這不是您本人的操作，請忽略此郵件，您的密碼不會被更改。\n\n"
        . "香港終端 TerminalHK";

    $settings = mail_config();
    $driver = strtolower((string) $settings['driver']);

    if ($driver === 'log') {
        return log_mail_message($toEmail, $subject, $body);
    }

    if ($driver === 'mail') {
        return send_with_php_mail($toEmail, $subject, $body, $settings);
    }

    return send_with_smtp($settings, $toEmail, $subject, $body);
}

/**
 * Write a message to data/mail.log for local testing.
 *
 * @param string $to      Recipient address.
 * @param string $subject Subject line.
 * @param string $body    Plain-text body.
 * @return bool True when the log entry was written.
 */
function log_mail_message(string $to, string $subject, string $body): bool
{
    $entry = "[" . date('c') . "] To: " . $to . "\n"
        . "Subject: " . $subject . "\n"
        . $body . "\n"
        . str_repeat('-', 60) . "\n";

    return file_put_contents(dirname(__DIR__) . '/data/mail.log', $entry, FILE_APPEND | LOCK_EX) !== false;
}

/**
 * Send a message through PHP mail().
 *
 * @param string              $to      Recipient address.
 * @param string              $subject Subject line.
 * @param string              $body    Plain-text body.
 * @param array<string,mixed> $settings Mail configuration.
 * @return bool True when mail() accepted the message.
 */
function send_with_php_mail(string $to, string $subject, string $body, array $settings): bool
{
    $from = (string) ($settings['from'] ?: $settings['username']);
    if (!is_valid_email($from)) {
        return false;
    }

    $fromName = encode_mime_header((string) $settings['from_name']);
    $subjectHeader = encode_mime_header($subject);
    $headers = [
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: base64',
        'From: ' . $fromName . ' <' . $from . '>',
        'Reply-To: ' . $from,
        'X-Mailer: PHP/' . PHP_VERSION,
    ];

    return mail($to, $subjectHeader, chunk_split(base64_encode($body), 76, "\r\n"), implode("\r\n", $headers));
}

/**
 * Send a message through Gmail SMTP using STARTTLS and AUTH LOGIN.
 *
 * @param array<string,mixed> $settings Mail configuration.
 * @param string              $to       Recipient address.
 * @param string              $subject  Subject line.
 * @param string              $body     Plain-text body.
 * @return bool True when the SMTP server accepted the message.
 */
function send_with_smtp(array $settings, string $to, string $subject, string $body): bool
{
    $host = (string) $settings['host'];
    $port = (int) $settings['port'];
    $encryption = strtolower((string) $settings['encryption']);
    $username = (string) $settings['username'];
    $password = (string) $settings['password'];
    $from = (string) ($settings['from'] ?: $username);

    if ($host === '' || $port <= 0 || !is_valid_email($from) || !is_valid_email($to)) {
        return false;
    }

    $remote = ($encryption === 'ssl' ? 'ssl://' : 'tcp://') . $host . ':' . $port;
    $context = stream_context_create([
        'ssl' => [
            'verify_peer' => (bool) $settings['verify_peer'],
            'verify_peer_name' => (bool) $settings['verify_peer'],
            'allow_self_signed' => !(bool) $settings['verify_peer'],
        ],
    ]);

    $socket = @stream_socket_client($remote, $errno, $errstr, 30, STREAM_CLIENT_CONNECT, $context);
    if ($socket === false) {
        error_log('SMTP connect failed: ' . $errno . ' ' . $errstr);
        return false;
    }

    stream_set_timeout($socket, 30);

    try {
        smtp_expect($socket, '220');
        smtp_command($socket, 'EHLO localhost', '250');

        if ($encryption === 'tls') {
            smtp_command($socket, 'STARTTLS', '220');
            if (!stream_socket_enable_crypto($socket, true, STREAM_CRYPTO_METHOD_TLS_CLIENT)) {
                throw new RuntimeException('Unable to start TLS with the SMTP server.');
            }
            smtp_command($socket, 'EHLO localhost', '250');
        }

        smtp_command($socket, 'AUTH LOGIN', '334');
        smtp_command($socket, base64_encode($username), '334');
        smtp_command($socket, base64_encode($password), '235');

        smtp_command($socket, 'MAIL FROM:<' . $from . '>', '250');
        smtp_command($socket, 'RCPT TO:<' . $to . '>', '250');
        smtp_command($socket, 'DATA', '354');

        $message = build_smtp_message($settings, $to, $subject, $body);
        fwrite($socket, $message . "\r\n.\r\n");
        smtp_expect($socket, '250');

        smtp_command($socket, 'QUIT', '221');
    } catch (Throwable $exception) {
        error_log('SMTP send failed: ' . $exception->getMessage());
        fclose($socket);
        return false;
    }

    fclose($socket);

    return true;
}

/**
 * Build RFC 5322 headers and a base64 body for SMTP DATA.
 *
 * @param array<string,mixed> $settings Mail configuration.
 * @param string              $to       Recipient address.
 * @param string              $subject  Subject line.
 * @param string              $body     Plain-text body.
 * @return string Message with CRLF line endings, without the final dot.
 */
function build_smtp_message(array $settings, string $to, string $subject, string $body): string
{
    $from = (string) ($settings['from'] ?: $settings['username']);
    $fromName = encode_mime_header((string) $settings['from_name']);
    $subjectHeader = encode_mime_header($subject);
    $messageId = bin2hex(random_bytes(16)) . '@' . (string) $settings['host'];

    $headers = [
        'Date: ' . date('r'),
        'From: ' . $fromName . ' <' . $from . '>',
        'To: <' . $to . '>',
        'Subject: ' . $subjectHeader,
        'Message-ID: <' . $messageId . '>',
        'MIME-Version: 1.0',
        'Content-Type: text/plain; charset=UTF-8',
        'Content-Transfer-Encoding: base64',
    ];

    return implode("\r\n", $headers) . "\r\n\r\n"
        . chunk_split(base64_encode($body), 76, "\r\n");
}

/**
 * Encode a UTF-8 header value for an email.
 *
 * @param string $value Plain header text.
 * @return string MIME encoded-word value.
 */
function encode_mime_header(string $value): string
{
    return '=?UTF-8?B?' . base64_encode($value) . '?=';
}

/**
 * Send one SMTP command and verify the reply code.
 *
 * @param resource $socket Connected SMTP socket.
 * @param string   $command Command text.
 * @param string   $expected Expected three-digit reply code.
 */
function smtp_command($socket, string $command, string $expected): void
{
    fwrite($socket, $command . "\r\n");
    smtp_expect($socket, $expected);
}

/**
 * Read one complete SMTP reply, including multi-line replies.
 *
 * @param resource $socket Connected SMTP socket.
 * @return string Full reply text.
 */
function smtp_read_reply($socket): string
{
    $reply = '';
    while (($line = fgets($socket, 515)) !== false) {
        $reply .= $line;
        if (strlen($line) < 4 || $line[3] === ' ') {
            break;
        }
    }

    return $reply;
}

/**
 * Verify the reply code from the SMTP server.
 *
 * @param resource $socket   Connected SMTP socket.
 * @param string   $expected Expected three-digit reply code.
 */
function smtp_expect($socket, string $expected): void
{
    $reply = smtp_read_reply($socket);
    if (substr($reply, 0, 3) !== $expected) {
        throw new RuntimeException('SMTP expected ' . $expected . ', got: ' . trim($reply));
    }
}

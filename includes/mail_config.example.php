<?php
/**
 * Group: [Group Number]
 * Members: [Member Name] ([Student ID]), [Member Name] ([Student ID])
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Example mail configuration. Copy this file to mail_config.local.php and
 * fill in the group Gmail account and app password. The local file is
 * excluded by .gitignore and must not be committed.
 *
 * Gmail setup:
 * 1. Enable two-step verification on the group Gmail account.
 * 2. Create an App Password for "Mail".
 * 3. Use smtp.gmail.com, port 587, encryption "tls".
 */

return [
    'driver' => 'smtp',
    'host' => 'smtp.gmail.com',
    'port' => 587,
    'encryption' => 'tls',
    'username' => 'yourgroup@gmail.com',
    'password' => 'your-16-character-app-password',
    'from' => 'yourgroup@gmail.com',
    'from_name' => 'TerminalHK',
    'verify_peer' => true,
];

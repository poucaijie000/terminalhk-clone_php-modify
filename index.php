<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Front controller: includes the header, home sections and footer.
 */


// 如果有資料庫連線或設定，通常會在這裡 require config.php
// require_once 'config.php';

// 引入頭部
include 'includes/header.php';

// 引入首頁內容
include 'views/home.php';

// 引入底部
include 'includes/footer.php';
?>
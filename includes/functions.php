<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Shared display helpers such as price formatting.
 */


/**
 * 格式化價格輸出
 */
function formatPrice($price) {
    return '$' . number_format($price, 0);
}
?>
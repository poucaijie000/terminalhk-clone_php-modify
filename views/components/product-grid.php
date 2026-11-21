<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Reusable grid that renders a list of product cards.
 */


// 預期傳入變數 $products 陣列
if (empty($products)) return;
?>
<div class="product-grid">
    <?php foreach ($products as $product): ?>
        <?php include __DIR__ . '/product-card.php'; ?>
    <?php endforeach; ?>
</div>
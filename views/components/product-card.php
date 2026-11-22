<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Reusable product card for one catalogue item.
 */
?>
<div class="card h-100 border-light shadow-sm text-decoration-none">
    <!-- Image container (fixed height, centred) -->
    <div class="d-flex align-items-center justify-content-center p-3" style="height: 180px;">
        <img src="<?= htmlspecialchars($product['img']) ?>" class="img-fluid" alt="<?= htmlspecialchars($product['title']) ?>" style="max-height: 100%; object-fit: contain;">
    </div>
    
    <div class="card-body d-flex flex-column p-3">
        <!-- Title: truncate long text with line-clamp -->
        <h6 class="card-title text-dark" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; font-size: 14px; min-height: 42px;">
            <?= htmlspecialchars($product['title']) ?>
        </h6>
        
        <!-- Price block (pushed to the bottom) -->
        <div class="mt-auto d-flex align-items-center gap-2">
            <?php if (isset($product['is_sale']) && $product['is_sale']): ?>
                <span class="badge bg-danger rounded-1">SALES</span>
            <?php endif; ?>
            
            <span class="fs-5 fw-bold text-danger"><?= formatPrice($product['price']) ?></span>
            
            <?php if (isset($product['original_price'])): ?>
                <small class="text-muted text-decoration-line-through"><?= formatPrice($product['original_price']) ?></small>
            <?php endif; ?>
        </div>
    </div>
</div>
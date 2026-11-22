<?php
declare(strict_types=1);
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Home page sections. The carousel and side cards stay static, while the
 * recommended, new arrival and hot sale product rows are read from
 * data/items.xml through the shared XML layer.
 */

require_once __DIR__ . '/../includes/helpers.php';
require_once __DIR__ . '/../includes/xml.php';

$itemsXml = load_xml(ITEMS_XML);
$items = $itemsXml instanceof SimpleXMLElement ? all_items($itemsXml) : [];

/**
 * Resolve a product image and reject paths outside img/.
 */
$itemImage = static function (SimpleXMLElement $item): string {
    $image = (string) $item->image;
    if ($image === '' || strpos($image, 'img/') !== 0 || !is_file(dirname(__DIR__) . '/' . $image)) {
        return 'img/logo/logo.png';
    }

    return $image;
};

/**
 * Resolve the alt text for a product image.
 */
$itemAlt = static function (SimpleXMLElement $item): string {
    $alt = trim((string) $item->image_alt);
    return $alt !== '' ? $alt : (string) $item->name;
};

/**
 * Build the product detail URL for a card.
 */
$itemUrl = static function (SimpleXMLElement $item): string {
    return 'product_detail.php?id=' . urlencode((string) $item->id);
};

// Recommended: featured items first, then fill the four-card row.
$recommended = [];
foreach ($items as $item) {
    if ((int) $item->featured === 1) {
        $recommended[] = $item;
    }
}
foreach ($items as $item) {
    if (count($recommended) >= 4) {
        break;
    }

    $alreadySelected = false;
    foreach ($recommended as $selected) {
        if ($selected === $item) {
            $alreadySelected = true;
            break;
        }
    }

    if (!$alreadySelected) {
        $recommended[] = $item;
    }
}
$recommended = array_slice($recommended, 0, 4);

// New arrivals: newest created_at first.
$newItems = $items;
usort($newItems, static function (SimpleXMLElement $a, SimpleXMLElement $b): int {
    $dateOrder = strcmp((string) $b->created_at, (string) $a->created_at);
    if ($dateOrder !== 0) {
        return $dateOrder;
    }

    return strcmp((string) $a->name, (string) $b->name);
});
$newItems = array_slice($newItems, 0, 5);

// Hot sales: items marked hot="1", then fill the five-card row.
$hotItems = [];
foreach ($items as $item) {
    if ((int) $item->hot === 1) {
        $hotItems[] = $item;
    }
}
foreach ($items as $item) {
    if (count($hotItems) >= 5) {
        break;
    }

    $alreadySelected = false;
    foreach ($hotItems as $selected) {
        if ($selected === $item) {
            $alreadySelected = true;
            break;
        }
    }

    if (!$alreadySelected) {
        $hotItems[] = $item;
    }
}
$hotItems = array_slice($hotItems, 0, 5);
?>
    <!-- === Main content: carousel and sidebar === -->
    <main class="container my-4">
        <div class="row">
            <!-- Left carousel (Bootstrap Carousel, 9 columns) -->
            <div class="col-lg-9 mb-4">
                <div id="heroCarousel" class="carousel slide banner-area" data-bs-ride="carousel">
                    <!-- Indicators -->
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="0" class="active"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="1"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="2"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="3"></button>
                        <button type="button" data-bs-target="#heroCarousel" data-bs-slide-to="4"></button>
                    </div>
                    
                    <!-- Slide content -->
                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <img src="img/slide/slide01.png" class="d-block w-100" alt="Slide 1">
                        </div>
                        <div class="carousel-item">
                            <img src="img/slide/slide02.png" class="d-block w-100" alt="Slide 2">
                        </div>
                        <div class="carousel-item">
                            <img src="img/slide/slide03.png" class="d-block w-100" alt="Slide 3">
                        </div>
                        <div class="carousel-item">
                            <img src="img/slide/slide04.png" class="d-block w-100" alt="Slide 4">
                        </div>
                        <div class="carousel-item">
                            <img src="img/slide/slide05.png" class="d-block w-100" alt="Slide 5">
                        </div>
                    </div>
                    
                    <!-- Previous / next controls -->
                    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    </button>
                </div>
            </div>

            <!-- Right sidebar (3 columns) -->
            <div class="col-lg-3">
                <div class="d-flex flex-column gap-3">
                    <a href="products.php" class="text-decoration-none">
                        <div class="card side-card text-center py-4">
                            <img src="img/sideimg/rightside01.png" class="card-img-top mx-auto" alt="電腦硬件">
                            <div class="card-body p-0 mt-3">
                                <h5 class="card-title text-dark m-0 fw-normal">電腦硬件</h5>
                            </div>
                        </div>
                    </a>
                    
                    <a href="build.php#builder-section" class="text-decoration-none">
                        <div class="card side-card text-center py-4">
                            <img src="img/sideimg/rightside02.png" class="card-img-top mx-auto" alt="自選砌機">
                            <div class="card-body p-0 mt-3">
                                <h5 class="card-title text-dark m-0 fw-normal">自選砌機</h5>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- === Featured products: featured items from data/items.xml === -->
    <section class="container my-5">
        <h2 class="section-title mb-3">推薦產品</h2>

        <?php if ($recommended === []): ?>
            <div class="alert alert-info">暫無推薦商品。</div>
        <?php else: ?>
            <div class="row g-0 border-top border-start">
                <?php foreach ($recommended as $item): ?>
                    <?php $oldPrice = (float) $item->old_price; ?>
                    <div class="col-md-3">
                        <div class="card h-100 border-0 border-end border-bottom rounded-0">
                            <a href="<?php echo e($itemUrl($item)); ?>" class="text-decoration-none">
                                <div class="p-3 text-center">
                                    <img
                                        src="<?php echo e($itemImage($item)); ?>"
                                        class="img-fluid product-img"
                                        alt="<?php echo e($itemAlt($item)); ?>"
                                        loading="lazy"
                                    >
                                </div>
                            </a>
                            <div class="card-body pt-0 px-3 pb-3 d-flex flex-column">
                                <h5 class="card-title product-title">
                                    <a href="<?php echo e($itemUrl($item)); ?>" class="text-dark text-decoration-none">
                                        <?php echo e((string) $item->name); ?>
                                    </a>
                                </h5>
                                <div class="mt-auto d-flex align-items-center gap-2">
                                    <?php if ($oldPrice > (float) $item->price): ?>
                                        <span class="badge bg-danger rounded-0 px-2 py-1">SALES</span>
                                    <?php endif; ?>
                                    <span class="text-danger fs-5 fw-bold">
                                        <?php echo e(format_price($item->price)); ?>
                                    </span>
                                    <?php if ($oldPrice > (float) $item->price): ?>
                                        <span class="text-muted text-decoration-line-through small">
                                            <?php echo e(format_price($item->old_price)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- === New arrivals: newest created_at from data/items.xml === -->
    <section class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2 class="section-title m-0">新品上架</h2>
            <a href="products.php" class="text-decoration-none text-muted">顯示更多》</a>
        </div>

        <?php if ($newItems === []): ?>
            <div class="alert alert-info">暫無新品。</div>
        <?php else: ?>
            <div class="row g-0 border-top border-start product-row-five">
                <?php foreach ($newItems as $item): ?>
                    <div class="col">
                        <div class="card h-100 border-0 border-end border-bottom rounded-0">
                            <a href="<?php echo e($itemUrl($item)); ?>" class="text-decoration-none">
                                <div class="p-4 text-center">
                                    <img
                                        src="<?php echo e($itemImage($item)); ?>"
                                        class="img-fluid product-img"
                                        alt="<?php echo e($itemAlt($item)); ?>"
                                        loading="lazy"
                                    >
                                </div>
                            </a>
                            <div class="card-body pt-0 px-3 pb-3">
                                <p class="card-text text-dark small product-name mb-1">
                                    <a href="<?php echo e($itemUrl($item)); ?>" class="text-dark text-decoration-none">
                                        <?php echo e((string) $item->name); ?>
                                    </a>
                                </p>
                                <p class="text-muted small mb-0">
                                    <?php echo e(format_price($item->price)); ?>
                                </p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- === Hot sale: hot="1" items from data/items.xml === -->
    <section class="container my-5 pb-5">
        <h2 class="section-title mb-3">熱賣產品</h2>

        <?php if ($hotItems === []): ?>
            <div class="alert alert-info">暫無熱賣商品。</div>
        <?php else: ?>
            <div class="row g-0 border-top border-start product-row-five">
                <?php foreach ($hotItems as $item): ?>
                    <?php $oldPrice = (float) $item->old_price; ?>
                    <div class="col">
                        <div class="card h-100 border-0 border-end border-bottom rounded-0">
                            <a href="<?php echo e($itemUrl($item)); ?>" class="text-decoration-none">
                                <div class="p-4 text-center">
                                    <img
                                        src="<?php echo e($itemImage($item)); ?>"
                                        class="img-fluid product-img"
                                        alt="<?php echo e($itemAlt($item)); ?>"
                                        loading="lazy"
                                    >
                                </div>
                            </a>
                            <div class="card-body pt-0 px-3 pb-3 d-flex flex-column">
                                <h5 class="card-title product-title product-name" style="font-size: 14px;">
                                    <a href="<?php echo e($itemUrl($item)); ?>" class="text-dark text-decoration-none">
                                        <?php echo e((string) $item->name); ?>
                                    </a>
                                </h5>
                                <div class="mt-auto d-flex align-items-center gap-2">
                                    <?php if ($oldPrice > (float) $item->price): ?>
                                        <span class="badge bg-danger rounded-0 px-2 py-1">SALES</span>
                                    <?php endif; ?>
                                    <span class="<?php echo $oldPrice > (float) $item->price ? 'text-danger fw-bold fs-5' : 'text-muted'; ?>">
                                        <?php echo e(format_price($item->price)); ?>
                                    </span>
                                    <?php if ($oldPrice > (float) $item->price): ?>
                                        <span class="text-muted text-decoration-line-through small">
                                            <?php echo e(format_price($item->old_price)); ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

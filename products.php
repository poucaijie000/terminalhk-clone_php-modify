<?php
declare(strict_types=1);
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Item list page. Products are read from data/items.xml through the shared XML
 * layer. The page supports a keyword search and a category filter; every card
 * links to product_detail.php?id=<item id>.
 */

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';

$current_page = 'products.php';
$page_title = '產品目錄 | 香港終端 TerminalHK';

$keyword = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';
$category = isset($_GET['category']) && is_string($_GET['category']) ? trim($_GET['category']) : '';

$itemsXml = load_xml(ITEMS_XML);
$items = $itemsXml instanceof SimpleXMLElement ? all_items($itemsXml) : [];

$categories = [];
foreach ($items as $item) {
    $name = trim((string) $item->category);
    if ($name !== '') {
        $categories[$name] = true;
    }
}
$categories = array_keys($categories);
sort($categories, SORT_NATURAL | SORT_FLAG_CASE);

$filtered = [];
foreach ($items as $item) {
    $name = (string) $item->name;
    $summary = (string) $item->summary;
    $itemCategory = (string) $item->category;

    if ($keyword !== '') {
        $haystack = $name . ' ' . $summary . ' ' . $itemCategory;
        if (stripos($haystack, $keyword) === false) {
            continue;
        }
    }

    if ($category !== '' && $itemCategory !== $category) {
        continue;
    }

    $filtered[] = $item;
}

require __DIR__ . '/includes/header.php';
?>

<!-- Product catalogue: filters, category navigation and dynamic item cards -->
<div class="container my-4">

    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="index.php" class="text-decoration-none text-muted">首頁</a>
            </li>
            <li class="breadcrumb-item active text-dark" aria-current="page">產品目錄</li>
        </ol>
    </nav>

    <div class="row border rounded bg-white">

        <!-- Category sidebar generated from items.xml -->
        <aside class="col-lg-3 border-end py-3">
            <h2 class="h6 text-uppercase text-muted px-3 mb-3">商品分類</h2>
            <ul class="list-unstyled category-menu mb-0">
                <li class="mb-2">
                    <a
                        href="products.php<?php echo $keyword !== '' ? '?q=' . urlencode($keyword) : ''; ?>"
                        class="text-decoration-none <?php echo $category === '' ? 'fw-bold text-primary' : 'text-dark'; ?>"
                    >
                        全部商品
                        <span class="text-muted small">(<?php echo count($items); ?>)</span>
                    </a>
                </li>

                <?php foreach ($categories as $categoryName): ?>
                    <?php
                    $count = 0;
                    foreach ($items as $item) {
                        if ((string) $item->category === $categoryName) {
                            $count++;
                        }
                    }

                    $params = [];
                    if ($keyword !== '') {
                        $params['q'] = $keyword;
                    }
                    $params['category'] = $categoryName;
                    $categoryUrl = 'products.php?' . http_build_query($params);
                    $active = $category === $categoryName;
                    ?>
                    <li class="mb-2">
                        <a
                            href="<?php echo e($categoryUrl); ?>"
                            class="text-decoration-none <?php echo $active ? 'fw-bold text-primary' : 'text-dark'; ?>"
                        >
                            <?php echo e($categoryName); ?>
                            <span class="text-muted small">(<?php echo $count; ?>)</span>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>

        <!-- Search results and product grid -->
        <section class="col-lg-9 p-4">
            <div class="d-flex flex-wrap justify-content-between align-items-end gap-2 mb-3">
                <div>
                    <h1 class="h4 mb-1">產品目錄</h1>
                    <p class="text-muted mb-0">共 <?php echo count($filtered); ?> 件商品</p>
                </div>

                <?php if ($keyword !== '' || $category !== ''): ?>
                    <div class="small text-muted">
                        <?php if ($keyword !== ''): ?>
                            關鍵字：<?php echo e($keyword); ?>
                        <?php endif; ?>
                        <?php if ($keyword !== '' && $category !== ''): ?>
                            ／
                        <?php endif; ?>
                        <?php if ($category !== ''): ?>
                            分類：<?php echo e($category); ?>
                        <?php endif; ?>
                        <a href="products.php" class="ms-2">清除篩選</a>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Keyword search form; the selected category is kept as a hidden field -->
            <form class="row g-2 mb-4" action="products.php" method="get">
                <div class="col-12 col-md-8">
                    <label for="productKeyword" class="visually-hidden">搜尋商品</label>
                    <input
                        class="form-control"
                        id="productKeyword"
                        name="q"
                        type="search"
                        value="<?php echo e($keyword); ?>"
                        placeholder="搜尋商品名稱、描述或分類..."
                    >
                </div>

                <?php if ($category !== ''): ?>
                    <input type="hidden" name="category" value="<?php echo e($category); ?>">
                <?php endif; ?>

                <div class="col-12 col-md-4 d-flex gap-2">
                    <button class="btn btn-primary flex-grow-1" type="submit">搜尋</button>
                    <a class="btn btn-outline-secondary" href="products.php">清除</a>
                </div>
            </form>

            <?php if ($filtered === []): ?>
                <!-- Empty state when no item matches the filters -->
                <div class="alert alert-info mb-0" role="alert">
                    沒有符合條件的商品，請嘗試其他關鍵字或分類。
                </div>
            <?php else: ?>
                <!-- Dynamic product cards read from data/items.xml -->
                <div class="row row-cols-1 row-cols-md-2 row-cols-xl-3 g-4">
                    <?php foreach ($filtered as $item): ?>
                        <?php
                        $itemId = (string) $item->id;
                        $itemName = (string) $item->name;
                        $itemCategory = (string) $item->category;
                        $itemSummary = (string) $item->summary;
                        $itemImage = (string) $item->image;
                        $itemAlt = trim((string) $item->image_alt);
                        if ($itemAlt === '') {
                            $itemAlt = $itemName;
                        }

                        if (
                            $itemImage === ''
                            || strpos($itemImage, 'assets/images/') !== 0
                            || !is_file(__DIR__ . '/' . $itemImage)
                        ) {
                            $itemImage = 'assets/images/logo/logo.png';
                        }
                        ?>
                        <div class="col">
                            <article class="card h-100 shadow-sm">
                                <div class="ratio ratio-4x3 bg-light">
                                    <img
                                        src="<?php echo e($itemImage); ?>"
                                        class="w-100 h-100 object-fit-contain p-3"
                                        alt="<?php echo e($itemAlt); ?>"
                                        loading="lazy"
                                    >
                                </div>
                                <div class="card-body d-flex flex-column">
                                    <span class="badge bg-secondary align-self-start mb-2">
                                        <?php echo e($itemCategory); ?>
                                    </span>
                                    <h2 class="h6 card-title"><?php echo e($itemName); ?></h2>
                                    <p class="card-text text-muted small flex-grow-1">
                                        <?php echo e($itemSummary); ?>
                                    </p>
                                    <div class="d-flex align-items-center justify-content-between gap-2 mt-3">
                                        <span class="fs-5 fw-bold text-primary">
                                            <?php echo e(format_price($item->price)); ?>
                                        </span>
                                        <a
                                            class="btn btn-primary btn-sm"
                                            href="product_detail.php?id=<?php echo urlencode($itemId); ?>"
                                        >
                                            查看詳情
                                        </a>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </section>
    </div>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>

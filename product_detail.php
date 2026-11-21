<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Item detail page. The product id comes from ?id= and is matched against
 * data/items.xml. All output is escaped, and the image path must stay inside
 * the local assets/images/ directory.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';

$current_page = 'products.php';
$page_title = '產品詳情 | 香港終端 TerminalHK';

$itemId = isset($_GET['id']) ? trim((string) $_GET['id']) : '';
$item = null;
$error = '';

if ($itemId === '' || !is_valid_item_id($itemId)) {
    http_response_code(404);
    $error = '產品編號不正確。';
} else {
    $items = load_xml(ITEMS_XML);
    if ($items === null) {
        http_response_code(500);
        $error = '無法讀取產品資料，請確認 data/items.xml 存在。';
    } else {
        $item = find_item_by_id($items, $itemId);
        if ($item === null) {
            http_response_code(404);
            $error = '找不到這個產品。';
        }
    }
}

if ($item !== null) {
    $page_title = (string) $item->name . ' | 香港終端 TerminalHK';
}

require __DIR__ . '/includes/header.php';
?>

<!-- Item detail section: product information loaded from data/items.xml -->
<section class="py-5">
    <div class="container">
        <?php if ($item === null): ?>
            <!-- Missing or invalid product -->
            <div class="row justify-content-center">
                <div class="col-12 col-lg-8">
                    <div class="alert alert-warning" role="alert">
                        <h1 class="h5">無法顯示產品</h1>
                        <p class="mb-3"><?php echo e($error); ?></p>
                        <a class="btn btn-primary" href="products.php">返回產品目錄</a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <?php
            $name = (string) $item->name;
            $category = (string) $item->category;
            $price = (float) $item->price;
            $summary = (string) $item->summary;
            $description = (string) $item->description;
            $image = (string) $item->image;
            $imageAlt = (string) $item->image_alt;
            $imagePath = __DIR__ . '/' . ltrim($image, '/');
            $imageUrl = (str_starts_with($image, 'assets/images/') && is_file($imagePath))
                ? $image
                : 'assets/images/logo/logo.png';
            ?>

            <!-- Breadcrumb navigation -->
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.php">首頁</a></li>
                    <li class="breadcrumb-item"><a href="products.php">產品目錄</a></li>
                    <li class="breadcrumb-item active" aria-current="page"><?php echo e($name); ?></li>
                </ol>
            </nav>

            <div class="row g-4 g-lg-5 align-items-start">
                <!-- Product image -->
                <div class="col-12 col-lg-6">
                    <div class="card border-0 bg-light">
                        <img class="img-fluid w-100" src="<?php echo e($imageUrl); ?>" alt="<?php echo e($imageAlt); ?>">
                    </div>
                </div>

                <!-- Product summary and price -->
                <div class="col-12 col-lg-6">
                    <span class="badge text-bg-secondary mb-3"><?php echo e($category); ?></span>
                    <h1 class="h2 mb-3"><?php echo e($name); ?></h1>
                    <p class="fs-3 fw-semibold mb-3"><?php echo e(format_price($price)); ?></p>
                    <p class="text-muted mb-4"><?php echo e($summary); ?></p>

                    <div class="d-grid gap-2 d-sm-flex">
                        <a class="btn btn-primary btn-lg" href="contact.php">查詢購買</a>
                        <a class="btn btn-outline-secondary btn-lg" href="products.php">返回產品目錄</a>
                    </div>
                </div>
            </div>

            <div class="row mt-5">
                <div class="col-12 col-lg-10">
                    <!-- Product description -->
                    <h2 class="h4 mb-3">產品介紹</h2>
                    <p class="mb-4"><?php echo nl2br(e($description)); ?></p>

                    <!-- Technical specification table -->
                    <h2 class="h4 mb-3">技術規格</h2>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <tbody>
                                <?php foreach ($item->specs->spec as $spec): ?>
                                    <tr>
                                        <th class="w-25 bg-light" scope="row"><?php echo e((string) $spec['name']); ?></th>
                                        <td><?php echo e((string) $spec); ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</section>

<?php require __DIR__ . '/includes/footer.php'; ?>

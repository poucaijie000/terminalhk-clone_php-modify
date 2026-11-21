<?php
declare(strict_types=1);
/**
 * Group: [Group Number]
 * Members: [Member Name] ([Student ID]), [Member Name] ([Student ID])
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * FAQ page. Questions and answers are read from data/faq.xml, with optional
 * category filtering and a keyword search. No FAQ text is hard-coded here.
 */

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';

if (!defined('FAQ_XML')) {
    define('FAQ_XML', __DIR__ . '/data/faq.xml');
}

/**
 * Keep only the simple HTML tags used by the FAQ answers.
 *
 * @param string $html Answer HTML stored in faq.xml.
 * @return string Filtered HTML.
 */
function faq_answer_html(string $html): string
{
    return strip_tags($html, '<p><br><strong><b><em><i><ul><ol><li><a><img>');
}

/**
 * Read the category list attached to one FAQ entry.
 *
 * @param SimpleXMLElement $faq FAQ node.
 * @return string[] Category names.
 */
function faq_categories(SimpleXMLElement $faq): array
{
    $categories = [];
    foreach ($faq->categories->category as $category) {
        $categories[] = (string) $category;
    }

    return $categories;
}

/**
 * Case-insensitive substring search that also works for Chinese text.
 *
 * @param string $haystack Text to search.
 * @param string $needle   Search term.
 * @return bool True when the term is found.
 */
function faq_contains(string $haystack, string $needle): bool
{
    if ($needle === '') {
        return true;
    }

    if (function_exists('mb_stripos')) {
        return mb_stripos($haystack, $needle, 0, 'UTF-8') !== false;
    }

    return stripos($haystack, $needle) !== false;
}

$categoryOptions = ['常見問題', '送貨服務', '付款說明', '售後支援', '產品退換', '購物流程', '除舊服務'];

$categoryParam = isset($_GET['category']) && is_string($_GET['category'])
    ? trim($_GET['category'])
    : '';
$selectedCategory = in_array($categoryParam, $categoryOptions, true) ? $categoryParam : '常見問題';

$search = isset($_GET['q']) && is_string($_GET['q']) ? trim($_GET['q']) : '';

$faqRoot = load_xml(FAQ_XML);
$faqNodes = [];
if ($faqRoot instanceof SimpleXMLElement) {
    foreach ($faqRoot->faq as $faq) {
        $faqNodes[] = $faq;
    }
}

usort($faqNodes, static function (SimpleXMLElement $a, SimpleXMLElement $b): int {
    $order = (int) $a['order'] <=> (int) $b['order'];
    if ($order !== 0) {
        return $order;
    }

    return strcmp((string) $a->question, (string) $b->question);
});

$visibleFaqs = [];
foreach ($faqNodes as $faq) {
    $categories = faq_categories($faq);

    if ($selectedCategory !== '常見問題' && !in_array($selectedCategory, $categories, true)) {
        continue;
    }

    if ($search !== '') {
        $question = (string) $faq->question;
        $answer = strip_tags((string) $faq->answer);
        if (!faq_contains($question, $search) && !faq_contains($answer, $search)) {
            continue;
        }
    }

    $visibleFaqs[] = $faq;
}

include 'includes/header.php';
?>

<!-- 主體內容 -->
<div class="container my-4">
    
    <!-- 麵包屑導航 -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">首頁</a></li>
            <li class="breadcrumb-item active text-dark" aria-current="page">常見問題</li>
        </ol>
    </nav>

    <!-- 常見問題主外框 -->
    <div class="row border rounded bg-white">
        
        <!-- === 左側選單 (佔 3 欄) === -->
        <div class="col-lg-3 border-end py-4">
            <ul class="list-unstyled contact-menu mb-0">
                <?php foreach ($categoryOptions as $category): ?>
                    <?php
                    $isActive = $category === $selectedCategory;
                    $categoryUrl = $category === '常見問題'
                        ? 'faq.php'
                        : 'faq.php?category=' . urlencode($category);
                    ?>
                    <li>
                        <a
                            href="<?php echo e($categoryUrl); ?>"
                            class="<?php echo $isActive ? 'active text-decoration-none' : 'text-decoration-none'; ?>"
                        >
                            <?php echo e($category); ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>

        <!-- === 右側內容區 (佔 9 欄) === -->
        <div class="col-lg-9 p-4 p-md-5">
            
            <!-- 區塊標題 -->
            <h4 class="mb-2 text-dark"><?php echo e($selectedCategory); ?></h4>
            <p class="text-muted small mb-4">共 <?php echo count($visibleFaqs); ?> 條問題</p>

            <!-- 關鍵字搜尋 -->
            <form method="get" action="faq.php" class="mb-4">
                <div class="input-group">
                    <?php if ($selectedCategory !== '常見問題'): ?>
                        <input type="hidden" name="category" value="<?php echo e($selectedCategory); ?>">
                    <?php endif; ?>
                    <input
                        type="search"
                        name="q"
                        class="form-control rounded-0"
                        placeholder="搜尋問題關鍵字，例如：送貨、保養"
                        value="<?php echo e($search); ?>"
                    >
                    <button class="btn btn-dark rounded-0" type="submit">搜尋</button>
                    <?php if ($search !== ''): ?>
                        <a
                            href="<?php echo e($selectedCategory === '常見問題' ? 'faq.php' : 'faq.php?category=' . urlencode($selectedCategory)); ?>"
                            class="btn btn-outline-secondary rounded-0"
                        >清除</a>
                    <?php endif; ?>
                </div>
            </form>

            <?php if ($visibleFaqs === []): ?>
                <div class="alert alert-info mb-0">沒有符合條件的問題，請嘗試其他關鍵字或分類。</div>
            <?php else: ?>
                <!-- 快速跳轉目錄 -->
                <ul class="text-dark mb-5" style="line-height: 2;">
                    <?php foreach ($visibleFaqs as $faq): ?>
                        <li>
                            <a href="#<?php echo e((string) $faq['id']); ?>" class="text-decoration-none text-dark">
                                <?php echo e((string) $faq->question); ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>

                <hr class="my-4">

                <!-- 問答列表 -->
                <div class="faq-content">
                    <?php foreach ($visibleFaqs as $faq): ?>
                        <div id="<?php echo e((string) $faq['id']); ?>" class="mb-4 pb-3 border-bottom">
                            <h5 class="text-dark"><?php echo e((string) $faq->question); ?></h5>
                            <?php echo faq_answer_html((string) $faq->answer); ?>
                        </div>
                    <?php endforeach; ?>

                    <hr class="my-4">
                    <p class="text-dark small mb-0">* 若閣下有任何疑問 歡迎聯絡我們</p>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php
// 引入底部
include 'includes/footer.php';
?>

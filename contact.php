<?php
declare(strict_types=1);
/**
 * Group: [Group Number]
 * Members: [Member Name] ([Student ID]), [Member Name] ([Student ID])
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Contact page. The online enquiry form validates its input, checks CSRF and
 * appends a new <enquiry> node to data/enquiries.xml through the shared XML
 * layer. Existing contact information and the sidebar behaviour stay unchanged.
 */

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';

if (!defined('ENQUIRIES_XML')) {
    define('ENQUIRIES_XML', __DIR__ . '/data/enquiries.xml');
}

$contactCategories = ['客戶服務', '商業資訊', '問題回饋'];
$errors = [];
$old = [
    'name' => '',
    'email' => '',
    'category' => '客戶服務',
    'subject' => '',
    'message' => '',
];
$success = flash('contact_success');

/**
 * Count characters in a way that works for both English and Chinese.
 *
 * @param string $value Text to measure.
 * @return int Character count.
 */
function contact_length(string $value): int
{
    return function_exists('mb_strlen') ? mb_strlen($value, 'UTF-8') : strlen($value);
}

/**
 * Append a text node without breaking on &, < or > in the submitted value.
 *
 * @param SimpleXMLElement $parent Parent XML node.
 * @param string           $name   Child node name.
 * @param string           $value  Text value.
 */
function contact_add_text(SimpleXMLElement $parent, string $name, string $value): void
{
    $child = $parent->addChild($name);
    $child[0] = $value;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $old['name'] = post_string('name');
    $old['email'] = post_string('email');
    $old['category'] = post_string('category');
    $old['subject'] = post_string('subject');
    $old['message'] = post_string('message');

    if (!in_array($old['category'], $contactCategories, true)) {
        $old['category'] = '客戶服務';
    }

    if (!verify_csrf()) {
        $errors['csrf'] = '表單已過期，請重新整理頁面後再提交。';
    }

    if (contact_length($old['name']) < 2 || contact_length($old['name']) > 60) {
        $errors['name'] = '聯絡人姓名必須為 2 至 60 個字元。';
    }

    if (!is_valid_email($old['email']) || contact_length($old['email']) > 120) {
        $errors['email'] = '請輸入有效的電郵地址。';
    }

    if (contact_length($old['subject']) < 3 || contact_length($old['subject']) > 100) {
        $errors['subject'] = '主題必須為 3 至 100 個字元。';
    }

    if (contact_length($old['message']) < 10 || contact_length($old['message']) > 2000) {
        $errors['message'] = '內容必須為 10 至 2000 個字元。';
    }

    if ($errors === []) {
        $root = load_xml(ENQUIRIES_XML);
        if (!$root instanceof SimpleXMLElement) {
            $root = new SimpleXMLElement('<enquiries/>');
        }

        $nextId = 1;
        foreach ($root->enquiry as $existing) {
            $nextId = max($nextId, ((int) $existing['id']) + 1);
        }

        $enquiry = $root->addChild('enquiry');
        $enquiry->addAttribute('id', (string) $nextId);
        contact_add_text($enquiry, 'name', $old['name']);
        contact_add_text($enquiry, 'email', $old['email']);
        contact_add_text($enquiry, 'category', $old['category']);
        contact_add_text($enquiry, 'subject', $old['subject']);
        contact_add_text($enquiry, 'message', $old['message']);
        contact_add_text($enquiry, 'status', 'new');
        contact_add_text($enquiry, 'created_at', date('Y-m-d H:i:s'));

        if (!save_xml($root, ENQUIRIES_XML)) {
            $errors['form'] = '暫時無法儲存你的查詢，請稍後再試。';
        } else {
            flash('contact_success', '你的查詢已提交，我們會盡快回覆。');
            redirect('contact.php#online-form');
        }
    }
}

include 'includes/header.php';
?>

<!-- 主體內容 -->
<div class="container my-4">
    
    <!-- 麵包屑導航 -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb" id="breadcrumb">
            <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">首頁</a></li>
            <li class="breadcrumb-item active text-dark" aria-current="page">聯絡我們</li>
        </ol>
    </nav>

    <!-- 聯絡我們主外框 -->
    <div class="row border rounded bg-white">
        
        <!-- === 左側選單 (佔 3 欄) === -->
        <div class="col-lg-3 border-end py-4">
            <ul class="list-unstyled contact-menu mb-0" id="contactMenu">
                <li><a href="#customer-service" class="active text-decoration-none" data-category="客戶服務">客戶服務</a></li>
                <li><a href="#commercial-info" class="text-decoration-none" data-category="商業資訊">商業資訊</a></li>
                <li><a href="#feedback" class="text-decoration-none" data-category="問題回饋">問題回饋</a></li>
            </ul>
        </div>

        <!-- === 右側內容區 (佔 9 欄) === -->
        <div class="col-lg-9 p-4 p-md-5">
            
            <!-- 區塊 1：客戶服務 -->
            <div class="mb-5">
                <h4 class="mb-4 text-dark">客戶服務</h4>
                <p class="text-dark mb-2">如果你有任何問題，歡迎隨時聯絡我們，我們會盡力以最短時間回覆。</p>
                <p class="text-dark mb-3">在聯絡前，我們建議閣下瀏覽 <a href="faq.php" class="text-decoration-none">常見問題</a> 來獲取最快速解決方案。</p>
                <ul class="text-dark mb-4" style="line-height: 1.8;">
                    <li>送貨服務</li>
                    <li>付款說明</li>
                    <li>支援保養</li>
                    <li>產品退換</li>
                    <li>購物流程</li>
                </ul>
            </div>

            <hr class="my-5">

            <!-- 區塊 2：WhatsApp 聯絡 -->
            <div class="mb-5">
                <h5 class="mb-3 text-dark">以 WhatsApp 聯絡我們</h5>
                <div class="d-flex align-items-center mb-3">
                    <!-- WhatsApp 綠色圖標 -->
                    <i class="bi bi-whatsapp text-success" style="font-size: 2.5rem; margin-right: 10px;"></i>
                    <span class="fs-3 text-dark fw-bold">64066698</span>
                    <span class="text-danger ms-2">(只限WhatsApp)</span>
                </div>
                <p class="text-dark mb-0">*服務時間為星期一 至 星期六 10：30 - 19：00 （星期日及公眾假期休息）</p>
            </div>

            <hr class="my-5">

            <!-- 區塊 3：公司地址 -->
            <div class="mb-5">
                <h4 class="mb-4 text-dark">公司地址</h4>
                <!-- Google Maps 佔位圖 (請替換為你自己的地圖截圖) -->
                <div class="mb-4 text-center">
                    <img src="assets/images/contact/map.png" alt="Google Maps" class="img-fluid border rounded" style="width: 100%; max-height: 400px; object-fit: cover;">
                    <!-- 如果你想用真實的 Google 地圖，可以把上面換成下面的 iframe：
                    <iframe src="https://www.google.com/maps/embed?pb=..." width="100%" height="400" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                    -->
                </div>
                
                <div class="row text-dark">
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold mb-3">客戶服務及維修部</h6>
                        <p class="mb-1">九龍長沙灣道742-748號 鴻昌工廠大廈 3/F A室</p>
                        <p class="mb-1">(荔枝角地鐵站B出口)</p>
                        <p class="mb-3">九龍, 香港</p>
                        <p>聯絡電話: 37047358 / 37047359</p>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="fw-bold mb-3">辦公時間:</h6>
                        <p class="mb-1">星期一 ~ 星期六 (公眾假期休息)</p>
                        <p>10:30 ~ 19:00</p>
                    </div>
                </div>
            </div>

            <hr class="my-5">

            <!-- 區塊 4：郵件客服 -->
            <div class="mb-5">
                <h4 class="mb-4 text-dark">郵件客服</h4>
                <p class="text-dark mb-3">郵件客服可在第一時間通過郵件解答閣下問題。</p>
                <p class="text-dark">請將問題發送郵件到 <a href="mailto:contact@terminalhk.com" class="text-decoration-none">contact@terminalhk.com</a>，工作人員會很快與您取得聯絡</p>
            </div>

            <hr class="my-5">

            <!-- 區塊 5：在線客服 (Facebook) -->
            <div class="mb-5">
                <h4 class="mb-4 text-dark">在線客服</h4>
                <p class="text-dark mb-4">我們在社交網絡設有在線客服，可為閣下以最直接方式解答問題。請訪問我們的社交網站發送信息</p>
                <!-- Facebook 按鈕 (請替換為你的圖片) -->
                <a href="https://www.facebook.com/" target="_blank">
                    <img src="assets/images/contact/find_us_facebook.png" alt="Find us on Facebook" style="height: 60px; object-fit: contain;">
                </a>
            </div>

            <hr class="my-5">

            <!-- 區塊 6：線上提問表單 -->
            <div class="mb-4" id="online-form">
                <h4 class="mb-4 text-dark">線上提問</h4>
                <p class="text-dark mb-4">您可通過下面表格直接提交問題。(請在提交問題前確認所輸入郵箱地址正確，以免造成不必要延誤)</p>

                <?php if ($success !== null): ?>
                    <div class="alert alert-success rounded-0" role="alert">
                        <?php echo e($success); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors['form'])): ?>
                    <div class="alert alert-danger rounded-0" role="alert">
                        <?php echo e($errors['form']); ?>
                    </div>
                <?php endif; ?>

                <?php if (isset($errors['csrf'])): ?>
                    <div class="alert alert-danger rounded-0" role="alert">
                        <?php echo e($errors['csrf']); ?>
                    </div>
                <?php endif; ?>

                <form action="contact.php#online-form" method="POST" novalidate>
                    <?php echo csrf_field(); ?>

                    <div class="row mb-3 align-items-start">
                        <label for="contactName" class="col-sm-2 col-form-label fw-bold text-dark text-end">聯絡人</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control rounded-0<?php echo isset($errors['name']) ? ' is-invalid' : ''; ?>"
                                id="contactName"
                                name="name"
                                value="<?php echo e($old['name']); ?>"
                                placeholder="聯絡人"
                                maxlength="60"
                                required
                            >
                            <?php if (isset($errors['name'])): ?>
                                <div class="invalid-feedback d-block"><?php echo e($errors['name']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label for="contactEmail" class="col-sm-2 col-form-label fw-bold text-dark text-end">郵箱</label>
                        <div class="col-sm-10">
                            <input
                                type="email"
                                class="form-control rounded-0<?php echo isset($errors['email']) ? ' is-invalid' : ''; ?>"
                                id="contactEmail"
                                name="email"
                                value="<?php echo e($old['email']); ?>"
                                placeholder="郵箱"
                                maxlength="120"
                                required
                            >
                            <?php if (isset($errors['email'])): ?>
                                <div class="invalid-feedback d-block"><?php echo e($errors['email']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-center">
                        <label for="contactCategory" class="col-sm-2 col-form-label fw-bold text-dark text-end">查詢類型</label>
                        <div class="col-sm-10">
                            <select class="form-select rounded-0" id="contactCategory" name="category">
                                <?php foreach ($contactCategories as $category): ?>
                                    <option value="<?php echo e($category); ?>"<?php echo $category === $old['category'] ? ' selected' : ''; ?>>
                                        <?php echo e($category); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label for="contactSubject" class="col-sm-2 col-form-label fw-bold text-dark text-end">主題</label>
                        <div class="col-sm-10">
                            <input
                                type="text"
                                class="form-control rounded-0<?php echo isset($errors['subject']) ? ' is-invalid' : ''; ?>"
                                id="contactSubject"
                                name="subject"
                                value="<?php echo e($old['subject']); ?>"
                                placeholder="主題"
                                maxlength="100"
                                required
                            >
                            <?php if (isset($errors['subject'])): ?>
                                <div class="invalid-feedback d-block"><?php echo e($errors['subject']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row mb-3 align-items-start">
                        <label for="contactMessage" class="col-sm-2 col-form-label fw-bold text-dark text-end">內容</label>
                        <div class="col-sm-10">
                            <textarea
                                class="form-control rounded-0<?php echo isset($errors['message']) ? ' is-invalid' : ''; ?>"
                                id="contactMessage"
                                name="message"
                                rows="6"
                                placeholder="內容"
                                maxlength="2000"
                                required
                            ><?php echo e($old['message']); ?></textarea>
                            <?php if (isset($errors['message'])): ?>
                                <div class="invalid-feedback d-block"><?php echo e($errors['message']); ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-2"></div>
                        <div class="col-sm-10">
                            <button type="submit" class="btn btn-primary rounded-1 px-4">提交</button>
                        </div>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const menuItems = document.querySelectorAll('#contactMenu a');
    const breadcrumb = document.getElementById('breadcrumb');

    // 處理左側選單點擊事件
    menuItems.forEach(item => {
        item.addEventListener('click', function(e) {
            e.preventDefault(); // 阻止網頁跳轉

            // 1. 移除所有選單的 active class
            menuItems.forEach(el => el.classList.remove('active'));
            // 2. 幫當前點擊的選單加上 active class (字體變藍色)
            this.classList.add('active');

            // 3. 獲取點擊的分類名稱
            const category = this.getAttribute('data-category');

            // 4. 動態更新麵包屑
            breadcrumb.innerHTML = `
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none text-muted">首頁</a></li>
                <li class="breadcrumb-item text-muted">聯絡我們</li>
                <li class="breadcrumb-item active text-dark" aria-current="page">${category}</li>
            `;
            
            // 5. (可選) 改變網址上的 Hash，讓用戶重新整理也不會丟失狀態
            window.history.pushState(null, null, '#' + this.getAttribute('href').split('#')[1]);
        });
    });

    // 處理「重新整理頁面」時，保持原本點選的分類
    if (window.location.hash) {
        const hash = window.location.hash;
        const targetItem = document.querySelector(`#contactMenu a[href="${hash}"]`);
        if (targetItem) {
            targetItem.click();
        }
    }
});
</script>

<?php
// 引入底部
include 'includes/footer.php';
?>
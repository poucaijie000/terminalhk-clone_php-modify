<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Registration page. The page validates the form on the browser and repeats
 * every check on the server before appending a new <user> node to
 * data/users.xml. Passwords are stored with password_hash().
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';

$current_page = 'register.php';
$page_title = '註冊 | 香港終端 TerminalHK';

$errors = [];
$values = [
    'name' => '',
    'email' => '',
    'password' => '',
    'confirm_password' => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['name'] = post_string('name');
    $values['email'] = normalize_email(post_string('email'));
    $values['password'] = post_raw('password');
    $values['confirm_password'] = post_raw('confirm_password');

    if (!verify_csrf()) {
        $errors[] = '表單已過期，請重新整理頁面再試一次。';
    } else {
        $nameError = validate_name($values['name']);
        if ($nameError !== null) {
            $errors[] = $nameError;
        }

        if ($values['email'] === '') {
            $errors[] = '請輸入 Gmail 地址。';
        } elseif (!is_valid_email($values['email'])) {
            $errors[] = 'Gmail 地址格式不正確。';
        } elseif (!is_gmail_address($values['email'])) {
            $errors[] = '此系統只接受 @gmail.com 地址。';
        }

        foreach (validate_password($values['password']) as $passwordError) {
            $errors[] = $passwordError;
        }

        if ($values['confirm_password'] !== $values['password']) {
            $errors[] = '兩次輸入的密碼不一致。';
        }

        if ($errors === []) {
            $users = load_xml(USERS_XML);
            if ($users === null) {
                $errors[] = '無法讀取使用者資料，請確認 data/users.xml 存在並可寫入。';
            } elseif (find_user_by_email($users, $values['email']) !== null) {
                $errors[] = '這個 Gmail 地址已經註冊。';
            } else {
                $user = $users->addChild('user');
                $user->addChild('id', (string) next_numeric_id($users, 'user'));
                $user->addChild('name', $values['name']);
                $user->addChild('email', $values['email']);
                $user->addChild('password_hash', password_hash($values['password'], PASSWORD_DEFAULT));
                $user->addChild('reset_code_hash', '');
                $user->addChild('reset_expires_at', '0');
                $user->addChild('created_at', date('c'));

                if (save_xml($users, USERS_XML)) {
                    flash('success', '註冊成功，請使用剛才的 Gmail 地址登入。');
                    redirect('login.php');
                }

                $errors[] = '註冊資料未能寫入，請檢查 data/ 資料夾的寫入權限。';
            }
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<!-- Registration section: account details and password policy -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-2">建立帳戶</h1>
                    <p class="text-muted mb-0">註冊後即可使用會員功能；密碼會以雜湊方式儲存。</p>
                </div>

                <?php if ($errors !== []): ?>
                    <!-- Server-side validation summary -->
                    <div class="alert alert-danger" role="alert">
                        <h2 class="h6 mb-2">請修正以下問題</h2>
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <form class="needs-validation" action="register.php" method="post" novalidate>
                            <?php echo csrf_field(); ?>

                            <!-- Name field -->
                            <div class="mb-3">
                                <label class="form-label" for="name">姓名</label>
                                <input
                                    class="form-control"
                                    id="name"
                                    name="name"
                                    type="text"
                                    value="<?php echo e($values['name']); ?>"
                                    minlength="2"
                                    maxlength="50"
                                    autocomplete="name"
                                    required
                                >
                                <div class="invalid-feedback">請輸入 2 到 50 個字元的姓名。</div>
                            </div>

                            <!-- Gmail field -->
                            <div class="mb-3">
                                <label class="form-label" for="email">Gmail 地址</label>
                                <input
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    type="email"
                                    value="<?php echo e($values['email']); ?>"
                                    placeholder="name@gmail.com"
                                    autocomplete="email"
                                    data-gmail-only="true"
                                    required
                                >
                                <div class="form-text">只接受 @gmail.com，忘記密碼的驗證碼會寄到這個地址。</div>
                                <div class="invalid-feedback">請輸入有效的 @gmail.com 地址。</div>
                            </div>

                            <!-- Password field -->
                            <div class="mb-3">
                                <label class="form-label" for="password">密碼</label>
                                <input
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    type="password"
                                    minlength="8"
                                    autocomplete="new-password"
                                    data-password-policy="true"
                                    required
                                >
                                <div class="form-text">至少 8 個字元，需包含大寫字母、小寫字母及數字，不可含空白。</div>
                                <div class="invalid-feedback">密碼未符合規則。</div>
                            </div>

                            <!-- Password confirmation field -->
                            <div class="mb-4">
                                <label class="form-label" for="confirm_password">確認密碼</label>
                                <input
                                    class="form-control"
                                    id="confirm_password"
                                    name="confirm_password"
                                    type="password"
                                    minlength="8"
                                    autocomplete="new-password"
                                    data-match-password="password"
                                    required
                                >
                                <div class="invalid-feedback">兩次輸入的密碼必須一致。</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg" type="submit">註冊</button>
                                <a class="btn btn-link" href="login.php">已經有帳戶？返回登入</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shared client-side validation for account forms -->
<script src="js/account.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>

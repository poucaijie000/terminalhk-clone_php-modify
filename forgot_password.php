<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Forgot-password page. Step 1 accepts a registered Gmail address and sends a
 * six-digit code through includes/mailer.php. Step 2 verifies the code and
 * replaces the stored password hash. The code is valid for 15 minutes.
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';
require_once __DIR__ . '/includes/mailer.php';

$current_page = 'forgot_password.php';
$page_title = '忘記密碼 | 香港終端 TerminalHK';

$errors = [];
$info = '';
$step = 'request';
$email = (string) ($_SESSION['reset_email'] ?? '');

if (isset($_GET['restart'])) {
    unset($_SESSION['reset_email'], $_SESSION['reset_requested_at'], $_SESSION['reset_attempts']);
    redirect('forgot_password.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = post_string('action');

    if (!verify_csrf()) {
        $errors[] = '表單已過期，請重新整理頁面再試一次。';
    } elseif ($action === 'request') {
        $email = normalize_email(post_string('email'));

        if ($email === '') {
            $errors[] = '請輸入註冊時使用的 Gmail 地址。';
        } elseif (!is_valid_email($email)) {
            $errors[] = 'Gmail 地址格式不正確。';
        } elseif (!is_gmail_address($email)) {
            $errors[] = '此系統只接受 @gmail.com 地址。';
        }

        if ($errors === []) {
            $users = load_xml(USERS_XML);
            if ($users === null) {
                $errors[] = '無法讀取使用者資料，請確認 data/users.xml 存在。';
            } else {
                $user = find_user_by_email($users, $email);
                if ($user === null) {
                    // Do not reveal whether an address is registered.
                    $info = '如果這個 Gmail 地址已經註冊，驗證碼會在幾分鐘內寄到該信箱。';
                } else {
                    $code = (string) random_int(100000, 999999);
                    $user->reset_code_hash = password_hash($code, PASSWORD_DEFAULT);
                    $user->reset_expires_at = (string) (time() + 900);

                    if (!save_xml($users, USERS_XML)) {
                        $errors[] = '驗證碼未能儲存，請檢查 data/ 資料夾的寫入權限。';
                    } elseif (!send_verification_code((string) $user->email, (string) $user->name, $code)) {
                        $user->reset_code_hash = '';
                        $user->reset_expires_at = '0';
                        save_xml($users, USERS_XML);
                        $errors[] = '驗證碼郵件寄送失敗，請檢查 includes/mail_config.local.php 的 SMTP 設定。';
                    } else {
                        $_SESSION['reset_email'] = (string) $user->email;
                        $_SESSION['reset_requested_at'] = time();
                        $_SESSION['reset_attempts'] = 0;
                        $email = (string) $user->email;
                        $step = 'reset';
                        $info = '驗證碼已寄到 ' . mask_email($email) . '，請在 15 分鐘內輸入。';
                    }
                }
            }
        }
    } elseif ($action === 'reset') {
        $email = (string) ($_SESSION['reset_email'] ?? '');
        $code = post_string('code');
        $newPassword = post_raw('new_password');
        $confirmPassword = post_raw('confirm_password');

        if ($email === '') {
            $errors[] = '重設時段已失效，請重新輸入 Gmail 地址取得驗證碼。';
        } else {
            $step = 'reset';

            if (preg_match('/^[0-9]{6}$/', $code) !== 1) {
                $errors[] = '請輸入 6 位數字驗證碼。';
            }

            foreach (validate_password($newPassword) as $passwordError) {
                $errors[] = $passwordError;
            }

            if ($confirmPassword !== $newPassword) {
                $errors[] = '兩次輸入的新密碼不一致。';
            }

            $attempts = (int) ($_SESSION['reset_attempts'] ?? 0) + 1;
            $_SESSION['reset_attempts'] = $attempts;
            if ($attempts > 5) {
                $errors[] = '嘗試次數過多，請重新取得新的驗證碼。';
            }

            if ($errors === []) {
                $users = load_xml(USERS_XML);
                if ($users === null) {
                    $errors[] = '無法讀取使用者資料，請確認 data/users.xml 存在。';
                } else {
                    $user = find_user_by_email($users, $email);
                    $expiresAt = $user !== null ? (int) $user->reset_expires_at : 0;
                    $codeHash = $user !== null ? (string) $user->reset_code_hash : '';

                    if (
                        $user === null
                        || $codeHash === ''
                        || $expiresAt < time()
                        || !password_verify($code, $codeHash)
                    ) {
                        $errors[] = '驗證碼不正確或已經過期。';
                    } else {
                        $user->password_hash = password_hash($newPassword, PASSWORD_DEFAULT);
                        $user->reset_code_hash = '';
                        $user->reset_expires_at = '0';

                        if (save_xml($users, USERS_XML)) {
                            unset($_SESSION['reset_email'], $_SESSION['reset_requested_at'], $_SESSION['reset_attempts']);
                            $step = 'done';
                            $info = '密碼已經更新，請使用新密碼登入。';
                        } else {
                            $errors[] = '密碼更新未能寫入，請檢查 data/ 資料夾的寫入權限。';
                        }
                    }
                }
            }
        }
    }
} elseif (isset($_SESSION['reset_email'], $_SESSION['reset_requested_at'])) {
    $step = 'reset';
    $email = (string) $_SESSION['reset_email'];
}

require __DIR__ . '/includes/header.php';
?>

<!-- Forgot-password section: request code or enter a new password -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-7">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-2">重設密碼</h1>
                    <p class="text-muted mb-0">驗證碼會寄到註冊時使用的 Gmail 地址。</p>
                </div>

                <?php if ($info !== ''): ?>
                    <!-- Neutral confirmation message -->
                    <div class="alert alert-info" role="alert"><?php echo e($info); ?></div>
                <?php endif; ?>

                <?php if ($errors !== []): ?>
                    <!-- Server-side reset error summary -->
                    <div class="alert alert-danger" role="alert">
                        <ul class="mb-0">
                            <?php foreach ($errors as $error): ?>
                                <li><?php echo e($error); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>

                <div class="card shadow-sm">
                    <div class="card-body p-4 p-md-5">
                        <?php if ($step === 'done'): ?>
                            <!-- Reset complete -->
                            <div class="text-center">
                                <h2 class="h5 mb-3">密碼已更新</h2>
                                <p class="text-muted">你現在可以使用新密碼登入 TerminalHK。</p>
                                <a class="btn btn-primary" href="login.php">前往登入</a>
                            </div>
                        <?php elseif ($step === 'reset'): ?>
                            <!-- Step 2: verification code and new password -->
                            <p class="text-muted">驗證碼已寄到 <strong><?php echo e(mask_email($email)); ?></strong>，15 分鐘內有效。</p>

                            <form class="needs-validation" action="forgot_password.php" method="post" novalidate>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="reset">

                                <!-- Verification code -->
                                <div class="mb-3">
                                    <label class="form-label" for="code">6 位驗證碼</label>
                                    <input
                                        class="form-control"
                                        id="code"
                                        name="code"
                                        type="text"
                                        inputmode="numeric"
                                        pattern="[0-9]{6}"
                                        maxlength="6"
                                        autocomplete="one-time-code"
                                        required
                                    >
                                    <div class="invalid-feedback">請輸入 6 位數字驗證碼。</div>
                                </div>

                                <!-- New password -->
                                <div class="mb-3">
                                    <label class="form-label" for="new_password">新密碼</label>
                                    <input
                                        class="form-control"
                                        id="new_password"
                                        name="new_password"
                                        type="password"
                                        minlength="8"
                                        autocomplete="new-password"
                                        data-password-policy="true"
                                        required
                                    >
                                    <div class="form-text">至少 8 個字元，需包含大寫字母、小寫字母及數字，不可含空白。</div>
                                    <div class="invalid-feedback">新密碼未符合規則。</div>
                                </div>

                                <!-- New password confirmation -->
                                <div class="mb-4">
                                    <label class="form-label" for="confirm_password">確認新密碼</label>
                                    <input
                                        class="form-control"
                                        id="confirm_password"
                                        name="confirm_password"
                                        type="password"
                                        minlength="8"
                                        autocomplete="new-password"
                                        data-match-password="new_password"
                                        required
                                    >
                                    <div class="invalid-feedback">兩次輸入的密碼必須一致。</div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" type="submit">確認重設</button>
                                    <a class="btn btn-link" href="forgot_password.php?restart=1">重新取得驗證碼</a>
                                </div>
                            </form>
                        <?php else: ?>
                            <!-- Step 1: registered Gmail address -->
                            <form class="needs-validation" action="forgot_password.php" method="post" novalidate>
                                <?php echo csrf_field(); ?>
                                <input type="hidden" name="action" value="request">

                                <div class="mb-4">
                                    <label class="form-label" for="email">註冊時使用的 Gmail 地址</label>
                                    <input
                                        class="form-control"
                                        id="email"
                                        name="email"
                                        type="email"
                                        value="<?php echo e($email); ?>"
                                        placeholder="name@gmail.com"
                                        autocomplete="email"
                                        data-gmail-only="true"
                                        required
                                    >
                                    <div class="invalid-feedback">請輸入有效的 @gmail.com 地址。</div>
                                </div>

                                <div class="d-grid gap-2">
                                    <button class="btn btn-primary btn-lg" type="submit">寄出驗證碼</button>
                                    <a class="btn btn-link" href="login.php">返回登入</a>
                                </div>
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Shared client-side validation for account forms -->
<script src="js/account.js"></script>

<?php require __DIR__ . '/includes/footer.php'; ?>

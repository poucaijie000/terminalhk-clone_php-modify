<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * Login page. Credentials are checked against data/users.xml with
 * password_verify(). A successful login regenerates the session id and stores
 * the user in $_SESSION["user"].
 */

declare(strict_types=1);

require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/includes/xml.php';

$current_page = 'login.php';
$page_title = '登入 | 香港終端 TerminalHK';

$errors = [];
$email = '';
$success = flash('success');

if (current_user() !== null) {
    require __DIR__ . '/includes/header.php';
    ?>
    <!-- Already signed-in section -->
    <section class="py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-md-8 col-lg-6">
                    <div class="card shadow-sm">
                        <div class="card-body p-4 p-md-5 text-center">
                            <h1 class="h4 mb-3">你已經登入</h1>
                            <p class="text-muted">目前登入的帳戶是 <?php echo e(current_user()['email']); ?>。</p>
                            <a class="btn btn-primary" href="index.php">返回首頁</a>
                            <a class="btn btn-outline-secondary" href="logout.php">登出</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <?php
    require __DIR__ . '/includes/footer.php';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = normalize_email(post_string('email'));
    $password = post_raw('password');

    if (!verify_csrf()) {
        $errors[] = '表單已過期，請重新整理頁面再試一次。';
    } else {
        if ($email === '') {
            $errors[] = '請輸入 Gmail 地址。';
        } elseif (!is_valid_email($email)) {
            $errors[] = 'Gmail 地址格式不正確。';
        }

        if (trim($password) === '') {
            $errors[] = '請輸入密碼。';
        }

        if ($errors === []) {
            $users = load_xml(USERS_XML);
            if ($users === null) {
                $errors[] = '無法讀取使用者資料，請確認 data/users.xml 存在。';
            } else {
                $user = find_user_by_email($users, $email);
                if ($user === null || !password_verify($password, (string) $user->password_hash)) {
                    $errors[] = 'Gmail 地址或密碼不正確。';
                } else {
                    session_regenerate_id(true);
                    $_SESSION['user'] = [
                        'id' => (int) $user->id,
                        'name' => (string) $user->name,
                        'email' => (string) $user->email,
                    ];
                    flash('success', '歡迎回來，' . (string) $user->name . '。');
                    redirect('index.php');
                }
            }
        }
    }
}

require __DIR__ . '/includes/header.php';
?>

<!-- Login section: email and password form -->
<section class="py-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-12 col-md-10 col-lg-6">
                <div class="text-center mb-4">
                    <h1 class="h3 mb-2">會員登入</h1>
                    <p class="text-muted mb-0">使用註冊時填寫的 Gmail 地址登入。</p>
                </div>

                <?php if ($success !== null): ?>
                    <!-- Registration or login success message -->
                    <div class="alert alert-success" role="alert"><?php echo e($success); ?></div>
                <?php endif; ?>

                <?php if ($errors !== []): ?>
                    <!-- Server-side login error summary -->
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
                        <form class="needs-validation" action="login.php" method="post" novalidate>
                            <?php echo csrf_field(); ?>

                            <!-- Email field -->
                            <div class="mb-3">
                                <label class="form-label" for="email">Gmail 地址</label>
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
                                <div class="invalid-feedback">請輸入有效的 Gmail 地址。</div>
                            </div>

                            <!-- Password field -->
                            <div class="mb-4">
                                <label class="form-label" for="password">密碼</label>
                                <input
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    type="password"
                                    minlength="1"
                                    autocomplete="current-password"
                                    required
                                >
                                <div class="invalid-feedback">請輸入密碼。</div>
                            </div>

                            <div class="d-grid gap-2">
                                <button class="btn btn-primary btn-lg" type="submit">登入</button>
                                <a class="btn btn-outline-secondary" href="register.php">建立新帳戶</a>
                                <a class="btn btn-link" href="forgot_password.php">忘記密碼？</a>
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

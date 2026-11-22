<?php
/**
 * Group: 5
 * Members: Jacky (23577648), James (25530933), Tsoi Kit (25525116)
 * Date: 2026-11-22
 * Assignment: Final Project Part II - TerminalHK
 *
 * GameBox3 and GameBox3+ landing page with specifications and service promises.
 */


// Include header
include 'includes/header.php';
?>

<div class="gamebox-page">
    <div class="container gamebox-container">

        <!-- === In-page sub-navigation === -->
        <div class="game-menu d-flex flex-wrap align-items-end justify-content-between">
            <h3 class="game-menu-title mb-0">GameBox3</h3>
            <div class="game-menu-links d-flex flex-wrap align-items-center">
                <a href="#design" class="game-anchor">設計理念</a>
                <a href="#feature" class="game-anchor">電腦特點</a>
                <a href="#compare" class="game-anchor">產品比較</a>
                <a href="#spec" class="game-anchor">技術規格</a>
                <button type="button" class="btn btn-primary game-buy-btn" data-bs-toggle="modal" data-bs-target="#stockModal">
                    前往購買(缺貨)
                </button>
            </div>
        </div>
        <hr class="game-hr">

        <!-- === Design philosophy === -->
        <section id="design" class="game-section first-section text-center">
            <h1 class="game-h1">GameBox3</h1>
            <p class="game-subtitle">只為最佳遊戲體驗</p>
            <img src="img/gamebox/top.jpg" class="img-fluid game-hero-img" alt="GameBox3 遊戲電腦主視覺">
        </section>

        <hr class="game-hr">

        <!-- === GAME introduction === -->
        <section class="game-section text-center">
            <h2 class="game-h2">GAME</h2>
            <p class="game-subtitle">真正為遊戲度身設計</p>
            <p class="game-lead">
                香港終端專為玩家度身設計GameBox砌機組合，精心挑選專業遊戲硬件來最大限度提升遊戲體驗。選用頂級顯示卡以展現遊戲最高品質畫面，
                採用最新版處理器及大容量記憶體以提升多任務能力及系統整體性能使遊戲運行穩定流暢，同時高速硬碟可大幅減少遊戲啟動讀取時間，
                讓你不再因電腦延遲而分心，在超高品質流暢畫面下，享受完美遊戲體驗！
            </p>
            <img src="img/gamebox/mh.jpg" class="img-fluid game-wide-img" alt="遊戲畫面示範">
        </section>

        <hr class="game-hr">

        <!-- === PC features === -->
        <section id="feature" class="game-section">

            <!-- Processor -->
            <div class="row align-items-center g-4 feature-row">
                <div class="col-lg-6 feature-text">
                    <h2 class="game-h2 text-center">第9代Intel處理器</h2>
                    <p class="game-subtitle text-center">最新處理器平台</p>
                    <p>
                        採用Intel最新第9代Coffeelake多核心處理器，i5提供六核心、i7更提供八核心，為你帶來更快速度，提升L3快取的大小，
                        更輕鬆同時處理更多事物。無論是上網、看電影，還是工作、遊戲，一顆絕對強勁的「核心」都將讓你事半功倍！
                    </p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="img/gamebox/intel.png" class="img-fluid feature-img" alt="Intel 第9代 Core 處理器">
                </div>
            </div>

            <hr class="game-hr my-4">

            <!-- Graphics card -->
            <div class="row align-items-center g-4 feature-row">
                <div class="col-lg-6 order-2 order-lg-1 text-center">
                    <img src="img/gamebox/nvidia.jpg" class="img-fluid feature-img" alt="NVIDIA 遊戲顯示卡">
                </div>
                <div class="col-lg-6 order-1 order-lg-2 feature-text">
                    <h2 class="game-h2 text-center">最新頂級遊戲顯示卡</h2>
                    <p class="game-subtitle text-center">超凡畫質、極致遊戲體驗</p>
                    <p>
                        顯示卡是對遊戲性能影響最大硬件之一，直接決定最高特效等級及畫面流暢度。香港終端最新Nvidia／Radeon顯示卡，
                        採用最新架構，不僅性能大幅提升，功耗及散熱亦得到增強，配合大容量GDDR5記憶體（可升級至GDDR6），
                        可提高畫質，帶來更流暢遊戲畫面。在遊戲中搶占先機，體驗前所未有視覺體驗，展現你的實力。
                    </p>
                </div>
            </div>

            <hr class="game-hr my-4">

            <!-- Storage -->
            <div class="row align-items-center g-4 feature-row">
                <div class="col-lg-6 feature-text">
                    <h2 class="game-h2 text-center">極速大容量儲存器</h2>
                    <p class="game-subtitle text-center">1TB - 7200高轉速 - SATA 3</p>
                    <p>
                        GameBox配備1TB大容量硬碟，可輕鬆滿足對存儲遊戲、音樂、電影文件的需求。使用具有6Gb/s最大連接速度SATA 3接口，
                        7200rpm高轉速硬碟，並且根據使用需要，即時將數據存入可大幅提高讀寫速度的32M快取記憶體，減少遊戲讀取時間，
                        在起跑線領先於人！選擇使用SSD的GameBox更有超過傳統硬碟7倍讀寫速度，讓你從電腦啟動便感受極速！
                    </p>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="img/gamebox/sshd.jpg" class="img-fluid feature-img" alt="SSD 與 HDD 硬碟">
                </div>
            </div>

            <!-- Speed comparison -->
            <div class="speed-compare">
                <p class="speed-title text-center">最高順序讀寫速度比較</p>

                <div class="row align-items-center g-2 speed-row">
                    <div class="col-md-3 text-md-end speed-label">固態硬碟(SSD)</div>
                    <div class="col-md-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-success progress-bar-striped progress-bar-animated"
                                 role="progressbar" style="width: 100%" aria-valuenow="100" aria-valuemin="0" aria-valuemax="100">
                                550MB/秒
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row align-items-center g-2 speed-row">
                    <div class="col-md-3 text-md-end speed-label">傳統5400rpm硬碟(HDD)</div>
                    <div class="col-md-9">
                        <div class="progress">
                            <div class="progress-bar progress-bar-danger progress-bar-striped"
                                 role="progressbar" style="width: 14%" aria-valuenow="14" aria-valuemin="0" aria-valuemax="100">
                                80MB/秒
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr class="game-hr my-4">

            <!-- Memory -->
            <div class="row align-items-center g-4 feature-row">
                <div class="col-lg-6 order-2 order-lg-1 text-center">
                    <img src="img/gamebox/ram.png" class="img-fluid feature-img" alt="DDR4 記憶體">
                </div>
                <div class="col-lg-6 order-1 order-lg-2 feature-text">
                    <h2 class="game-h2 text-center">高速DDR4記憶體</h2>
                    <p class="game-subtitle text-center">輕鬆處理更多事物</p>
                    <p>
                        配備8GB DDR4 2666MHz高頻記憶體，讓你輕鬆運行更多應用程序，同時遊戲、聊天、上網、聽歌，
                        不必再為記憶體不夠而煩惱！
                    </p>
                </div>
            </div>

        </section>

        <hr class="game-hr">

        <!-- === Product comparison === -->
        <div id="compare" aria-hidden="true"></div>

<hr class="game-hr">

        <!-- === Technical specifications === -->
        <section id="spec" class="game-section">
            <h2 class="game-h2 text-center">技術規格</h2>

            <div class="table-responsive spec-wrap">
                <table class="game-spec-table">
                    <colgroup>
                        <col style="width: 14%">
                        <col style="width: 5%">
                        <col style="width: 40.5%">
                        <col style="width: 40.5%">
                    </colgroup>

                    <tr class="spec-head">
                        <td class="spec-label"></td>
                        <td class="spec-spacer"></td>
                        <td>GameBox3</td>
                        <td>GameBox3+</td>
                    </tr>

                    <tr class="spec-img-row">
                        <td></td>
                        <td></td>
                        <td><img src="img/gamebox/C450M.jpg" alt="GameBox3"></td>
                        <td><img src="img/gamebox/SF30.jpg" alt="GameBox3+"></td>
                    </tr>

                    <tr>
                        <th>價格</th>
                        <td class="spec-spacer"></td>
                        <td class="spec-price">$3799</td>
                        <td class="spec-price">$6699</td>
                    </tr>

                    <tr>
                        <th>處理器</th>
                        <td class="spec-spacer"></td>
                        <td>
                            Intel® Core™ CoffeeLake i3 Processor 四核心
                            <span class="spec-upgrade">可升至最高 Intel® Core™ CoffeeLake i5 Processor 六核心</span>
                        </td>
                        <td>
                            Intel® Core™ CoffeeLake i5 Processor 六核心
                            <span class="spec-upgrade">可升至最高 Intel® Core™ CoffeeLake i7 Processor</span>
                        </td>
                    </tr>

                    <tr>
                        <th>顯示卡</th>
                        <td class="spec-spacer"></td>
                        <td>
                            Nvidia Geforce GTX 1650 GDDR5 2GB 獨立顯示卡
                            <span class="spec-upgrade">可升至最高 Nvidia Geforce RTX 2060 GDDR5 6GB 獨立顯示卡</span>
                        </td>
                        <td>
                            Nvidia Geforce RTX 2060 GDDR5 6GB 獨立顯示卡
                            <span class="spec-upgrade">可升至最高 Nvidia Geforce RTX 2080 GDDR5X 8GB 獨立顯示卡</span>
                        </td>
                    </tr>

                    <tr>
                        <th>儲存設備</th>
                        <td class="spec-spacer"></td>
                        <td>
                            1TB 硬碟(HDD)
                            <span class="spec-upgrade">可添加最多500GB固態硬碟(SSD)<br>可添加最多2TB硬碟(HDD)</span>
                        </td>
                        <td>
                            1TB 硬碟(HDD)<br>
                            240GB 固態硬碟(SSD)
                            <span class="spec-upgrade">可添加最多500GB固態硬碟(SSD)<br>可添加最多2TB硬碟(HDD)</span>
                        </td>
                    </tr>

                    <tr>
                        <th>記憶體</th>
                        <td class="spec-spacer"></td>
                        <td>
                            8GB (1 x 8GB) DDR4 2666Mhz
                            <span class="spec-upgrade">可升至最高 16GB (2 x 8GB) DDR4 2666Mhz</span>
                        </td>
                        <td>
                            16GB (2 x 8GB) DDR4 2666Mhz
                            <span class="spec-upgrade">可升至最高 32GB (2 x 16GB) DDR4 2666Mhz</span>
                        </td>
                    </tr>

                    <tr>
                        <th>光碟機</th>
                        <td colspan="3">可添加外置DVD機</td>
                    </tr>

                    <tr>
                        <th>網絡</th>
                        <td colspan="3">
                            千兆網卡 10/100/1000 Mb/s
                            <span class="spec-upgrade">可添加 300Mbps 無線網卡</span>
                        </td>
                    </tr>

                    <tr>
                        <th>音效</th>
                        <td colspan="3">5.1 聲道高傳真音效</td>
                    </tr>

                    <tr>
                        <th>作業系統</th>
                        <td colspan="3">
                            無
                            <span class="spec-upgrade">可選擇安裝 Windows 10</span>
                        </td>
                    </tr>

                    <tr>
                        <th>支援保養</th>
                        <td colspan="3">
                            5年網上支援
                            <span class="spec-upgrade">可升級購買最多額外3年上門收機服務</span>
                        </td>
                    </tr>

                    <tr>
                        <th>尺寸</th>
                        <td class="spec-spacer"></td>
                        <td>21.0 x 42.2 x 39.5cm (W x H x D)</td>
                        <td>20.5 x 48.0 x 41.5cm (W x H x D)</td>
                    </tr>

                    <tr>
                        <th>顏色</th>
                        <td class="spec-spacer"></td>
                        <td>簡約版</td>
                        <td>RGB四風扇版</td>
                    </tr>

                    <tr>
                        <th>機箱</th>
                        <td class="spec-spacer"></td>
                        <td>全鋼化玻璃側板</td>
                        <td>全鋼化玻璃側板</td>
                    </tr>
                </table>
            </div>
        </section>

    </div>
</div>

<!-- === Out-of-stock modal === -->
<div class="modal fade" id="stockModal" tabindex="-1" aria-labelledby="stockModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-0">
            <div class="modal-header">
                <h5 class="modal-title" id="stockModalLabel">GameBox 系列</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="關閉"></button>
            </div>
            <div class="modal-body">
                GameBox3 及 GameBox3+ 目前缺貨，補貨後會盡快於產品目錄上架。
            </div>
            <div class="modal-footer">
                <a href="products.php" class="btn btn-primary">瀏覽其他產品</a>
                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">關閉</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Smooth scrolling for the in-page navigation
    const anchors = document.querySelectorAll('.gamebox-page .game-anchor');
    anchors.forEach(function (link) {
        link.addEventListener('click', function (e) {
            const target = document.querySelector(this.getAttribute('href'));
            if (!target) return;
            e.preventDefault();
            const top = target.getBoundingClientRect().top + window.scrollY - 20;
            window.scrollTo({ top: top, behavior: 'smooth' });
        });
    });

    // Highlight the navigation item for the section currently in view
    const sections = Array.prototype.slice.call(document.querySelectorAll('.gamebox-page section[id]'));

    function updateActive() {
        if (!sections.length) return;
        const offset = window.scrollY + 120;
        let current = sections[0];
        sections.forEach(function (section) {
            if (section.getBoundingClientRect().top + window.scrollY <= offset) {
                current = section;
            }
        });
        anchors.forEach(function (a) {
            a.classList.toggle('active', a.getAttribute('href') === '#' + current.id);
        });
    }

    window.addEventListener('scroll', updateActive, { passive: true });
    updateActive();
});
</script>

<?php
// Include footer
include 'includes/footer.php';
?>

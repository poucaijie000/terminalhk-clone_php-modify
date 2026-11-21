<?php
// 引入頭部
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
                <li><a href="#" class="active text-decoration-none">常見問題</a></li>
                <li><a href="#" class="text-decoration-none">送貨服務</a></li>
                <li><a href="#" class="text-decoration-none">付款說明</a></li>
                <li><a href="#" class="text-decoration-none">售後支援</a></li>
                <li><a href="#" class="text-decoration-none">產品退換</a></li>
                <li><a href="#" class="text-decoration-none">購物流程</a></li>
                <li><a href="#" class="text-decoration-none">除舊服務</a></li>
            </ul>
        </div>

        <!-- === 右側內容區 (佔 9 欄) === -->
        <div class="col-lg-9 p-4 p-md-5">
            
            <!-- 區塊標題 -->
            <h4 class="mb-4 text-dark">常見問題</h4>
            
            <!-- 快速跳轉目錄 -->
            <ul class="text-dark mb-5" style="line-height: 2;">
                <li><a href="#q1" class="text-decoration-none text-dark">你們是否有門市？</a></li>
                <li><a href="#q2" class="text-decoration-none text-dark">產品壞了如何保養？</a></li>
                <li><a href="#q3" class="text-decoration-none text-dark">可以送到香港以外地區嗎？</a></li>
                <li><a href="#q4" class="text-decoration-none text-dark">什麼是砌機服務？</a></li>
                <li><a href="#q5" class="text-decoration-none text-dark">購買電腦組合是否包Windows？</a></li>
                <li><a href="#q6" class="text-decoration-none text-dark">購買電腦組合是否包Cable？</a></li>
                <li><a href="#q7" class="text-decoration-none text-dark">送貨時 是否會有產品包裝，配件，說明書，保養證明，光碟驅動？</a></li>
                <li><a href="#q8" class="text-decoration-none text-dark">購買電腦組合是否有風扇？</a></li>
                <li><a href="#q9" class="text-decoration-none text-dark">送貨時會幫你們駁線嗎？</a></li>
                <li><a href="#q10" class="text-decoration-none text-dark">為何訂單一直顯示為處理中，是否正常？</a></li>
                <li><a href="#q11" class="text-decoration-none text-dark">免費送貨有最低消費限制嗎？</a></li>
                <li><a href="#q12" class="text-decoration-none text-dark">如果我現在下單，幾時可以收到產品？</a></li>
                <li><a href="#q13" class="text-decoration-none text-dark">可以分期付款嗎？</a></li>
                <li><a href="#q14" class="text-decoration-none text-dark">送貨時不在家怎麼辦？</a></li>
                <li><a href="#q15" class="text-decoration-none text-dark">可以由家人代收嗎？</a></li>
                <li><a href="#q16" class="text-decoration-none text-dark">我住的地方沒有電梯，是否都送到門口？</a></li>
                <li><a href="#q17" class="text-decoration-none text-dark">購物需要支付定金嗎？</a></li>
                <li><a href="#q18" class="text-decoration-none text-dark">新買電腦，駁線後發現顯示器沒畫面 (no signal), 怎麼辦？</a></li>
                <li><a href="#q19" class="text-decoration-none text-dark">自己裝Windows，為何看不到另外一塊Hard Disk？</a></li>
                <li><a href="#q20" class="text-decoration-none text-dark">我有舊HDD/DVD，送貨時可以幫我安裝嗎？</a></li>
                <li><a href="#q21" class="text-decoration-none text-dark">訂單顯示裝箱時間很久了，正常嗎？</a></li>
                <li><a href="#q22" class="text-decoration-none text-dark">為什麼安裝Windows後無法上網？</a></li>
            </ul>

            <hr class="my-4">

            <!-- 問答列表 -->
            <div class="faq-content">
                
                <div id="q1" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">你們是否有門市？</h5>
                    <p class="text-dark mb-0">香港終端為網上購物平台，未設有門市。</p>
                </div>

                <div id="q2" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">產品壞了如何保養？</h5>
                    <p class="text-dark mb-0">產品出現問題，請不要擔心。我們會盡全力為閣下解決問題。香港終端為客戶提供上門收機服務，免費線上支援，自攜機檢查服務等。同樣，閣下可直接聯絡產品代理享受代理保養。了解更多信息，請查看這裡 [ 售後支援 ]</p>
                </div>

                <div id="q3" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">可以送到香港以外地區嗎？</h5>
                    <p class="text-dark mb-0">送貨服務暫時只覆蓋香港地區。其他地區客人可聯絡我們到倉庫自取商品</p>
                </div>

                <div id="q4" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">什麼是砌機服務？</h5>
                    <p class="text-dark mb-0">$199享有砌機服務 包括組裝、首1個月硬件質量問題齊盒可安排換新、首2個月上門收機服務 (僅限硬件問題)及五年免費自攜檢查服務。</p>
                </div>

                <div id="q5" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">購買電腦組合是否包Windows？</h5>
                    <p class="text-dark mb-2">全部電腦組合在未特殊說明情況下未包含Windows。因為很多客人自己有安裝光碟或USB手指，而Windows價錢較高，因為我們不會強迫捆綁銷售</p>
                    <p class="text-dark mb-0">香港終端遵守香港法律，受到代理規管，只可以使用由香港代理授權的正版軟件<br>為避免日後爭議，不在香港終端購買軟件將不能提供安裝服務</p>
                </div>

                <div id="q6" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">購買電腦組合是否包Cable？</h5>
                    <p class="text-dark mb-2">購買含火牛的砌機組合時，會贈送火牛線。<br>
                    購買顯示器時，不同型號，原廠將提供不同種類顯示器線 (VGA/DVI/HDMI/Display Port)，如不了解內含顯示器線是否適用於您的電腦，請聯絡我們客服人員。<br>
                    購買Router時通常會贈送短Lan線</p>
                </div>

                <div id="q7" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">送貨時 是否會有產品包裝，配件，說明書，保養證明，光碟驅動？</h5>
                    <p class="text-dark mb-0">購買產品後，我們會將全部產品包裝，配件，說明書，保養證明，光碟驅動一起發送。</p>
                </div>

                <div id="q8" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">購買電腦組合是否有風扇？</h5>
                    <p class="text-dark mb-0">一般情況下購買電腦組合會贈送原廠風扇。無須另行購買。特殊型號產品可能未包含。(如高階CPU)</p>
                </div>

                <div id="q9" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">送貨時會幫你們駁線嗎？</h5>
                    <p class="text-dark mb-0">因並非全部送貨同事了解電腦，我們暫時不能保證一定會為閣下駁線。閣下可要求自己駁線試過後才簽收，送貨同事可等待閣下試機後才簽收</p>
                </div>

                <div id="q10" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">為何訂單一直顯示為處理中，是否正常？</h5>
                    <p class="text-dark mb-0">訂單在創建後即會轉為處理中，直至已簽收。屬於正常現象</p>
                </div>

                <div id="q11" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">免費送貨有最低消費限制嗎？</h5>
                    <p class="text-dark mb-0">香港終端為全部商品提供免費送貨，並無最低消費限制</p>
                </div>

                <div id="q12" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">如果我現在下單，幾時可以收到產品？</h5>
                    <p class="text-dark mb-0">香港終端現有三種送貨方式，免費送貨時間下可能在1-5日內收到產品，$50保證3日內送達，$150保證即日或最遲第二日夜晚送達。若有缺貨(如缺貨)工作人員會在第一時間聯絡閣下以告知延遲。由於訂單較多，實際交通狀況複雜，突發情況很多，具體時間通常比較難保證</p>
                </div>

                <div id="q13" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">會有分期付款嗎？</h5>
                    <p class="text-dark mb-0">暫時未有分期付款，相信會在不未來提供此服務</p>
                </div>

                <div id="q14" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">送貨時不在家怎麼辦？</h5>
                    <p class="text-dark mb-0">送貨同事會在送貨前提前通過電話方式聯絡，以確保有人收貨。若收件人未能收貨，可委託家人代收。若無法聯絡客人或無人收貨，公司會再安排下次盡快送貨。</p>
                </div>

                <div id="q15" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">可以由家人代收嗎？</h5>
                    <p class="text-dark mb-0">如若家人能夠幫忙核對貨品較佳。</p>
                </div>

                <div id="q16" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">我住的地方沒有電梯，是否都送到門口？</h5>
                    <p class="text-dark mb-0">香港終端送貨服務不包括行樓梯；若未有電梯，可安排在樓下交收(PayPal付款訂單無法於樓下交收)或與送貨同事商討有關個人服務及收費。</p>
                </div>

                <div id="q17" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">購物需要支付定金嗎？</h5>
                    <p class="text-dark mb-0">購物無需訂金。</p>
                </div>

                <div id="q18" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">新買電腦，駁線後發現顯示器沒畫面 (no signal), 怎麼辦？</h5>
                    <p class="text-dark mb-3">為避免用戶收貨後無法使用，及付出不必要時間/金錢，香港終端全部產品都會在測試通過後才發貨。沒有畫面多數由顯示器線駁錯位置造成。<br>當電腦擁有獨立顯示卡時，請將顯示器線連接獨立顯示卡。如下圖所示:</p>
                    
                    <!-- 請替換為你的圖片路徑 -->
                    <img src="assets/images/faq/no_signal.png" alt="顯示器接駁位置" class="img-fluid border rounded mb-3" style="max-height: 400px;">
                    
                    <p class="text-dark mb-2">因VGA(藍色頭)線規格較舊，很多顯示卡已不再支持，建議購買相應高清線使用。<br>若閣下顯示器沒有相應插口，可購買VGA-DVI轉換頭使用</p>
                    <p class="text-dark mb-0">若閣下不了解顯示器有沒有相應插口，歡迎提供圖片並與我們Facebook 或 WhatsApp客服中人員聯絡。</p>
                </div>

                <div id="q19" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">自己裝Windows，為何看不到另外一塊Hard Disk？</h5>
                    <p class="text-dark mb-2">新Windows無法見到全部HDD通常由於新HDD未被分配磁碟所致。可通過以下步驟解決:</p>
                    <ol class="text-dark mb-0">
                        <li>於工作列「開始」右鍵選擇磁碟管理</li>
                        <li>若彈出初始化磁碟畫面，請選擇GPT模式</li>
                        <li>找到磁碟，查看是否有未配置空間</li>
                        <li>滑鼠右鍵點擊未配置空間，跟隨提示完成分配HDD</li>
                    </ol>
                </div>

                <div id="q20" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">我有舊HDD/DVD，送貨時可以幫我安裝嗎？</h5>
                    <p class="text-dark mb-0">我們暫時未有上門安裝服務，安裝HDD/DVD相當簡單，閣下可通過Facebook 或 WhatsApp方式聯絡客服人員，我們會遠程協助閣下安裝舊硬件。</p>
                </div>

                <div id="q21" class="mb-4 pb-3 border-bottom">
                    <h5 class="text-dark">訂單顯示裝箱時間很久了，正常嗎？</h5>
                    <p class="text-dark mb-0">裝箱包含配貨/組裝/測試等眾多環節。通常時間較長，屬於正常現象。</p>
                </div>

                <div id="q22" class="mb-4 pb-3">
                    <h5 class="text-dark">為什麼安裝Windows後無法上網？</h5>
                    <p class="text-dark mb-0">新安裝Windows後需要安裝驅動程式才可正常使用硬件。<br>若在香港終端購買砌機組合，工作人員會將驅動程式碟放入底板盒中。<br>只需依次安裝底板驅動程式/顯示卡驅動程式，電腦便可正常使用</p>
                </div>
                
                <hr class="my-4">
                <p class="text-dark small mb-0">* 若閣下有任何疑問 歡迎聯絡我們</p>
            </div>

        </div>
    </div>
</div>

<?php
// 引入底部
include 'includes/footer.php';
?>
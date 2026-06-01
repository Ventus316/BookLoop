// 檔名：assets/script.js
// 集中存放全站共用的 jQuery 互動程式碼

$(document).ready(function() {

    // ==========================================
    // 1. 登入與註冊頁面 (Auth)
    // ==========================================
    $('#loginForm').submit(function(e) {
        let email = $('input[name="uemail"]').val().trim();
        let password = $('input[name="upassword"]').val().trim();
        if (email === '' || password === '') {
            e.preventDefault();
            alert('請完整填寫信箱與密碼！');
        }
    });

    $('#registerForm').submit(function(e) {
        let password = $('input[name="upassword"]').val();
        if (password.length < 6) {
            e.preventDefault();
            alert('為了安全起見，密碼長度不可少於 6 位數！');
        }
    });

    // ==========================================
    // 2. 我要捐書頁面 (Donate)
    // ==========================================
    $('#bimage').change(function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#image-preview').attr('src', e.target.result).removeClass('hidden');
                $('#upload-prompt').addClass('hidden');
            }
            reader.readAsDataURL(file);
        }
    });

    $('#btn-fetch-isbn').click(function() {
        let isbn = $('#bisbn').val().trim();
        if(isbn === '') { alert('請先輸入 ISBN 號碼！'); return; }
        
        let btn = $(this);
        let originalText = btn.text();
        btn.text('抓取中...').prop('disabled', true);

        setTimeout(function() {
            $('#btitle').val('網頁程式設計：PHP & MySQL 實戰 (模擬抓取)');
            $('#bauthor').val('張教授');
            btn.text('抓取成功').removeClass('bg-green-100 text-brand').addClass('bg-brand text-white');
            setTimeout(() => {
                btn.text(originalText).removeClass('bg-brand text-white').addClass('bg-green-100 text-brand').prop('disabled', false);
            }, 2000);
        }, 800);
    });

    $('#donateForm').submit(function(e) {
        if($('#image-preview').hasClass('hidden')) {
            e.preventDefault();
            alert('請務必上傳書籍實體照片！');
        }
    });

    // ==========================================
    // 3. 書籍詳情頁面 (Book Detail)
    // ==========================================

    // ==========================================
    // 4. 個人管理後臺 (User Panel)
    // ==========================================
    $('.panel-tab').click(function() {
        $('.panel-tab').removeClass('border-brand text-brand font-bold').addClass('border-transparent text-gray-400 font-medium');
        $(this).removeClass('border-transparent text-gray-400 font-medium').addClass('border-brand text-brand font-bold');
        $('.panel-content').addClass('hidden');
        let tabId = $(this).attr('id');
        if (tabId === 'tab-donated') $('#content-donated').removeClass('hidden');
        if (tabId === 'tab-collected') $('#content-collected').removeClass('hidden');
        if (tabId === 'tab-received') $('#content-received').removeClass('hidden');
    });

    $('.btn-delete-book').click(function() {
        let trRow = $(this).closest('tr');
        if (confirm('確定要永久刪除這本已捐贈的書籍資料嗎？')) {
            trRow.fadeOut(400, function() { $(this).remove(); });
        }
    });

    // ==========================================
    // 5. 管理員後臺 (Admin Panel)
    // ==========================================
    $('.admin-tab').click(function() {
        $('.admin-tab').removeClass('border-admin text-admin font-bold').addClass('border-transparent text-gray-400 font-medium');
        $(this).removeClass('border-transparent text-gray-400 font-medium').addClass('border-admin text-admin font-bold');
        $('.admin-content').addClass('hidden');
        let tabId = $(this).attr('id');
        if (tabId === 'tab-books') $('#content-books').removeClass('hidden');
        if (tabId === 'tab-categories') $('#content-categories').removeClass('hidden');
        if (tabId === 'tab-users') $('#content-users').removeClass('hidden');
    });

    let nextCatId = 5; 
    $('#addCategoryForm').submit(function(e) {
        e.preventDefault();
        let catName = $('#new-cat-name').val().trim();
        if (catName === '') return;
        let newRow = `
            <tr class="hover:bg-gray-50/50 transition hidden">
                <td class="px-6 py-3.5 font-mono text-gray-400">${nextCatId++}</td>
                <td class="px-6 py-3.5 font-bold text-gray-900 cat-name">${catName}</td>
                <td class="px-6 py-3.5 text-right"><button class="btn-delete-cat text-xs font-bold text-gray-400 hover:text-red-500 transition">刪除</button></td>
            </tr>`;
        let $newRow = $(newRow);
        $('#category-table tbody').append($newRow);
        $newRow.fadeIn(300);
        $('#new-cat-name').val('');
    });

    $('#category-table').on('click', '.btn-delete-cat', function() {
        let row = $(this).closest('tr');
        if (confirm(`確定要刪除「${row.find('.cat-name').text()}」分類嗎？`)) {
            row.fadeOut(300, function() { $(this).remove(); });
        }
    });

    $('.btn-toggle-user').click(function() {
        let btn = $(this);
        let currentStatus = btn.attr('data-status');
        let statusCell = btn.closest('tr').find('.user-status-cell');
        if (currentStatus === 'active') {
            if (confirm('確定要對該用戶實施帳號封禁懲處嗎？')) {
                btn.attr('data-status', 'banned').text('🔓 解除封禁').removeClass('bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100').addClass('bg-green-50 border-green-200 text-green-600 hover:bg-green-100');
                statusCell.html('<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-red-100 text-red-700 status-badge">已停權封禁</span>');
            }
        } else {
            btn.attr('data-status', 'active').text('🔒 帳號封禁').removeClass('bg-green-50 border-green-200 text-green-600 hover:bg-green-100').addClass('bg-rose-50 border-rose-200 text-rose-600 hover:bg-rose-100');
            statusCell.html('<span class="px-2.5 py-0.5 rounded-full text-xs font-bold bg-green-100 text-green-700 status-badge">正常運作中</span>');
        }
    });

});

document.addEventListener('DOMContentLoaded', () => {
    
    // 從網址列抓取 id 參數
    const urlParams = new URLSearchParams(window.location.search);
    const bookId = urlParams.get('id');

    // ==========================================
    // 🌟 功能 A：點讚與收藏 (v0.4.0)
    // ==========================================
    const btnLike = document.getElementById('btn-like');
    const btnCollect = document.getElementById('btn-collect');

    const handleInteraction = async (type, buttonElement, countElementId) => {
        if (!bookId) return;

        try {
            const response = await fetch('api/toggle_interaction.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ book_id: bookId, type: type })
            });
            const result = await response.json();

            if (result.status === 'success') {
                document.getElementById(countElementId).textContent = result.new_count;
                const svgIcon = buttonElement.querySelector('svg');

                if (result.action === 'added') {
                    if (type === 'like') {
                        buttonElement.classList.add('text-red-500', 'bg-red-50', 'border-red-200');
                        buttonElement.classList.remove('text-gray-650', 'border-gray-200');
                        svgIcon.setAttribute('fill', 'currentColor');
                    } else {
                        buttonElement.classList.add('text-yellow-600', 'bg-yellow-50', 'border-yellow-200');
                        buttonElement.classList.remove('text-gray-650', 'border-gray-200');
                        svgIcon.setAttribute('fill', 'currentColor');
                    }
                } else {
                    if (type === 'like') {
                        buttonElement.classList.remove('text-red-500', 'bg-red-50', 'border-red-200');
                        buttonElement.classList.add('text-gray-650', 'border-gray-200');
                        svgIcon.setAttribute('fill', 'none');
                    } else {
                        buttonElement.classList.remove('text-yellow-600', 'bg-yellow-50', 'border-yellow-200');
                        buttonElement.classList.add('text-gray-650', 'border-gray-200');
                        svgIcon.setAttribute('fill', 'none');
                    }
                }
            } else {
                alert(result.message);
            }
        } catch (error) {
            console.error('AJAX 請求失敗:', error);
        }
    };

    if (btnLike) btnLike.addEventListener('click', () => handleInteraction('like', btnLike, 'like-count'));
    if (btnCollect) btnCollect.addEventListener('click', () => handleInteraction('collect', btnCollect, 'collect-count'));


    // ==========================================
    // 🌟 功能 B：社群留言板 AJAX 寫入 (v0.4.1)
    // ==========================================
    const commentForm = document.getElementById('commentForm');
    const commentInput = document.getElementById('comment-input');
    const commentList = document.getElementById('comment-list');
    const emptyState = document.getElementById('empty-state');

    if (commentForm) {
        commentForm.addEventListener('submit', async (e) => {
            e.preventDefault(); // 阻止表單預設的網頁重整
            
            const content = commentInput.value.trim();
            if (content === '' || !bookId) return;

            try {
                const response = await fetch('api/add_comment.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({
                        book_id: bookId,
                        content: content
                    })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    // 1. 若原本有「目前沒有留言」的提示，將其隱藏
                    if (emptyState) emptyState.style.display = 'none';

                    // 2. 擷取留言者名字的第一個字作為頭像
                    const firstChar = result.uname.charAt(0);

                    // 3. 動態組合新留言的 HTML 結構
                    const newCommentHTML = `
                        <div class="flex gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100 opacity-0 transition-opacity duration-500" id="new-comment-${Date.now()}">
                            <div class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex-shrink-0 flex items-center justify-center font-black text-xs">
                                ${firstChar}
                            </div>
                            <div>
                                <div class="flex items-baseline gap-2 mb-1">
                                    <span class="text-sm font-bold text-gray-900">${result.uname} (剛剛)</span>
                                    <span class="text-xs text-gray-400 font-mono">${result.time}</span>
                                </div>
                                <p class="text-sm text-gray-600 leading-relaxed">${result.content}</p>
                            </div>
                        </div>
                    `;

                    // 4. 將新留言安插到列表的最上方
                    commentList.insertAdjacentHTML('afterbegin', newCommentHTML);
                    
                    // 5. 觸發淡入動畫並清空輸入框
                    setTimeout(() => {
                        commentList.firstElementChild.classList.remove('opacity-0');
                    }, 50);
                    commentInput.value = '';

                } else {
                    alert(result.message);
                }
            } catch (error) {
                console.error('留言發布失敗:', error);
            }
        });
    }

    // ==========================================
    // 🌟 功能 C：申請預約領取 (v0.5.0)
    // ==========================================
    const btnReserve = document.getElementById('btn-reserve');

    if (btnReserve) {
        btnReserve.addEventListener('click', async () => {
            // 再次確認防呆
            if (!confirm('確定要預約領取這本書嗎？預約後請務必與捐贈者聯繫面交！')) return;
            if (!bookId) return;

            // 讓按鈕呈現載入中，防止連點
            const originalText = btnReserve.innerHTML;
            btnReserve.innerHTML = '🔄 處理中...';
            btnReserve.disabled = true;
            btnReserve.classList.add('opacity-70', 'cursor-not-allowed');

            try {
                const response = await fetch('api/reserve_book.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ book_id: bookId })
                });

                const result = await response.json();

                if (result.status === 'success') {
                    alert(`🎉 ${result.message}\n您的交易編號為：${result.trade_code}`);
                    // 預約成功後，強制重新整理頁面，讓 PHP 重新渲染「已預約」的狀態標籤
                    window.location.reload();
                } else {
                    alert(`❌ ${result.message}`);
                    // 失敗的話把按鈕恢復原狀
                    btnReserve.innerHTML = originalText;
                    btnReserve.disabled = false;
                    btnReserve.classList.remove('opacity-70', 'cursor-not-allowed');
                }
            } catch (error) {
                console.error('預約請求失敗:', error);
                alert('系統連線發生異常，請稍後再試！');
                btnReserve.innerHTML = originalText;
                btnReserve.disabled = false;
                btnReserve.classList.remove('opacity-70', 'cursor-not-allowed');
            }
        });
    }
});


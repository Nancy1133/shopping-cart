<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>購物車</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php require_once 'nav.php'; ?>
    
    <h1>我的購物車</h1>

    <table id="cart" border="1">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"> 全選</th>
                <th>商品名稱</th>
                <th>單價</th>
                <th>數量</th>
                <th>總價</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <!-- 購物車商品將會動態添加到這裡 -->
        </tbody>
    </table>

    <h3>總金額：<span id="totalPrice">0</span> 元</h3>
    <button id="checkout">結帳選擇的商品</button>

    <script>
        // 省略了購物車操作的 JavaScript 代碼，保持不變。

        // 結帳選擇的商品
        $('#checkout').on('click', function() {
            var selectedTotal = 0;
            var selectedItems = [];

            $('.item-select:checked').each(function() {
                var row = $(this).closest('tr');
                var p_no = row.find('.delete').data('pno');
                var price = parseFloat(row.find('.price').text());
                var quantity = parseInt(row.find('.quantity').val());

                selectedTotal += price * quantity;
                selectedItems.push({
                    p_no: p_no,
                    quantity: quantity
                });
            });

            if (selectedTotal > 0) {
                // 確認結帳，將選擇的商品傳送到 checkout.php
                $.ajax({
                    url: 'checkout.php',
                    method: 'POST',
                    data: { items: selectedItems },
                    success: function(response) {
                        var order_id = response.order_id; // 假設 checkout.php 返回訂單 ID
                        alert('結帳成功！總金額：' + selectedTotal + ' 元');
                        window.location.href = 'view_order.php?order_id=' + order_id; // 跳轉到訂單檢視頁面
                    }
                });
            } else {
                alert('請選擇至少一件商品進行結帳');
            }
        });

        // 初始化購物車
        loadCart();
    </script>
</body>
</html>

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
        // 載入購物車
        function loadCart() {
            $.ajax({
                url: 'load_cart.php',
                method: 'GET',
                success: function(response) {
                    $('#cart tbody').html(response);
                    updateTotalPrice(); // 每次載入購物車後更新總金額
                }
            });
        }

        // 更新購物車顯示並更新總金額
        function updateCart(action, p_no, quantity) {
            $.ajax({
                url: 'update_cart.php',
                method: 'POST',
                data: { action: action, p_no: p_no, quantity: quantity },
                success: function(response) {
                    loadCart();
                }
            });
        }

        // 計算並更新總金額
        function updateTotalPrice() {
            var total = 0;
            $('#cart tbody tr').each(function() {
                if ($(this).find('.item-select').is(':checked')) { // 只計算選中的商品
                    var price = parseFloat($(this).find('.price').text());
                    var quantity = parseInt($(this).find('.quantity').val());
                    total += price * quantity;
                }
            });
            $('#totalPrice').text(total);
        }

        // 增加商品數量
        $(document).on('click', '.increase', function() {
            var p_no = $(this).data('pno');
            updateCart('increase', p_no, 1);
        });

        // 減少商品數量
        $(document).on('click', '.decrease', function() {
            var p_no = $(this).data('pno');
            updateCart('decrease', p_no, 1);
        });

        // 直接修改商品數量
        $(document).on('input', '.quantity', function() {
            var p_no = $(this).data('pno');
            var newQuantity = parseInt($(this).val());
            if (newQuantity <= 0) {
                updateCart('delete', p_no, 0);
            } else {
                updateCart('update', p_no, newQuantity);
            }
        });

        // 刪除商品
        $(document).on('click', '.delete', function() {
            var p_no = $(this).data('pno');
            updateCart('delete', p_no, 0);
        });

        // 全選/取消全選
        $('#selectAll').on('change', function() {
            $('.item-select').prop('checked', this.checked);
            updateTotalPrice(); // 全選時更新總金額
        });

        // 計算選擇的商品總金額
        $('#cart').on('change', '.item-select', function() {
            updateTotalPrice(); // 當用戶選擇或取消選擇商品時，更新總金額
        });

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
                // 確認結帳，刪除選擇的商品並刷新頁面
                $.ajax({
                    url: 'checkout.php',
                    method: 'POST',
                    data: { items: selectedItems },
                    success: function(response) {
                        alert('結帳成功！總金額：' + selectedTotal + ' 元');
                        location.reload(); // 刷新頁面
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

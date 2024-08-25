<!DOCTYPE html>
<html lang="zh-TW">
<head>
    <meta charset="UTF-8">
    <title>商品列表</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php require_once 'nav.php'; ?>
    <h1>商品列表</h1>

    <table border="1">
        <thead>
            <tr>
                <th>商品名稱</th>
                <th>價格</th>
                <th>數量</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php
            include 'conndb.php';

            $sql = "SELECT no, name, price FROM products";
            $result = $conn->query($sql);

            while($row = $result->fetch_assoc()) {
                echo '<tr>
                    <td>' . $row['name'] . '</td>
                    <td>' . $row['price'] . ' 元</td>
                    <td><input type="number" id="quantity_' . $row['no'] . '" value="1" min="1"></td>
                    <td><button class="add-to-cart" data-pno="' . $row['no'] . '">新增至購物車</button></td>
                </tr>';
            }

            $conn->close();
            ?>
        </tbody>
    </table>

    <script>
        // 新增至購物車
        $(document).on('click', '.add-to-cart', function() {
            var p_no = $(this).data('pno');
            var quantity = $('#quantity_' + p_no).val();

            $.ajax({
                url: 'add_to_cart.php',
                method: 'POST',
                data: { p_no: p_no, quantity: quantity },
                success: function(response) {
                    if(response === 'Success') {
                        alert('商品已新增至購物車');
                    } else {
                        alert('新增至購物車時發生錯誤：' + response);
                    }
                }
            });
        });
    </script>
</body>
</html>

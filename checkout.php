<?php
include 'conndb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $items = $_POST['items'];

    // 生成唯一的訂單號碼
    include 'db_connect.php';
    $order_id = generateUniqueOrderId($conn);

    // 插入訂單資料
    $query = "INSERT INTO orders (order_id, order_date) VALUES (?, NOW())";
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $stmt->close();
    }

    // 處理每個商品
    foreach ($items as $item) {
        $p_no = $item['p_no'];
        $quantity = $item['quantity'];

        // 插入訂單項目到 order_items 表格
        $query = "INSERT INTO order_items (order_id, product_id, quantity) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("iii", $order_id, $p_no, $quantity);
            $stmt->execute();
            $stmt->close();
        }

        // 刪除購物車中的商品
        $query = "DELETE FROM car WHERE p_no = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $p_no);
            $stmt->execute();
            $stmt->close();
        }
    }

    $conn->close();
    echo "Success";

    // 重定向至訂單檢視頁面
    header("Location: view_order.php?order_id=" . $order_id);
    exit();
}

function generateUniqueOrderId($conn) {
    $order_id = '';
    $is_unique = false;

    while (!$is_unique) {
        $order_id = str_pad(mt_rand(0, 9999999999), 10, '0', STR_PAD_LEFT);

        $query = "SELECT COUNT(*) FROM orders WHERE order_id = ?";
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param("i", $order_id);
            $stmt->execute();
            $stmt->bind_result($count);
            $stmt->fetch();

            if ($count == 0) {
                $is_unique = true;
            }

            $stmt->close();
        }
    }

    return $order_id;
}
?>

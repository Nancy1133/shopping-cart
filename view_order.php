<?php
include 'db_connect.php';

$order_id = isset($_GET['order_id']) ? intval($_GET['order_id']) : 0;

if ($order_id > 0) {
    // 查詢訂單和商品內容
    $query = "
        SELECT products.product_name, order_items.quantity, order_items.price 
        FROM order_items 
        JOIN products ON order_items.product_id = products.product_id 
        WHERE order_items.order_id = ?";
    
    if ($stmt = $conn->prepare($query)) {
        $stmt->bind_param("i", $order_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<h1>訂單號碼: $order_id</h1>";
            echo "<table border='1'>";
            echo "<tr><th>商品名稱</th><th>數量</th><th>單價</th><th>總價</th></tr>";

            while ($row = $result->fetch_assoc()) {
                $product_name = htmlspecialchars($row['product_name']);
                $quantity = intval($row['quantity']);
                $price = number_format($row['price'], 2);
                $total = number_format($row['quantity'] * $row['price'], 2);

                echo "<tr>";
                echo "<td>$product_name</td>";
                echo "<td>$quantity</td>";
                echo "<td>\$$price</td>";
                echo "<td>\$$total</td>";
                echo "</tr>";
            }

            echo "</table>";
        } else {
            echo "<p>找不到訂單內容。</p>";
        }

        $stmt->close();
    }
} else {
    echo "<p>無效的訂單號碼。</p>";
}

$conn->close();
?>

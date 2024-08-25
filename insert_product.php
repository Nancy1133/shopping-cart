<?php
include 'conndb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $price = $_POST['price'];
    $create_at = date("Y-m-d H:i:s");  // 獲取當前的日期和時間
    $update_at = $create_at;           // 將 `update_at` 初始設置為與 `create_at` 相同

    // 檢查名稱是否已存在於資料庫中
    $check_stmt = $conn->prepare("SELECT * FROM products WHERE name = ?");
    $check_stmt->bind_param("s", $name);
    $check_stmt->execute();
    $result = $check_stmt->get_result();

    if ($result->num_rows > 0) {
        // 名稱已存在，更新價格和更新日期
        $update_stmt = $conn->prepare("UPDATE products SET price = ?, updata_at = ? WHERE name = ?");
        $update_stmt->bind_param("sss", $price, $update_at, $name);

        if ($update_stmt->execute()) {
            echo "產品已成功更新！";
        } else {
            echo "更新產品時出錯：" . $update_stmt->error;
        }

        $update_stmt->close();
    } else {
        // 名稱不存在，插入新的產品資料
        $insert_stmt = $conn->prepare("INSERT INTO products (name, price, create_at, updata_at) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $name, $price, $create_at, $update_at);

        if ($insert_stmt->execute()) {
            echo "產品已成功新增！";
        } else {
            echo "新增產品時出錯：" . $insert_stmt->error;
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
    $conn->close();
}
?>

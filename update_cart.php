<?php
include 'conndb.php';

$action = $_POST['action'];
$p_no = $_POST['p_no'];
$quantity = $_POST['quantity'];
$update_at = date("Y-m-d H:i:s");

if ($action == 'increase') {
    $sql = "UPDATE car SET quantity = quantity + 1, updata_at = '$update_at' WHERE p_no = $p_no";
} elseif ($action == 'decrease') {
    $sql = "UPDATE car SET quantity = quantity - 1, updata_at = '$update_at' WHERE p_no = $p_no";
} elseif ($action == 'update') {
    $sql = "UPDATE car SET quantity = $quantity, updata_at = '$update_at' WHERE p_no = $p_no";
} elseif ($action == 'delete') {
    $sql = "DELETE FROM car WHERE p_no = $p_no";
}

// 執行SQL查詢來更新數量或刪除商品
if (isset($sql) && $conn->query($sql) === TRUE) {
    // 檢查數量是否小於等於0，如果是則刪除該項目
    $sqlCheck = "SELECT quantity FROM car WHERE p_no = $p_no";
    $result = $conn->query($sqlCheck);
    if ($result) {
        $row = $result->fetch_assoc();
        if ($row['quantity'] <= 0) {
            $sqlDelete = "DELETE FROM car WHERE p_no = $p_no";
            $conn->query($sqlDelete);
        }
    }
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

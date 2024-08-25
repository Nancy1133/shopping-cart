<?php
include 'conndb.php';

$p_no = $_POST['p_no'];
$quantity = $_POST['quantity'];
$create_at = date("Y-m-d H:i:s");
$update_at = $create_at;

// 檢查該商品是否已在購物車中
$sql = "SELECT * FROM car WHERE p_no = $p_no";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 如果已存在，則更新數量
    $sql = "UPDATE car SET quantity = quantity + $quantity, updata_at = '$update_at' WHERE p_no = $p_no";
} else {
    // 如果不存在，則新增一筆新的紀錄
    $sql = "INSERT INTO car (p_no, quantity, create_at, updata_at) VALUES ($p_no, $quantity, '$create_at', '$update_at')";
}

if ($conn->query($sql) === TRUE) {
    echo "Success";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>

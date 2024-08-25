<?php
include 'conndb.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $items = $_POST['items'];

    foreach ($items as $item) {
        $p_no = $item['p_no'];
        $sql = "DELETE FROM car WHERE p_no = $p_no";
        $conn->query($sql);
    }

    $conn->close();
    echo "Success";
}
?>

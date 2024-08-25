<?php
include 'conndb.php';

$sql = "SELECT car.p_no, car.quantity, products.name, products.price 
        FROM car 
        JOIN products ON car.p_no = products.no";
$result = $conn->query($sql);

$output = '';
$total = 0;

while($row = $result->fetch_assoc()) {
    $itemTotal = $row['price'] * $row['quantity'];
    $total += $itemTotal;
    
    $output .= '<tr>
        <td><input type="checkbox" class="item-select"></td>
        <td>' . $row['name'] . '</td>
        <td class="price">' . $row['price'] . ' 元</td>
        <td>
            <button class="decrease" data-pno="' . $row['p_no'] . '">-</button>
            <input type="number" class="quantity" data-pno="' . $row['p_no'] . '" value="' . $row['quantity'] . '" min="1">
            <button class="increase" data-pno="' . $row['p_no'] . '">+</button>
        </td>
        <td>' . $itemTotal . ' 元</td>
        <td><button class="delete" data-pno="' . $row['p_no'] . '">刪除</button></td>
    </tr>';
}

echo $output;
?>

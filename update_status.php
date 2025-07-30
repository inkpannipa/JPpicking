<?php
require_once("include/connect.php");
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $order_no = $_POST['order_no'];
    $column = $_POST['column'];
    $status = $_POST['status'];
    $value = $_POST['value'];

    $validColumns = ['main', 'nt', 'w', 'sw', 'tw', 'cs'];

    if (!in_array($column, $validColumns) && $column !== 'unknown') {
        http_response_code(400);
        exit("❌ Invalid column name");
    }

    if ($column !== 'unknown') {
        $statusColumn = $column . "status"; // เช่น nt => ntstatus
        $stmt = $conn->prepare("UPDATE order_parts SET $statusColumn = ? WHERE order_no = ?");
        if (!$stmt) {
            http_response_code(500);
            exit("❌ SQL error: " . $conn->error);
        }
        $stmt->bind_param("ss", $status, $order_no);
        $stmt->execute();
        $stmt->close();
        echo "✅ Updated {$statusColumn} = {$status} for {$order_no}";
    } else {
        echo "❌ No matched part found for value: {$value}";
    }
}
?>
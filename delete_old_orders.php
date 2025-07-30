<?php
require_once("include/connect.php"); // เชื่อมต่อฐานข้อมูล

// 1. ตรวจสอบว่าวันนี้ตรงรอบ 7 วันหรือไม่
$today = date('Y-m-d');
$startDate = '2024-01-01'; // ปรับเป็นวันที่เริ่มต้นจริงของคุณ

$diff = (strtotime($today) - strtotime($startDate)) / (60 * 60 * 24);

if ($diff % 7 !== 0) {
    exit("Not the 7th day interval. Skipped.\n");
}

// 2. ลบข้อมูลเก่ากว่า 7 วัน
$sql = "DELETE FROM order_parts WHERE created_at < NOW() - INTERVAL 7 DAY";

if ($conn->query($sql) === TRUE) {
    echo "Deleted old data older than 7 days successfully.\n";
} else {
    echo "Error deleting data: " . $conn->error . "\n";
}

$conn->close();
?>

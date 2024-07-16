<?php
// ตัวอย่างการเชื่อมต่อฐานข้อมูล MySQL
$servername = "localhost"; // เปลี่ยนตามการตั้งค่าของคุณ
$username = "root"; // เปลี่ยนตามการตั้งค่าของคุณ
$password = ""; // เปลี่ยนตามการตั้งค่าของคุณ
$dbname = "ITIssueReports";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าการเข้ารหัส
$conn->set_charset("utf8mb4");

// คำสั่ง SQL สำหรับดึงข้อมูลการแจ้งปัญหา
$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // เริ่มต้นสร้าง HTML สำหรับแสดงสถานะ
    $output = '<div class="container">';
    
    // วนลูปเพื่อดึงข้อมูลแต่ละแถว
    while ($row = $result->fetch_assoc()) {
        $statuses = ["ส่งข้อมูลเข้าระบบ", "เจ้าหน้าที่รับเรื่องแล้ว", "แก้ไขเรียบร้อย"];
        $current_status = array_search($row["status"], $statuses);
        
        // สร้าง HTML สำหรับแสดงสถานะที่ได้
        $output .= '<div class="problem">';
        $output .= '<h3>' . htmlspecialchars($row["cause"]) . '<br>'.'</h3>';
        $output .= '<br>';
        
        foreach ($statuses as $index => $status) {
            $color = $index <= $current_status ? "green" : "red";
            $output .= '<div class="circle" style="background-color:' . $color . ';">';
            $output .= '<div class="circle-text">' . htmlspecialchars($status) . '</div>';
            $output .= '</div>';
            $output .= '<br>';
        }
        $output .='<br>';
        $output .= '</div>'; // ปิด div problem
    }
    
    // ปิดส่วน HTML สำหรับแสดงสถานะ
    $output .= '</div>';
    
    // แสดงผลลัพธ์ที่สร้างขึ้นไปยังหน้าเว็บ
    echo $output;
} else {
    echo "ไม่พบข้อมูลการแจ้งปัญหา";
}

// ปิดการเชื่อมต่อ
$conn->close();
?>

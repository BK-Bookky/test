<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ITIssueReports";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// ตั้งค่าการเข้ารหัส
$conn->set_charset("utf8mb4");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    $report_id = $_POST['id'];

    // ลบข้อมูล
    $sql = "DELETE FROM reports WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $report_id);

    if ($stmt->execute()) {
        echo "<script>alert('ลบปัญหาเรียบร้อยแล้ว');
        window.location.href = 'admin.php';
        </script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาด: " . $stmt->error . "');
        window.location.href = 'admin.php';
        </script>";
    }

    $stmt->close();
} else {
    echo "<script>alert('ข้อมูลไม่ถูกต้อง');</script>";
}

$conn->close();
?>

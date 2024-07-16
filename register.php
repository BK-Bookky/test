<?php
session_start();
$servername = "localhost";
$username = "root"; // เปลี่ยนตามการตั้งค่าของคุณ
$password = ""; // เปลี่ยนตามการตั้งค่าของคุณ
$dbname = "it_department";

// สร้างการเชื่อมต่อ
$conn = new mysqli($servername, $username, $password, $dbname);

// ตรวจสอบการเชื่อมต่อ
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $input_username = $_POST['username'];
    $input_email = $_POST['email'];
    $input_password = $_POST['password'];
    $input_confirm_password = $_POST['confirm_password'];

    if ($input_password !== $input_confirm_password) {
        echo "รหัสผ่านไม่ตรงกัน";
        exit;
    }

    $hashed_password = password_hash($input_password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sss", $input_username, $input_email, $hashed_password);
        if ($stmt->execute()) {
            // สมัครสมาชิกสำเร็จ แสดง Alert แล้ว redirect ไปยังหน้า index.html
            echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href = 'login.html';</script>";
            // หรือถ้าใช้ header
            // echo "<script>alert('สมัครสมาชิกสำเร็จ'); window.location.href = 'index.html';</script>";
            // header('Location: index.html');
            exit;
        } else {
            echo "เกิดข้อผิดพลาด: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "เกิดข้อผิดพลาดในการเตรียม statement: " . $conn->error;
    }
}
$conn->close();
?>
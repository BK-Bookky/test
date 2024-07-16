<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

require 'function.php'; // เรียกไฟล์ที่มีฟังก์ชัน sendLineNotify

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $servername = "localhost"; 
    $username = "root"; 
    $password = ""; 
    $dbname = "ITIssueReports";

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->set_charset("utf8mb4");

    $report_date = $_POST['date'];
    $reporter_name = $_POST['name'];
    $department = $_POST['department'];
    $unit = $_POST['unit'];
    $subject = $_POST['subject'];
    $details = $_POST['details'];
    $correction = $_POST['correction'];
    $cause = $_POST['cause'];
    $further_advice = $_POST['furtheradvice'];
    $status = 'รอการดำเนินการ'; 

    $stmt = $conn->prepare("INSERT INTO reports (report_date, reporter_name, department, unit, subject, details, correction, cause, further_advice, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssssss", $report_date, $reporter_name, $department, $unit, $subject, $details, $correction, $cause, $further_advice, $status);

    if ($stmt->execute()) {
        // ส่งแจ้งเตือนไปยัง LINE
        $line_token = 'diLwJz1ez1aFa6QB6QDsKJKIn5uVvDeeA0CFMj9tr5G';
        $message = "มีการแจ้งปัญหาใหม่\nวันที่: $report_date\nผู้แจ้ง: $reporter_name\nแผนก: $department\nหน่วย: $unit\nปัญหา: $subject\nสาเหตุ: $cause";
        sendLineNotify($message, $line_token);

        echo "<script>
                alert('ส่งข้อมูลเสร็จสิ้น');
                window.location.href = 'status.html';
              </script>";
    } else {
        echo "<script>
                alert('เกิดข้อผิดพลาด: " . $stmt->error . "');
                window.location.href = 'report.php';
              </script>";
    }

    $stmt->close();
    $conn->close();
}
?>

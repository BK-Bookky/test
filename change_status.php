<?php
function sendLineNotification($message) {
    $accessToken = 'diLwJz1ez1aFa6QB6QDsKJKIn5uVvDeeA0CFMj9tr5G'; // เปลี่ยนเป็น LINE Notify access token ของคุณ

    $data = [
        'message' => $message,
    ];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://notify-api.line.me/api/notify");
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer " . $accessToken,
        "Content-Type: application/x-www-form-urlencoded"
    ));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $result = curl_exec($ch);
    if (curl_errno($ch)) {
        echo 'Error:' . curl_error($ch);
    }
    curl_close($ch);
}

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

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['id']) && isset($_GET['status'])) {
    $report_id = $_GET['id'];
    $new_status = $_GET['status'];

    // อัปเดตสถานะ
    $sql = "UPDATE reports SET status = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $new_status, $report_id);

    if ($stmt->execute()) {
        // ดึงข้อมูลจากฐานข้อมูล
        $select_sql = "SELECT * FROM reports WHERE id = ?";
        $select_stmt = $conn->prepare($select_sql);
        $select_stmt->bind_param("i", $report_id);
        $select_stmt->execute();
        $result = $select_stmt->get_result();
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // สร้างข้อความแจ้งเตือน
            $message = "ปัญหา\n";
            $message .= "วันที่: " . htmlspecialchars($row['report_date']) . "\n";
            $message .= "ผู้แจ้ง: " . htmlspecialchars($row['reporter_name']) . "\n";
            $message .= "แผนก: " . htmlspecialchars($row['department']) . "\n";
            $message .= "หน่วย: " . htmlspecialchars($row['unit']) . "\n";
            $message .= "ปัญหา: " . htmlspecialchars($row['subject']) . "\n";
            $message .= "สาเหตุ: " . htmlspecialchars($row['cause']) . "\n";
            $message .= "สถานะ: $new_status";

            // ส่งแจ้งเตือนเข้าไปใน LINE group
            sendLineNotification($message);
        }

        echo "<script>alert('เปลี่ยนสถานะสำเร็จ');
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

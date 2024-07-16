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

// คำสั่ง SQL สำหรับดึงข้อมูลการแจ้งปัญหา
$sql = "SELECT * FROM reports";
$result = $conn->query($sql);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการปัญหาทั้งหมด</title>
    <link rel="stylesheet" href="styles4.css">
</head>
<body>
    <header>
        <h1>รายการปัญหาทั้งหมด</h1>
        <nav>
            <ul>
                <li><a href="admin.php">รายการปัญหาทั้งหมด</a></li>
                <li><a href="logout.php">ออกจากระบบ</a></li>
            </ul>
        </nav>
    </header>

    <main>
        <section id="all-reports">
            <h2>รายการปัญหาที่แจ้งทั้งหมด</h2>
            <div class="container">
                <?php
                if ($result->num_rows > 0) {
                    // สร้างตารางสำหรับแสดงข้อมูล
                    echo '<table>';
                    echo '<tr><th>ID</th><th>ชื่อผู้แจ้ง</th><th>สาเหตุ</th><th>แผนก</th><th>หน่วย</th><th>วันที่แจ้ง</th><th>สถานะ</th><th>การดำเนินการ</th></tr>';

                    // วนลูปเพื่อดึงข้อมูลแต่ละแถว
                    while ($row = $result->fetch_assoc()) {
                        echo '<tr>';
                        echo '<td>' . htmlspecialchars($row["id"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["reporter_name"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["cause"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["department"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["unit"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["report_date"]) . '</td>';
                        echo '<td>' . htmlspecialchars($row["status"]) . '</td>';
                        echo '<td>';
                        echo '<form action="change_status.php" method="get">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($row["id"]) . '">';
                        echo '<select name="status">';
                        echo '<option value="ส่งข้อมูลเข้าระบบ">ส่งข้อมูลเข้าระบบ</option>';
                        echo '<option value="เจ้าหน้าที่รับเรื่องแล้ว">เจ้าหน้าที่รับเรื่องแล้ว</option>';
                        echo '<option value="แก้ไขเรียบร้อย">แก้ไขเรียบร้อย</option>';
                        echo '</select>';
                        echo '<br>';
                        echo '<button type="submit" onclick="return confirm(\'คุณแน่ใจที่จะเปลี่ยนสถานะหรือไม่?\')">เปลี่ยนสถานะ</button>';
                        echo '</form>';
                        echo '<form action="delete_report.php" method="post" onsubmit="return confirm(\'คุณแน่ใจที่จะลบปัญหานี้หรือไม่?\')">';
                        echo '<input type="hidden" name="id" value="' . htmlspecialchars($row["id"]) . '">';
                        echo '<button type="submit">ลบ</button>';
                        echo '</form>';
                        echo '</td>';
                        echo '</tr>';
                    }
                    echo '</table>';
                } else {
                    echo "ไม่พบข้อมูลการแจ้งปัญหา";
                }

                // ปิดการเชื่อมต่อ
                $conn->close();
                ?>
            </div>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 บริษัท XYZ. สงวนลิขสิทธิ์.</p>
    </footer>
</body>
</html>

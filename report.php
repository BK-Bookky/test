<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แจ้งปัญหาแผนกไอที</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function confirmSubmission(event) {
            event.preventDefault(); // หยุดการส่งฟอร์มชั่วคราว
            const confirmation = confirm("ยืนยันที่จะส่งข้อมูลหรือไม่?");
            if (confirmation) {
                document.getElementById("reportForm").submit(); // ส่งฟอร์มเมื่อยืนยัน
            }
        }
    </script>
</head>
<body>
    <header>
        <h1>แจ้งปัญหาแผนกไอที</h1>
        <nav>
            <ul>
                <li><a href="report.php">แจ้งปัญหา</a></li>
                <li><a href="status.html">เช็คสถานะ</a></li>
                <?php if(isset($_SESSION['username'])): ?>
                    <li><a href="logout.php">ออกจากระบบ</a></li>
                <?php endif; ?>
            </ul>
        </nav>
    </header>

    <main>
        <section id="report-issue">
            <h2>แจ้งปัญหา</h2>
            <form id="reportForm" action="submit-issue.php" method="post" onsubmit="confirmSubmission(event)">
                <div class="form-group">
                    <label for="date">วันที่แจ้งปัญหา:</label>
                    <input type="date" id="date" name="date" required>
                </div>
                
                <div class="form-group">
                    <label for="name">ชื่อผู้แจ้ง:</label>
                    <input type="text" id="name" name="name">
                </div>

                <div class="form-group">
                    <label for="department">แผนก:</label>
                    <input type="text" id="department" name="department" required>
                </div>
                
                <div class="form-group">
                    <label for="unit">หน่วย:</label>
                    <input type="text" id="unit" name="unit" required>
                </div>
                
                <div class="form-group">
                    <label for="subject">ปัญหา:</label>
                    <div class="radio-group">
                        <input type="radio" id="hardware" name="subject" value="ฮาร์ดแวร์">
                        <label for="hardware">ฮาร์ดแวร์</label>
                        <input type="radio" id="software" name="subject" value="ซอฟต์แวร์">
                        <label for="software">ซอฟต์แวร์</label>
                        <input type="radio" id="data" name="subject" value="ข้อมูล">
                        <label for="data">ข้อมูล(DATA)</label>
                        <input type="radio" id="network" name="subject" value="เน็ตเวิร์ก">
                        <label for="network">เน็ตเวิร์ก</label>
                        <input type="radio" id="program" name="subject" value="โปรแกรม">
                        <label for="program">โปรแกรม</label>
                        <input type="radio" id="printer" name="subject" value="เครื่องพิมพ์">
                        <label for="printer">เครื่องพิมพ์</label>
                        <input type="radio" id="other" name="subject" value="อื่นๆ">
                        <label for="other">อื่นๆ</label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="details">รายละเอียดปัญหา:</label>
                    <textarea id="details" name="details"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="correction">การแก้ไข:</label>
                    <textarea id="correction" name="correction"></textarea>
                </div>
                
                <div class="form-group">
                    <label for="cause">สาเหตุ:</label>
                    <textarea id="cause" name="cause" required></textarea>
                </div>
                
                <div class="form-group">
                    <label for="furtheradvice">คำแนะนำเพิ่มเติม:</label>
                    <textarea id="furtheradvice" name="furtheradvice" ></textarea>
                </div>
                                    
                <div class="form-group">
                    <button type="submit">ส่งข้อมูล</button>
                </div>
            </form>
        </section>
    </main>

    <footer>
        <p>&copy; 2024 บริษัท XYZ. สงวนลิขสิทธิ์.</p>
    </footer>
</body>
</html>

